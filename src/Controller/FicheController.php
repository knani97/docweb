<?php

namespace App\Controller;

use App\Entity\Fiche;
use App\Entity\Medicament;
use App\Form\FicheType;
use App\Repository\FicheRepository;
use App\Repository\MedicamentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FicheController extends AbstractController
{
    /**
     * @Route("/fiche/contoller", name="fiche")
     */
    public function index(): Response
    {
        return $this->render('fiche/index.html.twig', [
            'controller_name' => 'FicheController',
        ]);
    }


    /**
     * @Route("/medicament/AfficherUneFiche/{id}", name="AfficherUneFiche")
     */
    public function AfficherUneFiche(int $id)
    { $repository= $this->getDoctrine()->getRepository(Fiche::class);

        $fiche =$repository->find($id);

        return $this->render("medicament/AfficherUneFiche.html.twig",
            ['fiche' => $fiche]);
    }

    /**
     * @Route("fiche/AfficheFiches" , name="AfficheFiches")
     */
    function AfficheFiches(){
        $repository=$this->getDoctrine()->getRepository(Fiche::class);
        $fiche=$repository->findAll();
        return $this->render("fiche/AfficheFiches.html.twig",
            ['fiche'=>$fiche]);
    }

    /**
     * @Route("/fiche/nouvelleFiche", name="nouvelleFiche")
     * Method({"GET", "POST"})
     */
    public function newFiche(Request $request) {
        $fiche= new Fiche();

        $form = $this->createForm(FicheType::class,$fiche);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $medicament = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($fiche);
            $entityManager->flush();
            return $this->redirectToRoute("AfficheFiches");
        }
        return $this->render('fiche/nouvelleFiche.html.twig',['form' => $form->createView()]);
    }



}
