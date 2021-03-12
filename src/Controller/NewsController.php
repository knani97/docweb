<?php
namespace App\Controller;
use App\Entity\Article;
use App\Entity\Reagit;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use App\Repository\CommentairesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class NewsController extends AbstractController
{
    /**
     * @Route("", name="AjoutDisLike")
     */
    public function like($id,$likeId)
    {
        $reagit=new Reagit();
        if($id!="")
        {
        if($id=0){
            $em=$this->getDoctrine()->getManager();
            $reagit->setTypeReact(0);
            $em->persist($reagit);
            $em->flush();


        }
        else
        {
            $em=$this->getDoctrine()->getManager();
            $reagit->setTypeReact(1);
            $em->persist($reagit);
            $em->flush();

        }
        }
        return $this->render('news/details-article.html.twig', [
            'articleDetails' => $reagit,
            'autreArticle'=>$reagit
        ]);
    }

    public function session(SessionInterface $session)
    {
        $session->set('id',1);
        $session->get('id');
        $session->set('prenom',"hammami");
    }

    /**
     * @Route("/news", name="news")
     */

    public function index(ArticleRepository $repository)
    {

        $article=$repository->articlesearch();
        $id=1;
        $recom=$repository->recomm($id);
        return $this->render('news/index.html.twig', [
            'article' => $article,
            'recom'=>$recom
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
        $article=$repository->mesArticle($idUser);
        return $this->render('news/mesArticle.html.twig', [
            'article' => $article
        ]);
    }

  /**
     * @Route("/news/listeArticles", name="listeArticles")
     */
    public function listeArticles(ArticleRepository $repository)
    {
        $article=$repository->articlesearch();
        return $this->render('news/listeArticles.html.twig', [
            'article' => $article
        ]);
    }

    /**
     * @Route("/news/detailsArt/{id}", name="detailsArt")
     */
    public function DetailsArticles(ArticleRepository $repository,$id,CommentairesRepository $repositoryCom)
    {

        $article=$repository->articlesearchById($id);
        $autreArticle=$repository->AutrearticlesearchById($id);
        $rec=$repository->RecommArticle($id);
        $idUser=1;
        return $this->render('news/details-article.html.twig', [
            'articleDetails' => $article,
            'autreArticle'=>$autreArticle,
            'RecommArticle'=>$rec,
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
            $em->flush();
            return $this->RedirectToRoute('detailsArt',['id' => $article->getId()]);
        }
        return $this->render('news/modArticle.html.twig',[
            'form' =>$form->createView()
        ]);
    }


}
