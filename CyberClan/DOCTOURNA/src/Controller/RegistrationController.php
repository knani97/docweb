<?php

namespace App\Controller;

use App\Entity\Cv;
use App\Entity\User;

use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Security\LoginFormAuthenticator;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/register/{role}", name="app_register")
     */
    public function register($role, Request $request, UserPasswordEncoderInterface $passwordEncoder, GuardAuthenticatorHandler $guardHandler, LoginFormAuthenticator $authenticator, \Swift_Mailer $mailer, UserRepository $userRepository): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('password')->getData()
                )
            );
            $file = $user->getImage();
            $fileName = md5(uniqid()) . "." . $file->guessExtension();

            try {
                $file->move(
                    $this->getParameter('clients_directory'),
                    $fileName
                );
            } catch (FileException $e) {
                $e->getMessage();
            }
            $user->setImage($fileName);
            if($role=='worker'){
                $user->setRoles(['ROLE_WORKER']);
                $user->setType(2);
                /*$cv=new Cv();
                $cv->setDiplome($request->get("diplome"));
                $user->setCv($cv);
                $user->getCv()->setSpecialite($request->get('specialite'));
                $user->getCv()->setFile($request->get('cvtest'));
                $user->getCv()->setUser($user);*/
                $user->setActivationToken(md5(uniqid()));
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();
                // do anything else you need here, like send an email
                // on cree le message
                $message =(new \Swift_Message('Activation de Votre Compte'))
                    ->setFrom('mouhebbenabdallah@gmail.com')
                    ->setTo($user->getEmail())
                    ->setBody(
                        $this->renderView(
                            'email/activation.html.twig',['token' => $user->getActivationToken()]

                        ),'text/html');
                $mailer->send($message) ;

                if (!empty($userRepository->findByEmail($user->getEmail()))) {
                    $user = $userRepository->findByEmail($user->getEmail())[0];
                    $request->getSession()->set('id', $user->getId());
                    $request->getSession()->set('email', $request->request->get('email'));
                    $request->getSession()->set('nom', $user->getNom());
                    $request->getSession()->set('prenom', $user->getPrenom());
                    $request->getSession()->set('type', $user->getType());
                    $request->getSession()->set('image', $user->getImage());
                }

                $guardHandler->authenticateUserAndHandleSuccess(
                    $user,
                    $request,
                    $authenticator,
                    'main' // firewall name in security.yaml
                );

                return $this->redirectToRoute('cv_new');
            }
            else{
                $user->setRoles(['ROLE_USER']);
                $user->setType(1);
            }
            // on genere le token d activation
            $user->setActivationToken(md5(uniqid()));
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email
            // on cree le message
            $message =(new \Swift_Message('Activation de Votre Compte'))
                ->setFrom('mouhebbenabdallah@gmail.com')
                ->setTo($user->getEmail())
                ->setBody(
                    $this->renderView(
                        'email/activation.html.twig',['token' => $user->getActivationToken()]

                    ),'text/html');
            $mailer->send($message) ;

            if (!empty($userRepository->findByEmail($user->getEmail()))) {
                $user = $userRepository->findByEmail($user->getEmail())[0];
                $request->getSession()->set('id', $user->getId());
                $request->getSession()->set('email', $request->request->get('email'));
                $request->getSession()->set('nom', $user->getNom());
                $request->getSession()->set('prenom', $user->getPrenom());
                $request->getSession()->set('type', $user->getType());
                $request->getSession()->set('image', $user->getImage());
            }

            return $guardHandler->authenticateUserAndHandleSuccess(
                $user,
                $request,
                $authenticator,
                'main' // firewall name in security.yaml
            );
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * @Route ("/activation/{token}",name="activation")
     */
    public function activation($token, UserRepository $UserRepo) {
    $user= $UserRepo->findBy(['activation_token'=>$token]) ;
    //on verfie si un ultilisateur a ce token
        $user = $UserRepo->findOneBy(['activation_token'=> $token]);
        //si aucun utilisateur n'existe avec ce token
        if(!$user){
            //erreur 404
            throw $this->createNotFoundException('cet user n\'existe pas');
        }
        //on supprime le token
        $user->setActivationToken(null);
        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();
        //we send message flash
        $this->addFlash('message','vous avez bien active votre compte');
        return$this->redirectToRoute('home');
    }
}
