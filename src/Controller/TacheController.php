<?php

namespace App\Controller;

use App\Entity\Calendrier;
use App\Entity\Disponibilite;
use App\Entity\Tache;
use DateTime;
use App\Form\DisponibiliteType;
use App\Form\TacheType;
use App\Repository\CalendrierRepository;
use App\Repository\TacheRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TacheController extends AbstractController
{
    /**
     * @Route("/tache", name="tache")
     */
    public function index(TacheRepository $repository, Request $request): Response
    {
        $tab = $repository->findAll();

        return $this->render('tache/index.html.twig', [
            'controller_name' => 'TacheController',
            'tab' => $tab,
        ]);
    }

    /**
     * @Route("/tache/add", name="addT")
     */
    public function add(CalendrierRepository $repository, Request $request): Response
    {
        $uid = $request->getSession()->get('id');
        $tache = new Tache();
        $calendrier = $repository->findByUid($uid);

        $tache->setCalendrier($calendrier);
        $tache->setDate(new \DateTime());

        $form = $this->createForm(TacheType::class, $tache);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $em->persist($tache);
            $em->flush();
        }

        return $this->render('tache/index.html.twig', [
            'f' => $form->createView(),
        ]);
    }

    /**
     * @Route("/tache/edit/{id}", name="editT")
     */
    public function edit($id, TacheRepository $repository, Request $request): Response
    {
        $uid = $request->getSession()->get('id');
        $tache = $repository->find($id);

        $form = $this->createForm(TacheType::class, $tache);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
        }

        return $this->render('tache/index.html.twig', [
            'f' => $form->createView(),
        ]);
    }

    /**
     * @Route("/tache/delete/{id}/{year}/{month}", name="deleteT")
     */
    public function delete($id, $year, $month, TacheRepository $repository): Response
    {
        $tache = $repository->find($id);
        $em = $this->getDoctrine()->getManager();

        $em->remove($tache);
        $em->flush();

        return $this->redirectToRoute("view", array('year' => $year, 'month' => $month));
    }

    /**
     * @Route("/tache/addDispo", name="addD")
     */
    public function addDispo(CalendrierRepository $repository, Request $request): Response
    {
        $uid = $request->getSession()->get('id');
        $dispo = new Disponibilite();
        $calendrier = $repository->findByUid($uid);

        $form = $this->createForm(DisponibiliteType::class, $dispo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $date = $dispo->getStartDate();

            while ($date < $dispo->getEndDate()) {
                $tache = new Tache();

                $tache->setCalendrier($calendrier);
                $tache->setType(4);
                $tache->setCouleur("#00FF00");
                $tache->setDate($date);
                $tache->setDescription("Disponibilite");
                $tache->setDuree($dispo->getDureeRDV());
                $tache->setLibelle("Disponibilite");

                $em->persist($tache);
                $em->flush();

                $date->modify('+'.$dispo->getDureeRDV()->format('H').' hours');
                $date->modify('+'.$dispo->getDureeRDV()->format('i').' minutes');
                $date->modify('+'.$dispo->getDureePause()->format('H').' hours');
                $date->modify('+'.$dispo->getDureePause()->format('i').' minutes');
            }

            $em->persist($dispo);
            $em->flush();
            $em->remove($dispo);
            $em->flush();

            return $this->render('tache/succes.html.twig', [
                'controller_name' => 'RDVController',
            ]);
        }

        return $this->render('tache/dispo.html.twig', [
            'form' => $form->createView(),
            'cal' => $calendrier
        ]);
    }
}
