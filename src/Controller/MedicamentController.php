<?php

namespace App\Controller;

use App\Entity\Fiche;
use App\Entity\Medicament;
use App\Form\FicheType;
use App\Form\MedicamentType;
use App\Repository\FicheRepository;
use App\Repository\MedicamentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MedicamentController extends AbstractController
{
    /**
     * @Route("/medicament/contoller", name="medicament")
     */
    public function index(): Response
    {
        return $this->render('medicament/index.html.twig', [
            'controller_name' => 'MedicamentController',
        ]);
    }

    /**
     * @Route("medicament/AfficheMedicament" , name="AfficheMedicament")
     *  * * Method({"GET", "POST"})
     */
    function AfficheMedicament(){
        $repository=$this->getDoctrine()->getRepository(Medicament::class);
        $medicament=$repository->findAll();
        return $this->render("medicament/AfficheMedicament.html.twig",
            ['medicament'=>$medicament]);
    }

    /**
     * @Route("/medicament/AfficherUneFiche/{id}", name="AfficherUneFiche")
     */
    public function AfficherUneFiche(int $id,FicheRepository $rep)
    {
        $em =$this->getDoctrine()->getManager();
        $repository = $this->getDoctrine()->getRepository(Medicament::class);
        $fiche=$rep->find($id);
        $medicament= $repository->find($id);
        $medicament->setFiche($fiche);
        $em->persist($medicament);
        $em->flush();

        return $this->render("medicament/AfficherUneFiche.html.twig",
            ['fiche' => $fiche]);
    }


    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route ("medicament/AjoutMedicament")
     */
    function AjouterMedicament(Request $request){
        $medicament = new medicament();
        $form=$this->createForm(MedicamentType::class,$medicament);

        $form->handleRequest($request);
        //Ajout
        if($form->isSubmitted()&& $form->isValid()){
            $em =$this->getDoctrine()->getManager();
            $em->persist($medicament);
            $em->flush();
            return $this->redirectToRoute("AfficheMedicament");
        }

        return $this->render("medicament/AjoutMedicament.html.twig",
            ['f'=>$form->createView()]
        );
    }
    /**
     * @Route("/medicament/nouveauMedicament", name="nouveauMedicament")
     * Method({"GET", "POST"})
     */
    public function newMedicament(Request $request) {
        $medicament= new medicament();

        $form = $this->createForm(MedicamentType::class,$medicament);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $fiche = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($medicament);
            $entityManager->flush();
            return $this->redirectToRoute("AfficheMedicament");
        }
        return $this->render('medicament/nouveauMedicament.html.twig',['form' => $form->createView()]);

    }

    /**
     * @Route ("/medicament/Update/{id}",name="updateMedicament")
     */
    function Update(MedicamentRepository  $rep,$id,Request $request){
        $medicament=$rep->find($id);
        $form=$this->createForm(MedicamentType::class,$medicament);
        $form->add("Update",SubmitType::class);
        $form->handleRequest($request);
        //Update
        if($form->isSubmitted()&& $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute("AfficheMedicament");
        }
        return $this->render("medicament/ModifierMedicament.html.twig",
            ['f'=>$form->createView()]
        );
    }
    /**
     * @Route ("/DeleteMedicament/{id}",name="deleteMedicament")

     */
    function DeleteM(Medicament $medicament){
        $em= $this->getDoctrine()->getManager();
        $em->remove($medicament);
        $em->flush();

        return $this->redirectToRoute("AfficheMedicament",
            ['medicament'=>$medicament]);
    }

}
