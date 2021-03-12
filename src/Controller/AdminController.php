<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\ArticleCat;
use App\Form\ArticleType;
use App\Form\CategorieType;
use App\Repository\ArticleCatRepository;
use App\Repository\ArticleRepository;
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
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }


    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @Route("/admin/ajoutArticleAdmin", name="ajoutArticleAdmin")
     */

    function addArticle (ArticleRepository $repository,Request $request){
        $notif=$repository->ValiderArtile();
        $article= new Article();
        $form=$this->createForm(ArticleType::class,$article);
        $form->handleRequest($request);
        if($form->isSubmitted() )
        {
            $em=$this->getDoctrine()->getManager();

            $file = $article->getImage();
            $fileName = md5(uniqid()) . "." . $file->guessExtension();

            try {
                $file->move(
                    $this->getParameter('images_directory'),
                    $fileName
                );
            } catch (FileException $e) {
                $e->getMessage();
            }
            $article->setImage($fileName);
            $article->setIdUser(1);
            $article->setEtatAjout(0);
            $article->setDateAjout(new \DateTime());
            $em->persist($article);
            $em->flush();
            return $this->RedirectToRoute('verifArticle');
        }
        return $this->render('admin/ajoutArticleAdmin.html.twig',[
            'form'=>$form->createView(),
            'notif'=>$notif
        ]);


    }
    
    /**
     * @param ArticleRepository $repository
     * @return Response
     * @Route ("/admin/article",name="verifArticle")
     */
    public function verifArticle(ArticleRepository $repository)
    {
        $notif=$repository->ValiderArtile();

        $session = new Session();
        $session->start();
        $session->set('id',2);
        $session->set('type','admin');
        $idUser=$session->get('id');
        $article=$repository->ValiderArtile();
        return $this->render('admin/index.html.twig', [
            'article' => $article,
            'notif'=>$notif
        ]);
    }


    /**
     * @param $id
     * @param ArticleRepository $repository
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("/news/supp/{id}",name="supp")
     */

    function Delete($id,ArticleRepository $repository){
        $article=$repository->find($id);
        $em=$this->getDoctrine()->getManager();
        $em->remove($article);
        $em->flush();
        return $this->redirectToRoute('verifArticle');
    }


    /**
     * @param ArticleRepository $repository
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @Route("/admin/add/{id}", name="add")
     */

    function update(ArticleRepository $repository,$id){
        $article=$repository->find($id);
            $article->setEtatAjout(1);
            $em=$this->getDoctrine()->getManager();
            $em->flush();
            return $this->RedirectToRoute('verifArticle');

    }


    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @Route ("/admin/categories",name="categories")
     */
    function addCat(Request $request,ArticleRepository $repository,ArticleCatRepository $repositoryCat){
        $notif=$repository->ValiderArtile();
        $date= date("Y");
        $mois= date("m");
        $Maxcat=$repositoryCat->maxCat($date,$mois);
        $articleCat=new ArticleCat();
        $form=$this->createForm(CategorieType::class,$articleCat);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em=$this->getDoctrine()->getManager();
            $file = $articleCat->getImage();
            $fileName = md5(uniqid()) . "." . $file->guessExtension();

            try {
                $file->move(
                    $this->getParameter('images_directory'),
                    $fileName
                );
            } catch (FileException $e) {
                $e->getMessage();
            }
            $em->persist($articleCat);
            $em->flush();
            return $this->RedirectToRoute('categories');
        }
        return $this->render('admin/AjoutCategories.html.twig', [
            'form' =>$form->createView(),
            'notif'=>$notif,
            'maxCat'=>$Maxcat
        ]);
        }




}
