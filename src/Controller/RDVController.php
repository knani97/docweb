<?php

namespace App\Controller;

use App\Entity\RDV;
use App\Entity\Tache;
use App\Entity\User;
use App\Form\RDVType;
use App\Form\ReporterType;
use App\Repository\CalendrierRepository;
use App\Repository\RDVRepository;
use App\Repository\TacheRepository;
use App\Repository\UserRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RDVController extends AbstractController
{
    /**
     * @Route("/r/d/v", name="r_d_v")
     */
    public function index(): Response
    {
        return $this->render('rdv/index.html.twig', [
            'controller_name' => 'RDVController',
        ]);
    }

    /**
     * @Route("/rdv/add", name="addRDV")
     */
    public function add(TacheRepository $repository, UserRepository $userRepository, CalendrierRepository $calendrierRepository, Request $request): Response
    {
        $uid = $request->getSession()->get('id');
        $rdv = new RDV();
        $user = $userRepository->find($uid);
        $calendrier = $calendrierRepository->findByUid($uid);

        $rdv->setDate(new \DateTime());

        $form = $this->createForm(RDVType::class, $rdv, array( 'method'=>'GET'));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();

            $tache = new Tache();

            $rdv->getTacheDispo()->setType(1);
            $rdv->getTacheDispo()->setCouleur('#FF0000');
            $rdv->getTacheDispo()->setLibelle('RDV');
            $rdv->getTacheDispo()->setDescription('RDV avec '.$user);
            $rdv->setDate($rdv->getTacheDispo()->getDate());
            $rdv->setEtat(1);
            $rdv->setPatient($user);

            $tache->setDate($rdv->getTacheDispo()->getDate());
            $tache->setDuree($rdv->getTacheDispo()->getDuree());
            $tache->setLibelle('RDV');
            $tache->setDescription('RDV avec Dr.'.$rdv->getMedecin());
            $tache->setCalendrier($calendrier);
            $tache->setCouleur('#FF0000');
            $tache->setType(1);

            $rdv->setTacheUser($tache);

            $em->persist($tache);
            $em->persist($rdv);
            $em->flush();

            return $this->redirectToRoute('listRDV');
        }

        return $this->render('rdv/add.html.twig', [
            'controller_name' => 'RDVController',
            'form' => $form->createView(),
            'cal' => $calendrier
        ]);
    }

    /**
     * @Route("/rdv/list", name="listRDV")
     */
    public function list(RDVRepository $repository, Request $request): Response
    {
        $uid = $request->getSession()->get('id');

        if ($request->getSession()->get('type') == 1)
            $tab = $repository->findByPatient($uid);
        else if ($request->getSession()->get('type') == 2)
            $tab = $repository->findByMedecin($uid);

        return $this->render('rdv/list.html.twig', [
            'controller_name' => 'RDVController',
            'tab' => $tab,
        ]);
    }

    /**
     * @Route("/rdv/annuler/{id}", name="annulerRDV")
     */
    public function annuler($id, RDVRepository $repository, Request $request): Response
    {
        $rdv = $repository->find($id);

        $rdv->setEtat(3);
        $rdv->getTacheDispo()->setType(4);
        $rdv->getTacheUser()->setType(4);

        $this->getDoctrine()->getManager()->flush();

        return $this->redirectToRoute('listRDV');
    }

    /**
     * @Route("/rdv/reporter/{id}", name="reporterRDV")
     */
    public function reporter($id, RDVRepository $repository, Request $request): Response
    {
        $rdv = $repository->find($id);

        $form = $this->createForm(ReporterType::class, $rdv);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();

            $rdv->setEtat(2);
            $rdv->getTacheDispo()->setDate($rdv->getDate());
            $rdv->getTacheUser()->setDate($rdv->getDate());

            $em->flush();

            return $this->redirectToRoute('listRDV');
        }

        return $this->render('rdv/reporter.html.twig', [
            'controller_name' => 'RDVController',
            'form' => $form->createView(),
        ]);
    }
}
