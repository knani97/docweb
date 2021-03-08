<?php
namespace App\Controller;
use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class NewsController extends AbstractController
{
    /**
     * @Route("/news", name="news")
     */

    public function index(): Response
    {
        return $this->render('news/index.html.twig', [
            'controller_name' => 'NewsController',
        ]);
    }
    /**
     * @param Request $request
     * @return Response
     * @Route ("news/ajoutArticle", name="ajoutArticle")
     */
    function add(Request $request){
        $article=new Article();
        $form=$this->createForm(ArticleType::class,$article);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $article->setIdUser(1);
            $article->setEtatAjout(0);
            $article->setDateAjout(new \DateTime());
            $em=$this->getDoctrine()->getManager();
            $em->persist($article);
            $em->flush();
            return $this->RedirectToRoute('mes-articles');
        }
        return $this->render('news/ajoutArticle.html.twig', [
            'form' =>$form->createView()
        ]);
    }

    /**
     * @Route("/news/mes-articles", name="mes-articles")
     */
    public function mesArticles(ArticleRepository $repository)
    {
        $session = new Session();
        $session->start();
        $session->set('id',1);
        $session->set('type','patient');
        $idUser=$session->get('id');
        $article=$repository->findBy([
            'idUser'=>$idUser
        ],[
            'dateAjout'=>"ASC"
            ],10,0
        );
        return $this->render('news/mesArticle.html.twig', [
            'article' => $article
        ]);
    }

  /**
     * @Route("/news/listeArticles", name="listeArticles")
     */
    public function listeArticles(ArticleRepository $repository)
    {
        $article=$repository->findAll();
        return $this->render('news/listeArticles.html.twig', [
            'article' => $article
        ]);
    }

    /**
     * @Route("/news/detailsArt/{id}", name="detailsArt")
     */
    public function DetailsArticles(ArticleRepository $repository,$id)
    {
        $session = new Session();
        $session->start();
        $session->set('id',1);
        $session->set('prenom','hammami');
        $idUser=$session->get('id');
        $article=$repository->find($id);
        return $this->render('news/details-article.html.twig', [
            'details' => $article
        ]);
    }
 /**
     * @Route("/news/detailsArt/{id}", name="detailsArtAutre")
     */
    public function DetailsArticlesAutre(ArticleRepository $repository,$id)
    {
        $session = new Session();
        $session->start();
        $session->set('id',1);
        $session->set('prenom','hammami');
        $idUser=$session->get('id');
        $article=$repository->AutreArticle($id,$idUser);
        return $this->render('news/details-article.html.twig', ['
            autreA' => $article
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
        return $this->redirectToRoute('mes-articles');
    }

    /**
     * @param ArticleRepository $repository
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @Route("/news/modArt/{id}", name="modArt")
     */

    function update(ArticleRepository $repository,$id,Request $request){
        $article=$repository->find($id);
        $form=$this->createForm(ArticleType::class,$article);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em=$this->getDoctrine()->getManager();
            $em->flush();
            return $this->RedirectToRoute('detailsArt',['id' => $article->getId()]);
        }
        return $this->render('news/modArticle.html.twig',[
            'form' =>$form->createView()
        ]);
    }


}
