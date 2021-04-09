<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\User;
use App\Form\PostType;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\DateTime;
use App\Entity\Comment;
use App\Form\CommentType;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @Route("/post")
 */
class PostController extends AbstractController
{
    /**
     * @Route("/", name="post_index", methods={"GET"})
     */
    public function index(PaginatorInterface $paginator , Request $request): Response
    {
        $repository=$this->getDoctrine()->getRepository(Post::class);
        $post=$repository->findAll();
        $data = $paginator->paginate(
            $post ,
            $request->query->getInt('page', 1),
        3
        );

        return $this->render('post/index.html.twig', [
            'posts' => $data,
        ]);
    }


    /**
     * @Route("/image", name="image", methods={"POST"},   options={"expose"=true})
     * @param Request $request
     * @return JsonResponse
     */
    public function getImage(Request $request )
    {
        if ($request->isXmlHttpRequest()) {
            $post= new Post();
            $form = $this->createForm(PostType::class, $post);
            $form->handleRequest($request);
            // the file
            $file = $_FILES['file'];
            $file = new UploadedFile($file['tmp_name'], $file['name'], $file['type']);
            $filename = $this->generateUniqueName() . '.' . $file->guessExtension();

            $file->move(
                $this->getTargetDir(),
                $filename
            );

            $post->setAvatar($filename);

            $em = $this->getDoctrine()->getManager();
            $em->persist($post);
            $em->flush();



        }
        return new JsonResponse("this is not a  bla bla bla");
    }

    private function generateUniqueName()
    {
        return md5(uniqid());
    }

    private function getTargetDir()
    {
        return $this->getParameter('uploads_dir');
    }

    /**
     * @Route("/new", name="post_new", methods={"GET","POST"})
     */
    public function new(Request $request, UserRepository $userRepository): Response
    {
        if ($request->getSession()->get('id') == null)
            return $this->render('user/erreur-login.html.twig', []);

        $uid = $request->getSession()->get('id');
        $user = $userRepository->find($uid);

        $post = new Post();
        $post->setUser($user);
        $post->setCreatedAt(new \DateTime());
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);
        

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($post);
            $entityManager->flush();

            return $this->redirectToRoute('post_index');
        }

        return $this->render('post/new.html.twig', [
            'post' => $post,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="post_show", methods={"GET"})
     */
    public function show(Post $post): Response
    {
        $comment = new Comment();
        $comment->setCreatedAt(new \DateTime());
        $form = $this->createForm(CommentType::class, $comment);
        
        return $this->render('post/show.html.twig', [
            'post' => $post,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="post_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Post $post): Response
    {
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('post_index');
        }

        return $this->render('post/edit.html.twig', [
            'post' => $post,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="post_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Post $post): Response
    {
        if ($this->isCsrfTokenValid('delete'.$post->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($post);
            $entityManager->flush();
        }

        return $this->redirectToRoute('post_index');
    }

    /**
     * @Route("/{id}", name="post_comment", methods={"POST"})
     */
    public function addComment(Request $request, UserRepository $userRepository, Post $post): Response
    {
        if ($request->getSession()->get('id') == null)
            return $this->render('user/erreur-login.html.twig', []);

        $uid = $request->getSession()->get('id');
        $user = $userRepository->find($uid);

        $comment = new Comment();
        $comment->setCreatedAt(new \DateTime());
        $comment->setPost($post);
        $comment->setUser($user);
        $form = $this->createForm(commentType::class, $comment);
        $form->handleRequest($request);
        

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($comment);
            $entityManager->flush();
        }

        return $this->redirectToRoute('post_show', ['id' => $post->getId()]);
    }
}
