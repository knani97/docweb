<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\ArticleCat;
use App\Form\ArticleType;
use App\Form\CategorieType;
use App\Repository\ArticleCatRepository;
use App\Repository\ArticleRepository;
use App\Repository\CalendrierRepository;
use App\Repository\RDVRepository;
use App\Repository\UserRepository;
use Knp\Component\Pager\PaginatorInterface;
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
    public function index(CalendrierRepository $repository, ArticleRepository $articleRepository): Response
    {
        $notif=$articleRepository->ValiderArtile();

        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
            'notif'=>$notif
        ]);
    }

    /**
     * @Route("/admin/calendrier", name="adminC")
     */
    public function calendrier(CalendrierRepository $repository, Request $request, PaginatorInterface $paginator, ArticleRepository $articleRepository): Response
    {
        $notif=$articleRepository->ValiderArtile();
        $data = $repository->findAll();

        $tab = $paginator->paginate(
            $data,
            $request->query->getInt('page', 1),
            4
        );

        return $this->render('admin/calendriers.html.twig', [
            'controller_name' => 'AdminController',
            'tab' => $tab,
            'notif'=>$notif
        ]);
    }

    /**
     * @Route("/admin/calendrierUser/{nom}", name="adminCNom")
     */
    public function calendrierUser($nom, CalendrierRepository $repository, Request $request, PaginatorInterface $paginator, ArticleRepository $articleRepository): Response
    {
        $notif=$articleRepository->ValiderArtile();
        $data = $repository->findLikeUser($nom);

        $tab = $paginator->paginate(
            $data,
            $request->query->getInt('page', 1),
            4
        );

        return $this->render('admin/calendriers.html.twig', [
            'controller_name' => 'AdminController',
            'tab' => $tab,
            'notif'=>$notif
        ]);
    }

    /**
     * @Route("/admin/rdv", name="adminRDV")
     */
    public function rdv(RDVRepository $repository, Request $request, PaginatorInterface $paginator, ArticleRepository $articleRepository): Response
    {
        $notif=$articleRepository->ValiderArtile();
        $data = $repository->findAll();

        $tab = $paginator->paginate(
            $data,
            $request->query->getInt('page', 1),
            4
        );

        return $this->render('admin/rdvs.html.twig', [
            'controller_name' => 'AdminController',
            'tab' => $tab,
            'notif'=>$notif
        ]);
    }

    /**
     * @param ArticleRepository $repository
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @Route("/admin/ListArticleAdmin", name="ListArticleAdmin")
     */

    function listArticle (ArticleRepository $repository,Request $request,PaginatorInterface $paginator){
        $notif=$repository->ValiderArtile();
        $article= new Article();
        $article=$repository->articlesearch();
        $ragitLike=$repository->reagitLike();
        $reagitDislike=$repository->reagitDislike();
        $article = $paginator->paginate(
            $article, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            6/*limit per page*/);
        return $this->render('admin/ListArticleAdmin.html.twig',[
            'article'=>$article,
            'notif'=>$notif,
            'reagitLike'=>$ragitLike,
            'reagitDislike'=>$reagitDislike
        ]);


    }

    /**
     * @param ArticleRepository $repository
     * @return Response
     * @Route ("/admin/article",name="verifArticle")
     */
    public function verifArticle(ArticleRepository $repository, Request $request)
    {
        $notif=$repository->ValiderArtile();

        $idUser=$request->getSession()->get('id');
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
     * @Route("/news/supp/{id}",name="suppArt")
     */

    function Delete($id,ArticleRepository $repository){
        $article=$repository->find($id);
        $em=$this->getDoctrine()->getManager();
        $em->remove($article);
        $em->flush();
        return $this->redirectToRoute('verifArticle');
    }


    /**
     * @param $id
     * @param ArticleRepository $repository
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("/news/suppListeArticle/{id}",name="suppListeArticle")
     */

    function DeleteListeArticle($id,ArticleRepository $repository){
        $article=$repository->find($id);
        $em=$this->getDoctrine()->getManager();
        $em->remove($article);
        $em->flush();
        return $this->redirectToRoute('ListArticleAdmin');
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
     * @param ArticleRepository $repository
     * @param ArticleCatRepository $repositoryCat
     * @param PaginatorInterface $paginator
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @Route ("/admin/categories",name="categories")
     */
    function addCat(Request $request,ArticleRepository $repository,ArticleCatRepository $repositoryCat,PaginatorInterface $paginator){
        $notif=$repository->ValiderArtile();
        $date= date("Y");
        $mois= date("m");
        $cat=$repositoryCat->CountCat($date,$mois);
        $AllCat=$repositoryCat->findAll();
        $Maxcat=$repositoryCat->maxCat($date,$mois);

        $AllCat = $paginator->paginate(
            $AllCat, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            5/*limit per page*/);
        $articleCat=new ArticleCat();
        $form=$this->createForm(CategorieType::class,$articleCat);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){

            $em=$this->getDoctrine()->getManager();
            $file = $articleCat->getImage();
            $fileName = md5(uniqid()) . "." . $file->guessExtension();

            try {
                $file->move(
                    $this->getParameter('images_directory_categorie'),
                    $fileName
                );
            } catch (FileException $e) {
                $e->getMessage();
            }
            $articleCat->setImage($fileName);
            $em->persist($articleCat);
            $em->flush();
            return $this->RedirectToRoute('categories');
        }



        return $this->render('admin/AjoutCategories.html.twig', [
                'form' =>$form->createView(),
                'notif'=>$notif,
                'maxCat'=>$Maxcat,
                'cat'=>$cat,
                'AllCat'=>$AllCat
            ]
        );
    }



    /**
     * @param $id
     * @param ArticleCatRepository $catRepository
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("/admin/deleteCat/{cat}", name="deleteCat")
     */
    function SuppCat($cat,ArticleCatRepository $catRepository){
        $categorie=$catRepository->findOneBy(['id'=>$cat]);
        $em=$this->getDoctrine()->getManager();
        $em->remove($categorie);
        $em->flush();
        return $this->redirectToRoute('categories');
    }

    /**
     * @param $idCat
     * @param ArticleRepository $repository
     * @param ArticleCatRepository $repositoryCat
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @Route("/admin/modCat/{idCat}", name="modCat")
     */
    function UpdateCat($idCat,ArticleRepository $repository,ArticleCatRepository $repositoryCat,Request $request,PaginatorInterface $paginator){
        $notif=$repository->ValiderArtile();
        $date= date("Y");
        $mois= date("m");
        $cat=$repositoryCat->CountCat($date,$mois);
        $AllCat=$repositoryCat->findAll();
        $Maxcat=$repositoryCat->maxCat($date,$mois);
        $articleCat=new ArticleCat();
        $articleCat=$repositoryCat->find($idCat);
        $AllCat = $paginator->paginate(
            $AllCat, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            5/*limit per page*/);
        $form=$this->createForm(CategorieType::class,$articleCat);
        $form->handleRequest($request);
        $img=$articleCat->getImage();
        if($form->isSubmitted() && $form->isValid()) {


            $em = $this->getDoctrine()->getManager();
            $file = $articleCat->getImage();
            if ($file != "")
            {
                $fileName = md5(uniqid()) . "." . $file->guessExtension();

                try {
                    $file->move(
                        $this->getParameter('images_directory_categorie'),
                        $fileName
                    );
                } catch (FileException $e) {
                    $e->getMessage();
                }
            }
            else
            {
                $fileName=$img;
            }
            $articleCat->setImage($fileName);
            $em->flush();
            return $this->RedirectToRoute('categories');
        }



        return $this->render('admin/UpdateCat.html.twig', [
                'form' =>$form->createView(),
                'notif'=>$notif,
                'maxCat'=>$Maxcat,
                'cat'=>$cat,
                'AllCat'=>$AllCat,
            ]
        );
    }

    /**
     * @Route("/admin/users", name="users")
     */
    public function users(ArticleRepository $repository, UserRepository $userRepository): Response
    {
        $notif=$repository->ValiderArtile();
        $users=$userRepository->createQueryBuilder('u')->select('u')->where('u.cv is NULL')
            ->getQuery()->getResult();
        return $this->render('admin/users.html.twig', [
            'users' => $users,
            'notif' => $notif,
        ]);
    }
    /**
     * @Route("/admin/workers", name="workers")
     */
    public function workers(ArticleRepository $repository, UserRepository $userRepository): Response
    {
        $notif=$repository->ValiderArtile();
        $users=$userRepository->createQueryBuilder('u')->select('u')->where('u.cv is NOT NULL')
            ->getQuery()->getResult();
        return $this->render('admin/workers.html.twig', [
            'users' => $users,
            'notif' => $notif,
        ]);
    }
}