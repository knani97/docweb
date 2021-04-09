<?php

namespace App\Controller;

use App\Entity\Pharmacie;
use App\Form\PharmacieType;
use App\Repository\PharmacieRepository;
use Dompdf\Dompdf;
use Dompdf\Options;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

/**
 * @Route("/pharmacie")
 */
class PharmacieController extends AbstractController
{
    /**
     * @Route("/", name="pharmacie_index", methods={"GET"})
     */
    public function index(PharmacieRepository $pharmacieRepository,Request $request,PaginatorInterface $paginator): Response
    {
        $donnees=$pharmacieRepository->findAll();
        $pharmacies=$paginator->paginate(
            $donnees,
            $request->query->getInt('page',1),
            4
        );
        return $this->render('pharmacie/index.html.twig', [
            'pharmacies' => $pharmacies,
        ]);
    }

    /**
     * @Route("/admin", name="pharmacie_index1", methods={"GET"})
     */
    public function index1(PharmacieRepository $pharmacieRepository): Response
    {
        return $this->render('pharmacie/index1.html.twig', [
            'pharmacies' => $pharmacieRepository->findAll(),

        ]);
    }

    /**
     * @Route("/new", name="pharmacie_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $pharmacie = new Pharmacie();
        $form = $this->createForm(PharmacieType::class, $pharmacie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $slugger = new AsciiSlugger();
            if ($form->isSubmitted() && $form->isValid()) {
                /** @var UploadedFile $permisFile */
                $permisFile = $form->get('img_pat')->getData();

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
                            $this->getParameter('img_pat_directory'),
                            $newFilename
                        );
                    } catch (FileException $e) {
                        // ... handle exception if something happens during file upload
                    }

                    // updates the 'brochureFilename' property to store the PDF file name
                    // instead of its contents
                    $pharmacie->setImgPat($newFilename);
                }

                $pharmacie->setNom($form->get('nom')->getdata());
                $pharmacie->setAdr($form->get('adr')->getdata());
                $pharmacie->setGouv($form->get('gouv')->getdata());
                $entityManager->persist($pharmacie);
                $entityManager->flush();

                return $this->redirectToRoute('pharmacie_index1');
            }
        }
        return $this->render('pharmacie/new.html.twig', [
            'pharmacie' => $pharmacie,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/pharmaciesP", name="pharmacies_P", methods={"GET"})
     */
    public function showPharmacieP(): Response
    {
        $options = new Options();
        $options->set('defaultFont', 'Roboto');


        $dompdf = new Dompdf($options);

        $data = array(
            'headline' => 'my headline'
        );

        $pharmacies = $this->getDoctrine()->getRepository(Pharmacie::class)->findAll();

        $html = $this->renderView('pharmacie/pharmaciesP.html.twig', [
            'pharmacies' => $pharmacies,
        ]);


        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream("Pharmaciespdf.pdf", [
            "Attachment" => true
        ]);


    }

    /**
     * @Route("/{id}", name="pharmacie_show", methods={"GET"})
     */
    public function show(Pharmacie $pharmacie): Response
    {
        return $this->render('pharmacie/show.html.twig', [
            'pharmacie' => $pharmacie,
        ]);
    }

    /**
     * @Route("/admin/{id}", name="pharmacie_show1", methods={"GET"})
     */
    public function show1(Pharmacie $pharmacie): Response
    {
        return $this->render('pharmacie/show1.html.twig', [
            'pharmacie' => $pharmacie,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="pharmacie_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Pharmacie $pharmacie): Response
    {
        $form = $this->createForm(PharmacieType::class, $pharmacie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            /** @var UploadedFile $permisFile */
            $slugger = new AsciiSlugger();
            $permisFile = $form->get('img_pat')->getData();

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
                        $this->getParameter('img_pat_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $pharmacie->setImgPat($newFilename);
            }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();
            return $this->redirectToRoute('pharmacie_index1');
        }

        return $this->render('pharmacie/edit.html.twig', [
            'pharmacie' => $pharmacie,
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/note_add/{n}/{id}", name="note_add")
     */
    public function add($n, Pharmacie $video): Response
    {
        $video->setNote($video->getNote() + $n);

        $this->getDoctrine()->getManager()->flush();
        return $this->redirectToRoute("pharmacie_index");
    }

    /**
     * @Route("/admin/{id}", name="pharmacie_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Pharmacie $pharmacie): Response
    {
        if ($this->isCsrfTokenValid('delete' . $pharmacie->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($pharmacie);
            $entityManager->flush();
        }

        return $this->redirectToRoute('pharmacie_index1');
    }
}
