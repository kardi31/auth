<?php
declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomepageController extends AbstractController
{
    /**
     * @Route("/", name="app_homepage", methods={"GET"})
     *
     * @return Response
     */
    public function index(): Response
    {
       return $this->render('homepage/index.html.twig');
    }
}
