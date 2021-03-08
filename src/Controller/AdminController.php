<?php

namespace App\Controller;

use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
     * @param ArticleRepository $repository
     * @return Response
     * @Route ("/admin/article",name="verifArticle")
     */
    public function verifArticle(ArticleRepository $repository)
    {
        $session = new Session();
        $session->start();
        $session->set('id',2);
        $session->set('type','admin');
        $idUser=$session->get('id');
        $article=$repository->findBy([
            'etatAjout'=>0
        ],[
            'dateAjout'=>"ASC"
        ],10,0
        );
        return $this->render('admin/index.html.twig', [
            'article' => $article
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
        return $this->redirectToRoute('admin');
    }


    /**
     * @param ArticleRepository $repository
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @Route("/news/add/{id}", name="add")
     */

    function update(ArticleRepository $repository,$id){
        $article=$repository->find($id);
            $article->setEtatAjout(1);
            $em=$this->getDoctrine()->getManager();
            $em->flush();
            return $this->RedirectToRoute('detailsArt',['id' => $article->getId()]);

    }
}
