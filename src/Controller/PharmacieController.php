<?php

namespace App\Controller;

use App\Entity\Pharmacie;
use App\Form\PharmacieType;
use App\Repository\PharmacieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;





class PharmacieController extends AbstractController
{
    /**
     * @Route("/pharmacie", name="pharmacie")
     */
    public function index(): Response
    {
        return $this->render('pharmacie/index.html.twig', [
            'controller_name' => 'PharmacieController',
        ]);
    }


    /**
     * @Route("pharmacie/AffichePharmacie" , name="AffichePharmacie")
     * * Method({"GET", "POST"})
     */
    function AffichePharmacie(){
        $repository=$this->getDoctrine()->getRepository(Pharmacie::class);
        $pharmacie=$repository->findAll();
        return $this->render("pharmacie/AffichePharmacie.html.twig",
            ['pharmacie'=>$pharmacie]);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route ("pharmacie/AjoutPharmacie")
     */
    function AjouterPharmacie(Request $request){
        $pharmacie = new Pharmacie();
        $form=$this->createForm(PharmacieType::class,$pharmacie);

        $form->handleRequest($request);
        //Ajout
        if($form->isSubmitted()&& $form->isValid()){
            $em =$this->getDoctrine()->getManager();
            $em->persist($pharmacie);
            $em->flush();
            return $this->redirectToRoute("AffichePharmacie");
        }

        return $this->render("pharmacie/AjouterPharmacie.html.twig",
            ['f'=>$form->createView()]
        );
    }

    /**
     * @Route ("/pharmacie/Update/{id}",name="updatePharmacie")
     */
    function Update(PharmacieRepository $rep,$id,Request $request){
        $pharmacie=$rep->find($id);
        $form=$this->createForm(PharmacieType::class,$pharmacie);
        $form->add("Update",SubmitType::class);
        $form->handleRequest($request);
        //Update
        if($form->isSubmitted()&& $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute("AffichePharmacie");
        }
        return $this->render("pharmacie/ModifierPharmacie.html.twig",
            ['f'=>$form->createView()]
        );
    }


    /**
     * @Route ("/DeletePharmacie/{id}",name="deletePharmacie")

     */
    function DeleteP(Pharmacie $pharmacie){
        $em= $this->getDoctrine()->getManager();
        $em->remove($pharmacie);
        $em->flush();

        return $this->redirectToRoute("AffichePharmacie",
            ['pharmacie'=>$pharmacie]);
    }
}
