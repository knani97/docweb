<?php

namespace App\Controller;

use App\Entity\Video;
use App\Entity\Panier;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    /**
     * @Route("/search", name="search")
     */
    public function search(Request $request): Response
    {
        $inp=$request->request->get('inp');
        $c=$this->getDoctrine()->getRepository(Video::class)->findBy(['titre' => $inp]);
        if ($c == NULL)
        $c=$this->getDoctrine()->getRepository(Video::class)->findBy(['id' => $inp]);
        return $this->render('video/index1.html.twig', [
            'videos' => $c
        ]);
    }

    /**
     * @Route("/admin/note_add/{n}/{id}", name="note_add")
     */
    public function add($n , Video $video): Response
    {
        $video->setNote($video->getNote() + $n);
        
        $videos = $this->getDoctrine()->getRepository(Video::class)->findBy(['panier' => 1]);
        $panier = $this->getDoctrine()->getRepository(Panier::class)->findOneBy(['id' => 1]);
        $this->getDoctrine()->getManager()->flush();
        return $this->redirectToRoute('video_index');
    }

        /**
     * @Route("/admin/", name="home1")
     */
    public function index1(): Response
    {
        return $this->render('home/index1.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
}
