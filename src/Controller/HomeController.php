<?php

namespace App\Controller;

use App\Entity\Fiche;
use App\Entity\Panier;
use App\Entity\Pharmacie;
use App\Entity\Medicament;
use App\Entity\Video;
use App\Repository\MedicamentRepository;
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
        return $this->render('index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
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

    /**
     * @Route("/search", name="search")
     */
    public function search(Request $request){
        $inp = $request->request->get('inp');
        $pharm = $this->getDoctrine()->getRepository(Pharmacie::class)->findBy(['nom' => $inp]);
        $med = $this->getDoctrine()->getRepository(Medicament::class)->findBy(['nom' => $inp]);
        $pharm_gouv = $this->getDoctrine()->getRepository(Pharmacie::class)->findBy(['gouv'=>$inp]);
        return $this->render("home/show.html.twig",[
            'pharmacies' => $pharm,
            'medicaments' => $med,
            'pharm_gouv'=>$pharm_gouv
        ]);
    }

    /**
     * @Route("/search_admin6", name="search_admin")
     */
    public function search1(Request $request){
        $inp = $request->request->get('inp');
        $pharm = $this->getDoctrine()->getRepository(Pharmacie::class)->findBy(['nom' => $inp]);
        $med = $this->getDoctrine()->getRepository(Medicament::class)->findBy(['nom' => $inp]);
        $pharm_gouv = $this->getDoctrine()->getRepository(Pharmacie::class)->findBy(['gouv'=>$inp]);

        if (count($pharm) == 0)
            $pharm = $this->getDoctrine()->getRepository(Pharmacie::class)->findBy(['id' => $inp]);

        if (count($med) == 0)
            $med = $this->getDoctrine()->getRepository(Medicament::class)->findBy(['id' => $inp]);

        return $this->render("home/show1.html.twig",[
            'pharmacies' => $pharm,
            'medicaments' => $med,
            'pharm_gouv'=>$pharm_gouv
        ]);
    }

    /**
     * @Route("/searchVid", name="searchVid")
     */
    public function searchVid(Request $request): Response
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
}
