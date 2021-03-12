<?php

namespace App\Controller;

use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use App\Repository\CalendrierRepository;
use App\Repository\RDVRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(CalendrierRepository $repository): Response
    {
        return $this->render('dashboard.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    /**
     * @Route("/admin/calendrier", name="adminC")
     */
    public function calendrier(CalendrierRepository $repository): Response
    {
        $tab = $repository->findAll();

        return $this->render('admin/calendriers.html.twig', [
            'controller_name' => 'AdminController',
            'tab' => $tab,
        ]);
    }

    /**
     * @Route("/admin/rdv", name="adminRDV")
     */
    public function rdv(RDVRepository $repository): Response
    {
        $tab = $repository->findAll();

        return $this->render('admin/rdvs.html.twig', [
            'controller_name' => 'AdminController',
            'tab' => $tab,
        ]);
    }

    /**
     * @Route("/", name="home")
     */
    public function home(RDVRepository $repository): Response
    {
        return $this->render('index.html.twig', [
        ]);
    }

    /**
     * @Route("/set/{id}/{nom}/{prenom}/{type}/{email}", name="set")
     */
    public function setSession($id, $nom, $prenom, $type, $email, Request $request): Response
    {
        $session = $request->getSession();

        $session->set('id', $id);
        $session->set('nom', $nom);
        $session->set('prenom', $prenom);
        $session->set('type', $type);
        $session->set('email', $email);

        return $this->redirectToRoute('home');
    }
}