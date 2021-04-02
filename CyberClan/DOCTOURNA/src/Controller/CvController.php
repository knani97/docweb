<?php

namespace App\Controller;

use App\Entity\Cv;
use App\Entity\User;
use App\Form\CvType;
use App\Repository\CvRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/cv")
 */
class CvController extends AbstractController
{
    /**
     * @Route("/", name="cv_index", methods={"GET"})
     */
    public function index(CvRepository $cvRepository): Response
    {
        return $this->render('cv/index.html.twig', [
            'cvs' => $cvRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="cv_new", methods={"GET","POST"})
     */
    public function new(Request $request, UserRepository $userRepository): Response
    {
        if ($request->getSession()->get('id') == null)
            return $this->render('user/erreur-login.html.twig', []);

        $uid = $request->getSession()->get('id');
        $user = $userRepository->find($uid);

        $cv = new Cv();
        $form = $this->createForm(CvType::class, $cv);
        $form->handleRequest($request);
        $cv->setUser($user);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($cv);
            $entityManager->flush();

            return $this->redirectToRoute('home');
        }

        return $this->render('cv/new.html.twig', [
            'cv' => $cv,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="cv_show", methods={"GET"})
     */
    public function show(Cv $cv): Response
    {
        return $this->render('cv/show.html.twig', [
            'cv' => $cv,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="cv_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Cv $cv): Response
    {
        $form = $this->createForm(CvType::class, $cv);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('cv_index');
        }

        return $this->render('cv/edit.html.twig', [
            'cv' => $cv,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="cv_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Cv $cv): Response
    {
        if ($this->isCsrfTokenValid('delete'.$cv->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($cv);
            $entityManager->flush();
        }

        return $this->redirectToRoute('cv_index');
    }
}
