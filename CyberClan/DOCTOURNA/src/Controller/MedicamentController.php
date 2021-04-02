<?php

namespace App\Controller;

use App\Entity\Fiche;
use App\Entity\Medicament;
use App\Form\MedicamentType;
use App\Repository\ArticleRepository;
use App\Repository\MedicamentRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

/**
 * @Route("/medicament")
 */
class MedicamentController extends AbstractController
{
    /**
     * @Route("/", name="medicament_index", methods={"GET"})
     */
    public function index(MedicamentRepository $medicamentRepository): Response
    {
        return $this->render('medicament/index.html.twig', [
            'medicaments' => $medicamentRepository->findAll(),
        ]);
    }

    /**
     * @Route("/admin", name="medicament_index1", methods={"GET"})
     */
    public function index1(MedicamentRepository $medicamentRepository, ArticleRepository $articleRepository): Response
    {
        $notif=$articleRepository->ValiderArtile();

        return $this->render('medicament/index1.html.twig', [
            'medicaments' => $medicamentRepository->findAll(),
            'notif' => $notif,
        ]);
    }

    /**
     * @Route("/admin/new", name="medicament_new", methods={"GET","POST"})
     */
    public function new(Request $request, ArticleRepository $articleRepository): Response
    {
        $notif=$articleRepository->ValiderArtile();
        $medicament = new Medicament();
        $form = $this->createForm(MedicamentType::class, $medicament);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $medicament->setNom($form->get('nom')->getdata());
            $medicament->setFournisseur($form->get('fournisseur')->getdata());
            $medicament->setPrixAchat($form->get('prix_achat')->getdata());
            $medicament->setPoid($form->get('poid')->getdata());
            $medicament->setFicheExist(false);
            /** @var UploadedFile $permisFile */
            $slugger = new AsciiSlugger();
            $permisFile = $form->get('img')->getData();

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($permisFile) {
                $originalFilename = pathinfo($permisFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $permisFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $permisFile->move(
                        $this->getParameter('img_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $medicament->setImg($newFilename);
            }
            $entityManager->persist($medicament);
            $entityManager->flush();

            return $this->redirectToRoute('medicament_index1');
        }

        return $this->render('medicament/new.html.twig', [
            'medicament' => $medicament,
            'form' => $form->createView(),
            'notif' => $notif,
        ]);
    }

    /**
     * @Route("/{id}", name="medicament_show", methods={"GET"})
     */
    public function show(Medicament $medicament): Response
    {
        return $this->render('medicament/show.html.twig', [
            'medicament' => $medicament,
        ]);
    }

    /**
     * @Route("/{id}", name="medicament_show1", methods={"GET"})
     */
    public function show1(Medicament $medicament): Response
    {
        return $this->render('medicament/show1.html.twig', [
            'medicament' => $medicament,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="medicament_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Medicament $medicament): Response
    {
        $form = $this->createForm(MedicamentType::class, $medicament);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $permisFile */
            $slugger = new AsciiSlugger();
            $permisFile = $form->get('img')->getData();

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($permisFile) {
                $originalFilename = pathinfo($permisFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $permisFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $permisFile->move(
                        $this->getParameter('img_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $medicament->setImg($newFilename);
            }
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('medicament_index1');
        }

        return $this->render('medicament/edit.html.twig', [
            'medicament' => $medicament,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="medicament_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Medicament $medicament): Response
    {
        if ($this->isCsrfTokenValid('delete' . $medicament->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $fiche= $this->getDoctrine()->getRepository(Fiche::class)->findOneBy(['id_med' => $medicament]);

            $entityManager->remove($fiche);
            $entityManager->flush();
        }

        return $this->redirectToRoute('medicament_index1');
    }
}
