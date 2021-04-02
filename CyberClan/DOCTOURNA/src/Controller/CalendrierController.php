<?php

namespace App\Controller;

use App\Entity\Calendrier;
use App\Entity\Disponibilite;
use App\Entity\Tache;
use App\Entity\User;
use App\EventSubscriber\CalendarSubscriber;
use App\Form\CalendrierType;
use App\Form\DisponibiliteType;
use App\Form\TacheType;
use App\Repository\CalendrierRepository;
use App\Repository\TacheRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Profiler\Profiler;
use Symfony\Component\Routing\Annotation\Route;

class CalendrierController extends AbstractController
{
    /**
     * @Route("/calendrier", name="calendrier")
     */
    public function index(UserRepository $userRep, CalendrierRepository $repository, Request $request): Response
    {
        if ($request->getSession()->get('id') == null)
            return $this->render('user/erreur-login.html.twig', []);

        $uid = $request->getSession()->get('id');

        $tab = $repository->findByUid($uid);
        $user = $userRep->find($uid);
        $calendrier = new Calendrier();

        $calendrier->setType(1);
        $calendrier->setUid($user);

        $form = $this->createForm(CalendrierType::class, $calendrier);
                $form->handleRequest($request);
                if ($form->isSubmitted() && $form->isValid())
                {
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($calendrier);
                    $em->flush();

                    return $this->redirectToRoute('calendrier');
                }

        return $this->render('calendrier/index.html.twig', [
            'controller_name' => 'CalendrierController',
            'form' => $form->createView(),
            'tab' => $tab,
        ]);
    }

    /**
     * @Route("/calendrier/edit", name="editC")
     */
    public function editC(CalendrierRepository $repository, Request $request, Profiler $profiler): Response
    {
        if ($request->getSession()->get('id') == null)
            return $this->render('user/erreur-login.html.twig', []);

        $uid = $request->getSession()->get('id');

        $calendrier = $repository->findByUid($uid);

        $form = $this->createForm(CalendrierType::class, $calendrier);
        $form->handleRequest($request);

        if ($form->isSubmitted())
        {
            $em = $this->getDoctrine()->getManager();

            $em->flush();
        }

        $profiler->disable();

        return $this->render('calendrier/edit.html.twig', [
            'controller_name' => 'CalendrierController',
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/calendrier/view/{year}/{month}", name="view")
     */
    public function view($year, $month, CalendrierRepository $calRep, TacheRepository $repository, Request $request): Response
    {
        if ($request->getSession()->get('id') == null)
            return $this->render('user/erreur-login.html.twig', []);

        $uid = $request->getSession()->get('id');

        $cal = $calRep->findByUid($uid);
        $tab = $repository->findByCal($cal->getId());

        $tache = new Tache();
        $tache->setCalendrier($cal);
        $form1 = $this->createForm(TacheType::class, $tache);

                $form1->handleRequest($request);
                if ($form1->isSubmitted() && $form1->isValid())
                {
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($tache);
                    $em->flush();

                    return $this->redirectToRoute("view", array('year' => $year, 'month' => $month));
                }

        return $this->render('calendrier/view.html.twig', [
            'tab' => $tab,
            'year' => $year,
            'month' => $month,
            'form1' => $form1->createView(),
            'cal' => $cal
        ]);
    }

    /**
     * @Route("/calendrier/view/{year}/{month}/{id}", name="viewCalAdmin")
     */
    public function viewAdmin($year, $month, $id, CalendrierRepository $calRep, TacheRepository $repository, Request $request): Response
    {
        $cal = $calRep->find($id);
        $tab = $repository->findByCal($cal->getId());

        return $this->render('calendrier/viewCalAdmin.html.twig', [
            'tab' => $tab,
            'year' => $year,
            'month' => $month,
            'cal' => $cal
        ]);
    }

    /**
     * @Route("/dashboard", name="dashboardC")
     */
    public function dashboard(CalendrierRepository $repository): Response
    {
        $tab = $repository->findAll();

        return $this->render('calendrier/dashboard-cal.html.twig', [
            'tab' => $tab
        ]);
    }

    /**
     * @Route("/calendrier/delete/{id}", name="deleteC")
     */
    public function delete($id, CalendrierRepository $repository, Request $request): Response
    {
        if ($request->getSession()->get('id') == null)
            return $this->render('user/erreur-login.html.twig', []);

        $uid = $request->getSession()->get('id');

        $calendrier = $repository->findByUid($uid);
        $em = $this->getDoctrine()->getManager();
        $em->remove($calendrier);
        $em->flush();

        return $this->redirectToRoute('calendrier');
    }

    /**
     * @Route("/calendrier/deletedc/{id}", name="deleteDC")
     */
    public function deletedc($id, CalendrierRepository $repository, Request $request): Response
    {
        if ($request->getSession()->get('id') == null)
            return $this->render('user/erreur-login.html.twig', []);

        $uid = $request->getSession()->get('id');

        $calendrier = $repository->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($calendrier);
        $em->flush();

        return $this->redirectToRoute('adminC');
    }

    /**
     * @Route("/calendrier/view2", name="view2")
     */
    public function view2(CalendrierRepository $calRep, TacheRepository $repository, Request $request): Response
    {
        if ($request->getSession()->get('id') == null)
            return $this->render('user/erreur-login.html.twig', []);

        $uid = $request->getSession()->get('id');

        $cal = $calRep->findByUid($uid);

        $nbTaches = count($cal->getTaches());
        $nbRDVs = 0;
        $nbDispos = 0;

        foreach ($cal->getTaches() as $tache) {
            if ($tache->getType() == 1)
                $nbRDVs++;
            else if ($tache->getType() == 4)
                $nbDispos++;
        }

        $tache = new Tache();
        $tache->setCalendrier($cal);
        $form1 = $this->createForm(TacheType::class, $tache);

        $form1->handleRequest($request);
        if ($form1->isSubmitted() && $form1->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $em->persist($tache);
            $em->flush();

            return $this->redirectToRoute("view2");
        }
        return $this->render('calendrier/view-fullcal.html.twig', [
            'form' => $form1->createView(),
            'nbTaches' => $nbTaches,
            'nbRDVs' => $nbRDVs,
            'nbDispos' => $nbDispos,
            'couleur' => $cal->getCouleur(),
        ]);
    }
}
