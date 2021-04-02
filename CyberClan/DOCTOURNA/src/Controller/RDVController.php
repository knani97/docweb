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
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Profiler\Profiler;
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
        if ($request->getSession()->get('id') == null)
            return $this->render('user/erreur-login.html.twig', []);

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
            $rdv->getTacheDispo()->setCouleur('#FFCCCB');
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
    public function list(RDVRepository $repository, Request $request, PaginatorInterface $paginator): Response
    {
        if ($request->getSession()->get('id') == null)
            return $this->render('user/erreur-login.html.twig', []);

        $uid = $request->getSession()->get('id');

        if ($request->getSession()->get('type') == 1)
            $data = $repository->findByPatient($uid);
        else if ($request->getSession()->get('type') == 2)
            $data = $repository->findByMedecin($uid);

        $tab = $paginator->paginate(
            $data,
            $request->query->getInt('page', 1),
            4
        );

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
        if ($request->getSession()->get('id') == null)
            return $this->render('user/erreur-login.html.twig', []);

        $uid = $request->getSession()->get('id');

        $rdv = $repository->find($id);

        $rdv->setEtat(3);
        $rdv->getTacheDispo()->setType(4);
        $rdv->getTacheDispo()->setCouleur('#9932CC');
        $rdv->getTacheDispo()->setLibelle('Disponibilité');
        $rdv->getTacheDispo()->setDescription('RDV annulé.');

        $rdv->getTacheUser()->setType(4);
        $rdv->getTacheUser()->setCouleur('#9932CC');
        $rdv->getTacheUser()->setLibelle('Disponibilité');
        $rdv->getTacheUser()->setDescription('RDV annulé.');

        $this->getDoctrine()->getManager()->flush();

        return $this->redirectToRoute('listRDV');
    }

    /**
     * @Route("/rdv/reporter/{id}", name="reporterRDV")
     */
    public function reporter($id, RDVRepository $repository, Request $request): Response
    {
        if ($request->getSession()->get('id') == null)
            return $this->render('user/erreur-login.html.twig', []);

        $uid = $request->getSession()->get('id');

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

    /**
     * @Route("/rdv/terminer/{id}", name="terminerRDV")
     */
    public function terminer($id, RDVRepository $repository, Request $request): Response
    {
        if ($request->getSession()->get('id') == null)
            return $this->render('user/erreur-login.html.twig', []);

        $uid = $request->getSession()->get('id');

        $rdv = $repository->find($id);

        $rdv->setEtat(4);
        $rdv->getTacheDispo()->setType(4);
        $rdv->getTacheDispo()->setCouleur('#9932CC');
        $rdv->getTacheDispo()->setLibelle('Disponibilité');
        $rdv->getTacheDispo()->setDescription('RDV terminé.');

        $rdv->getTacheUser()->setType(4);
        $rdv->getTacheUser()->setCouleur('#9932CC');
        $rdv->getTacheUser()->setLibelle('Disponibilité');
        $rdv->getTacheUser()->setDescription('RDV terminé.');

        $this->getDoctrine()->getManager()->flush();

        return $this->redirectToRoute('listRDV');
    }

    /**
     * @Route("/chercher1", name="chercher1")
     */
    public function chercher1(UserRepository $userRepository, Profiler $profiler): Response
    {
        $meds = $userRepository->findAllMeds();

        $profiler->disable();

        return $this->render('rdv/chercher1.html.twig', [
            'meds' => $meds,
        ]);
    }

    /**
     * @Route("/chercher2", name="chercher2")
     */
    public function chercher2(UserRepository $userRepository, Profiler $profiler): Response
    {
        $meds = $userRepository->findAllMeds();

        $profiler->disable();

        return $this->render('rdv/chercher2.html.twig', [
            'meds' => $meds,
        ]);
    }

    /**
     * @Route("/details/{id}", name="details")
     */
    public function details($id, UserRepository $userRepository, RDVRepository $repository, Request $request, Profiler $profiler): Response
    {
        if ($request->getSession()->get('id') == null)
            $res = 'anon';
        else {
            $uid = $request->getSession()->get('id');
            $res = $repository->findRDVByPatientMedecin($uid, $id);
        }

        $med = $userRepository->find($id);

        $profiler->disable();

        return $this->render('details.html.twig', [
            'med' => $med,
            'res' => $res,
        ]);
    }

    /**
     * @Route("/chatbot", name="chatbot")
     */
    public function chatbot(): Response
    {
        return $this->render('chatbot.html.twig', [
        ]);
    }
}
