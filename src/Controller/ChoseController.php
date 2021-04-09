<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ChoseController extends AbstractController
{
    /**
     * @Route("/chose", name="chose")
     */
    public function index(): Response
    {
        return $this->render('chose/index.html.twig', [
            'controller_name' => 'ChoseController',
        ]);
    }
}
