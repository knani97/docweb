<?php

namespace App\EventSubscriber;

use App\Repository\CalendrierRepository;
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
    private $calendrierRepository;
    private $tacheRepository;
    private $router;
    private $session;

    public function __construct(
        CalendrierRepository $calendrierRepository,
        TacheRepository $tacheRepository,
        UrlGeneratorInterface $router,
        SessionInterface $session
    ) {
        $this->calendrierRepository = $calendrierRepository;
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
        $calId = $this->calendrierRepository->findByUid($id);

        // You may want to make a custom query from your database to fill the calendar
        $taches = $this->tacheRepository
            ->createQueryBuilder('tache')
            ->where('tache.calendrier = :id')
            ->setParameter('id', $calId)
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
                    $bCol = 'red';
                    break;
                case 2:
                    $bCol = 'blue';
                    break;
                case 3:
                    $bCol = 'yellow';
                    break;
                case 4:
                    $bCol = 'green';
                    break;
                default:
                    $bCol = 'pink';
                    break;
            }

            $event->setOptions([
                'id' => $tache->getId(),
                'backgroundColor' => $bCol,
                'borderColor' => $tache->getCouleur(),
                'textColor' => $tache->getCouleur(),
            ]);

            if ($tache->getType() == 1) {
                $event->addOption('editable', false);
                $event->addOption('groupId', 'noEd');
            }

            $calendar->addEvent($event);
        }
    }
}