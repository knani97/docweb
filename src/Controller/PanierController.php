<?php

namespace App\Controller;

use App\Entity\Video;
use App\Entity\Panier;
use App\Entity\Commande;
use App\Form\PanierType;
use Doctrine\ORM\Mapping\Id;
use App\Repository\VideoRepository;
use App\Repository\PanierRepository;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/panier")
 */
class PanierController extends AbstractController
{
    /**
     * @Route("/", name="panier_index", methods={"GET"})
     */
    public function index(Request $request): Response
    {
        $session = $request->getSession();
        $cart = $session->get('panier' , []);
        dump($session->get('panier'));
        $s = 0 ;
        foreach ($cart as $id => $video){
            $s = $s + $video->getPrix();
        }

        return $this->render('panier/index.html.twig', [
            'videos' => $cart,
            'somme' => $s,
            'w' => 0,
            'k' => 0
        ]);
    }

    private function getData(): array
    {
        
        $list = [];
        $commands = $this->getDoctrine()->getRepository(Commande::class)->findAll();

        foreach ($commands as $user) {
            $list[] = [
                $user->getId(),
                $user->getPrix(),
                $user->getDateAchat(),
                
            ];
        }
        return $list;
    }
    /**
     * @Route("/spreadsheet" , name="spread")
     */
    public function spread(){
        $spreadsheet = new Spreadsheet();

        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setTitle('Commande List');

        $sheet->getCell('A1')->setValue('id');
        $sheet->getCell('B1')->setValue('Prix');
        $sheet->getCell('c1')->setValue('Date');
        

        // Increase row cursor after header write
            $sheet->fromArray($this->getData(),null, 'A2', true);
        

        $writer = new Xlsx($spreadsheet);

        $writer->save('Commandes.xlsx');
        return $this->redirectToRoute("video_index");
    }
    /**
     * @Route("/commande/add", name="commande_add")
     */

     public function commandeAdd(Request $request, VideoRepository $videoRepository){
        $session = $request->getSession();
        $cart = $session->get('panier', []);
        $commande = new Commande;
        
        
        
                $commande->setDateAchat(date("D M d, Y G:i"));

        $s = 0;
        dump($commande);
        foreach ($cart as $id => $video){

          
          $commande->addVideo($videoRepository->findOneBy(['id' => $id]));
            $s+=$video->getPrix();
        }

        $commande->setPrix($s);
        $this->getDoctrine()->getManager()->persist($commande);
        $this->getDoctrine()->getManager()->flush();
        
       
        return $this->redirectToRoute('panier_index');
     }
    /**
     * @Route("/add/{id}" , name="panier_add")
     */
    public function add($id, Request $request , VideoRepository $videoRepository){
        $session = $request->getSession();
        $cart = $session->get('panier' , []);
        
            $cart[$id] = $videoRepository->findOneBy(['id' => $id]);
        
        $session->set('panier' , $cart);
        
        $videoRepository->findOneBy(['id' => $id])->setPanier($this->getDoctrine()->getRepository(Panier::class)->findOneBy(['id' => 1]));
        $this->getDoctrine()->getManager()->flush();
        return $this->redirectToRoute('video_index');
    }

    /**
     * @Route("/delete/{id}" , name="panier_delete1")
     */
    public function supp($id, Request $request , VideoRepository $videoRepository){
        $session = $request->getSession();
        $cart = $session->get('panier' , []);
        
        if (!empty($cart[$id]))
            unset($cart[$id]);
        
        
        $session->set('panier' , $cart);
        $videoRepository->findOneBy(['id' => $id])->setPanier(NUll);
        $this->getDoctrine()->getManager()->flush();
        return $this->redirectToRoute('panier_index');
    }
    /**
     * @Route("/deletep/{id}", name="video_deletep", methods={"GET"})
     */
    public function deletp(Video $video): Response
    {
        $panier = new Panier;
        $panier = $this->getDoctrine()->getRepository(Panier::class)->findOneBy(['id' => 1]);
        
        $video->setPanier(NULL);
        $panier->setSomme($panier->getSomme()-$video->getPrix());
        $this->getDoctrine()->getManager()->flush();
        return $this->redirectToRoute("panier_index");
    }

    /**
     * @Route("/new", name="panier_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $panier = new Panier();
        $form = $this->createForm(PanierType::class, $panier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($panier);
            $entityManager->flush();

            return $this->redirectToRoute('panier_index');
        }

        return $this->render('panier/new.html.twig', [
            'panier' => $panier,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="panier_show", methods={"GET"})
     */
    public function show(Panier $panier): Response
    {
        return $this->render('panier/show.html.twig', [
            'panier' => $panier,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="panier_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Panier $panier): Response
    {
        $form = $this->createForm(PanierType::class, $panier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('panier_index');
        }

        return $this->render('panier/edit.html.twig', [
            'panier' => $panier,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="panier_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Panier $panier): Response
    {
        if ($this->isCsrfTokenValid('delete' . $panier->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($panier);
            $entityManager->flush();
        }

        return $this->redirectToRoute('panier_index');
    }
}
