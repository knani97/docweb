<?php
namespace App\Controller;
use App\Entity\Article;
use App\Entity\Commentaires;
use App\Entity\Reagit;
use App\Entity\Users;
use App\Form\ArticleType;
use App\Form\CommentaireType;
use App\Repository\ArticleRepository;
use App\Repository\CommentairesRepository;
use App\Repository\ReagitRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\User;

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

    public function index(ArticleRepository $repository,PaginatorInterface  $paginator,Request $request)
    {

        $session = new Session();
        $session->start();
        $idUser=$session->get('id');
        $notifLike=$repository->notifLike($idUser);
        $notifAjout=$repository->notif($idUser);
        $article=$repository->articlesearch();
        $articleTop=$repository->articlesearch();
        $ragitLike=$repository->reagitLike();
        $topLike=$repository->topLike();
        $reagitDislike=$repository->reagitDislike();

        $recom=$repository->recomm($idUser);
        $article = $paginator->paginate(
            $article, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            4/*limit per page*/);
        return $this->render('news/index.html.twig', [
            'article' => $article,
            'articleTop' => $articleTop,
            'recom'=>$recom,
            'reagitLike'=>$ragitLike,
            'reagitDislike'=>$reagitDislike,
            'topLike'=>$topLike,
            'notifLike'=>$notifLike,
            'notifAjout'=>$notifAjout
        ]);
    }

    /**
     * @param Request $request
     * @return Response
     * @Route ("news/ajoutArticle", name="ajoutArticle")
     */
    function add(Request $request,ArticleRepository $repository){
        $session = new Session();
        $session->start();
        $idUser=$session->get('id');
        $notifAjout=$repository->notif($idUser);
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
            'form' =>$form->createView(),
            'notifAjout'=>$notifAjout
        ]);
    }

    /**
     * @Route("/news/mes-articles", name="mes-articles")
     * @param ArticleRepository $repository
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @return Response
     */
    public function mesArticles(ArticleRepository $repository,Request $request,PaginatorInterface $paginator)
    {
        $session = new Session();
        $session->start();
        $session->set('id',1);
        $session->set('type','patient');
        $idUser=$session->get('id');
        $notifAjout=$repository->notif($idUser);

        $article=$repository->mesArticle($idUser);
        $ragitLike=$repository->reagitLike();
        $reagitDislike=$repository->reagitDislike();
        $article = $paginator->paginate(
            $article, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            6/*limit per page*/);
        return $this->render('news/mesArticle.html.twig', [
            'article' => $article,'reagitLike'=>$ragitLike,'reagitDislike'=>$reagitDislike,
            'notifAjout'=>$notifAjout
        ]);
    }

  /**
     * @Route("/news/listeArticles", name="listeArticles")
     */
    public function listeArticles(ArticleRepository $repository,Request $request,PaginatorInterface $paginator)
    {
        $session = new Session();
        $session->start();
        $idUser=$session->get('id');
        $notifAjout=$repository->notif($idUser);

        $article=$repository->articlesearch();
        $ragitLike=$repository->reagitLike();
        $reagitDislike=$repository->reagitDislike();

        $article = $paginator->paginate(
            $article, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            6/*limit per page*/);
        return $this->render('news/listeArticles.html.twig', [
            'article' => $article,
            'reagitLike'=>$ragitLike,
            'reagitDislike'=>$reagitDislike,
            'notifAjout'=>$notifAjout
        ]);
    }


  /**
     * @Route("/news/ArticlePrefere", name="ArticlePrefere")
     */
    public function ArticlesPrefere(ArticleRepository $repository,Request $request,PaginatorInterface $paginator)
    {
        $session = new Session();
        $session->start();
        $idUser=$session->get('id');
        $notifAjout=$repository->notif($idUser);
        $ragitLike=$repository->reagitLike();
        $reagitDislike=$repository->reagitDislike();
        $idUser=1;/* id user */
        $article=$repository->articlePrefere($idUser);
        $article = $paginator->paginate(
            $article, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            6/*limit per page*/);
        return $this->render('news/articlePrefere.html.twig', [
            'article' => $article,'reagitLike'=>$ragitLike,'reagitDislike'=>$reagitDislike,
           'notifAjout'=>$notifAjout
        ]);
    }

    /**
     * @Route("/news/detailsArt/{id}", name="detailsArt")
     * @param Request $request
     * @param ArticleRepository $repository
     * @param $id
     * @param CommentairesRepository $repositoryCom
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function DetailsArticles(Request $request,ArticleRepository $repository,$id)
    {
        $session = new Session();
        $session->start();
        $idUser=$session->get('id');
        $notifAjout=$repository->notif($idUser);
        $ragitLike=$repository->reagitLike();
        $reagitDislike=$repository->reagitDislike();
        $article=$repository->articlesearchById($id);
        $autreArticle=$repository->AutrearticlesearchById($id);
        $rec=$repository->RecommArticle($id);
        $idUser=1;
        return $this->render('news/details-article.html.twig', [
            'articleDetails' => $article,
            'autreArticle'=>$autreArticle,
            'RecommArticle'=>$rec,
            'reagitLike'=>$ragitLike,'reagitDislike'=>$reagitDislike,
                'notifAjout'=>$notifAjout
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

    /**
     * @param $id
     * @param ArticleRepository $repository
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("/news/DelR/{id}",name="DelR")
     */

    function DeleteReagit($id,ReagitRepository $repository){

        $idUser=1;
        $reagit = $repository->findOneBy([
            'idArt' => $id,
            'idUser' => $idUser,
        ]);
        $em=$this->getDoctrine()->getManager();
        $em->remove($reagit);
        $em->flush();
        return $this->redirectToRoute('mes-articles');
    }

    /**
     * @param Article $id
     * @param $etat
     * @param ReagitRepository $repository
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("/news/TestLike/{id}/etat={etat}",name="TestLike" , requirements={"id":"\d+"})
     */

    function TestLike(Article $id,$etat,ReagitRepository $repository,Request $request){


       $user =new Users();
       $idUser=1;
       $etatAjout=false;
       $reagit = new Reagit();
        $em=$this->getDoctrine()->getManager();

        if($etat==0){
           $reagit = $repository->findOneBy([
               'idArt' => $id,
               'idUser' => $idUser,
               'typeReact'=>1
           ]);
           if(!$reagit){
               $reagit = new Reagit();
               $reagit->setTypeReact($etat);
               $reagit->setIdArt($id);
               $em->persist($reagit);

               $em->flush();
               $etatAjout=true;
           }
           else{
               $reagit->setIdArt($id);
               $reagit->setTypeReact($etat);
               $em->persist($reagit);
               $em->flush();
               $etatAjout=true;

           }
       }
       elseif ($etat==1){
           $reagit = $repository->findOneBy([
               'idArt' => $id,
               'idUser' => $idUser,
               'typeReact'=>0
           ]);
           if(!$reagit){
               $reagit = new Reagit();

               $reagit->setTypeReact($etat);
               $reagit->setIdArt($id);
               $em->flush();
               $etatAjout=true;

           }
           else{
               $reagit->setIdArt($id);
               $reagit->setTypeReact($etat);
               $em->persist($reagit);
               $em->flush();
               $etatAjout=true;

           }
       }
        if($etat!=2){
            $reagitInsert = $repository->findOneBy([
                'idArt' => $id,
                'idUser' => $idUser,
                'typeReact'=> 0 and 1
            ]);
            if($etatAjout==false) {
                $reagit->setIdArt($id);
                $reagit->setIdUser(1);/** ID avec TOKEN**/
                $reagit->setTypeReact($etat);
                $em->persist($reagit);
                $em->flush();
            }
        }



        return $this->redirectToRoute('ArticlePrefere');
    }

  /**
     * @param $id
     * @param ArticleRepository $repository
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("/news/TestDisLike/{id}",name="TestDisLike")
     */

    function TestDisLike($id,ReagitRepository $repository){

        $idUser=1;
        $reagit = $repository->findOneBy([
            'idArt' => $id,
            'idUser' => $idUser,
        ]);
        if($reagit!=0)
        $em=$this->getDoctrine()->getManager();
        $em->remove($reagit);
        $em->flush();
        return $this->redirectToRoute('ArticlePrefere');
    }


}
