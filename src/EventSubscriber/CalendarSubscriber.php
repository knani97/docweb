<?php

namespace App\EventSubscriber;

use App\Repository\TacheRepository;
use CalendarBundle\CalendarEvents;
use CalendarBundle\Entity\Event;
use CalendarBundle\Event\CalendarEvent;
use DateTime;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Csrf\TokenStorage\TokenStorageInterface;

class CalendarSubscriber implements EventSubscriberInterface
{
    private $tacheRepository;
    private $router;
    private $session;

    public function __construct(
        TacheRepository $tacheRepository,
        UrlGeneratorInterface $router,
        SessionInterface $session
    ) {
        $this->tacheRepository = $tacheRepository;
        $this->router = $router;
        $this->session = $session;
    }

    public static function getSubscribedEvents()
    {
        return [
            CalendarEvents::SET_DATA => 'onCalendarSetData',
        ];
    }

    public function onCalendarSetData(CalendarEvent $calendar)
    {
        $start = $calendar->getStart();
        $end = $calendar->getEnd();
        $filters = $calendar->getFilters();
        $id = $this->session->get('id');

        // You may want to make a custom query from your database to fill the calendar
        $taches = $this->tacheRepository
            ->createQueryBuilder('tache')
            ->where('tache.calendrier = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult()
        ;

        foreach ($taches as $tache) {
            $startDate = $tache->getDate();
            $endDate = new DateTime($startDate->format('Y-m-d H:i:s'));
            $endDate->modify('+'.$tache->getDuree()->format('H').' hours');
            $endDate->modify('+'.$tache->getDuree()->format('i').' minutes');
            $libelle = $tache->getLibelle();

            if (!empty($tache->getDescription()))
                $libelle = $tache->getLibelle().': '.$tache->getDescription();

            $event = new Event(
                $libelle,
                $startDate,
                $endDate
            );

            switch ($tache->getType()) {
                case 1:
                    $bCol = 'green';
                    break;
                case 2:
                    $bCol = 'blue';
                    break;
                case 3:
                    $bCol = 'red';
                    break;
                default:
                    $bCol = 'black';
                    break;
            }

            $event->setOptions([
                'backgroundColor' => $bCol,
                'borderColor' => $tache->getCouleur(),
                'textColor' => $tache->getCouleur(),
            ]);

            $calendar->addEvent($event);
        }
    }
}