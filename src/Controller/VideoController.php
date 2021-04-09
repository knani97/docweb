<?php

namespace App\Controller;

use App\Entity\Panier;
use App\Entity\Video;
use App\Form\VideoType;
use App\Repository\VideoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/video")
 */
class VideoController extends AbstractController
{
    /**
     * @Route("/", name="video_index", methods={"GET"})
     */
    public function index(VideoRepository $videoRepository, Request $request): Response
    {
        $uid = $request->getSession()->get('id');
        $panier=$this->getDoctrine()->getRepository(Video::class)->findBy(['panier' => $uid]);
        return $this->render('video/index.html.twig', [
            'videos' => $videoRepository->findAll(),
            'count' =>count($panier)
        ]);
    }
    /**
     * @Route("/admin", name="video_index1", methods={"GET"})
     */
    public function index1(VideoRepository $videoRepository): Response
    {
        return $this->render('video/index1.html.twig', [
            'videos' => $videoRepository->findAll(),
        ]);
    }
    /**
     * @Route("/new", name="video_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $video = new Video();
        $form = $this->createForm(VideoType::class, $video);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $video->setTitre($form->get('titre')->getdata());
            $video->setSource($form->get('source')->getdata());
            $video->setPrix($form->get('prix')->getdata());
            if($video->getPrix() == 0)
            $video->setPaye(False);
            else
                $video->setPaye(true);
                $video->setNote(0);
            $entityManager->persist($video);
            $entityManager->flush();

            return $this->redirectToRoute('video_index1');
        }

        return $this->render('video/new.html.twig', [
            'video' => $video,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="video_show", methods={"GET"})
     */
    public function show(Video $video): Response
    {
        return $this->render('video/show.html.twig', [
            'video' => $video,
        ]);
    }

    /**
     * @Route("/add/{id}", name="video_add", methods={"GET"})
     */
    public function add(Video $video, Request $request): Response
    {
        $uid = $request->getSession()->get('id');
        $panier = $this->getDoctrine()->getRepository(Panier::class)  ->findOneBy(['id' => $uid]);
        $videos =$this->getDoctrine()->getRepository(Video::class)  ->findBy(['panier' => $uid]);
        $entityManager = $this->getDoctrine()->getManager();
        
        $panier->addIdvid($video);
        if ($video->getPaye() == true)
            $panier->setSomme($panier->getSomme()+$video->getPrix());
            
                
        $entityManager->persist($panier);
        $entityManager->flush();
        return $this->redirectToRoute('video_index');
    }

    /**
     * @Route("/{id}", name="video_show", methods={"GET"})
     */
    public function show1(Video $video): Response
    {
        return $this->render('video/show1.html.twig', [
            'video' => $video,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="video_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Video $video): Response
    {
        $form = $this->createForm(VideoType::class, $video);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('video_index1');
        }

        return $this->render('video/edit.html.twig', [
            'video' => $video,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete/{id}", name="video_delete")
     */
    public function delete(Request $request, Video $video): Response
    {
        
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($video);
            $entityManager->flush();
        

        return $this->redirectToRoute('video_index1');
    }
}
