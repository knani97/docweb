<?php

namespace App\Command;

use App\Entity\RDV;
use Doctrine\ORM\EntityManagerInterface;
use http\Env\Request;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class RappelEmailCommand extends Command
{
    protected static $defaultName = 'remind:users';
    protected static $defaultDescription = 'Add a short description for your command';

    private $em;
    private $mailer;
    protected $requestStack;

    public function __construct(EntityManagerInterface $em, \Swift_Mailer $mailer, RequestStack $requestStack)
    {
        parent::__construct();
        $this->em = $em;
        $this->mailer = $mailer;
        $this->requestStack = $requestStack;
    }


    protected function configure()
    {
        $this
            ->setName('remind:users')
            // more configuration
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $rdvs = $this->em->getRepository(RDV::class)
            ->findAllEventsWithDateTomorrow();
        $request = $this->requestStack->getCurrentRequest();

        foreach ($rdvs as $rdv) {
            $message = (new \Swift_Message('Rappel RDV'))
                ->setFrom('mouhebbenabdallah@gmail.com')
                ->setTo($rdv->getPatient()->getEmail())
                ->setBody('Vous avez un RDV avec Docteur '.$rdv->getMedecin().' demain à '.$rdv->getDate()->format('Y-m-d H:i:s').'!');

            $this->mailer->send($message);
        }

        return 0;
    }
}
