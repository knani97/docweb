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
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Profiler\Profiler;

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
    public function add(CalendrierRepository $repository, Request $request, Profiler $profiler): Response
    {
        if ($request->getSession()->get('id') == null)
            return $this->render('user/erreur-login.html.twig', []);

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

        $profiler->disable();

        return $this->render('tache/add.html.twig', [
            'f' => $form->createView(),
        ]);
    }

    /**
     * @Route("/tache/addDef/{year}/{month}/{day}", name="addTDef")
     */
    public function addDef($year, $month, $day, CalendrierRepository $repository, Request $request, Profiler $profiler): Response
    {
        if ($request->getSession()->get('id') == null)
            return $this->render('user/erreur-login.html.twig', []);

        $uid = $request->getSession()->get('id');

        $tache = new Tache();
        $calendrier = $repository->findByUid($uid);

        $tache->setCalendrier($calendrier);
        $tache->setDate(\DateTime::createFromFormat('Y-m-d', $year.'-'.$month.'-'.$day));

        $form = $this->createForm(TacheType::class, $tache);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $em->persist($tache);
            $em->flush();
        }

        $profiler->disable();

        return $this->render('tache/add.html.twig', [
            'f' => $form->createView(),
        ]);
    }

    /**
     * @Route("/tache/addDefHM/{year}/{month}/{day}/{hour}/{minute}", name="addTDefHM")
     */
    public function addDefHM($year, $month, $day, $hour, $minute, CalendrierRepository $repository, Request $request, Profiler $profiler): Response
    {
        if ($request->getSession()->get('id') == null)
            return $this->render('user/erreur-login.html.twig', []);

        $uid = $request->getSession()->get('id');

        $tache = new Tache();
        $calendrier = $repository->findByUid($uid);

        $tache->setCalendrier($calendrier);
        $tache->setDate(\DateTime::createFromFormat('Y-m-d H:i', $year.'-'.$month.'-'.$day.' '.$hour.':'.$minute));

        $form = $this->createForm(TacheType::class, $tache);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $em->persist($tache);
            $em->flush();
        }

        $profiler->disable();

        return $this->render('tache/add.html.twig', [
            'f' => $form->createView(),
        ]);
    }

    /**
     * @Route("/tache/edit/{id}", name="editT")
     */
    public function edit($id, TacheRepository $repository, Request $request, Profiler $profiler): Response
    {
        if ($request->getSession()->get('id') == null)
            return $this->render('user/erreur-login.html.twig', []);

        $uid = $request->getSession()->get('id');

        $tache = $repository->find($id);

        $form = $this->createForm(TacheType::class, $tache);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
        }

        $profiler->disable();

        return $this->render('tache/edit.html.twig', [
            'f' => $form->createView(),
        ]);
    }

    /**
     * @Route("/tache/delete/{id}/{year}/{month}", name="deleteT")
     */
    public function delete($id, $year, $month, TacheRepository $repository, Request $request): Response
    {
        if ($request->getSession()->get('id') == null)
            return $this->render('user/erreur-login.html.twig', []);

        $uid = $request->getSession()->get('id');

        $tache = $repository->find($id);
        $em = $this->getDoctrine()->getManager();

        $em->remove($tache);
        $em->flush();

        return $this->redirectToRoute("view", array('year' => $year, 'month' => $month));
    }

    /**
     * @Route("/tache/delete2/{id}", name="deleteT2")
     */
    public function delete2($id, TacheRepository $repository, Request $request): Response
    {
        if ($request->getSession()->get('id') == null)
            return $this->render('user/erreur-login.html.twig', []);

        $uid = $request->getSession()->get('id');

        $tache = $repository->find($id);
        $em = $this->getDoctrine()->getManager();

        $em->remove($tache);
        $em->flush();

        if ($request->isXmlHttpRequest())
            return new JsonResponse('Tâche: '.$id.' effacée.');
        else
            return new Response();
    }

    /**
     * @Route("/tache/addDispo", name="addD")
     */
    public function addDispo(CalendrierRepository $repository, Request $request): Response
    {
        if ($request->getSession()->get('id') == null)
            return $this->render('user/erreur-login.html.twig', []);

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

    /**
     * @Route("/tache/save/{id}/{year}/{month}/{day}/{hour}/{minute}", name="saveT")
     */
    public function save($id, $year, $month, $day, $hour, $minute, TacheRepository $repository, Request $request): Response
    {
        if ($request->getSession()->get('id') == null)
            return $this->render('user/erreur-login.html.twig', []);

        $uid = $request->getSession()->get('id');

        $tache = $repository->find($id);

        $tache->setDate(\DateTime::createFromFormat('Y-m-d H:i', $year.'-'.$month.'-'.$day.' '.$hour.':'.$minute));

        $this->getDoctrine()->getManager()->flush();

        if ($request->isXmlHttpRequest())
            return new JsonResponse('Tâche: '.$id.' modifiée.');
        else
            return new Response();
    }

    /**
     * @Route("/tache/show/{id}", name="showT")
     */
    public function show($id, TacheRepository $repository, Request $request, Profiler $profiler): Response
    {
        if ($request->getSession()->get('id') == null)
            return $this->render('user/erreur-login.html.twig', []);

        $uid = $request->getSession()->get('id');

        $tache = $repository->find($id);

        $profiler->disable();

        return $this->render('tache/show.html.twig', [
            'tache' => $tache
        ]);
    }
}
