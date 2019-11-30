<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class AppController extends AbstractController{

    /**
     * @var Environment
     */
    private $twig;

    public function __construct(Environment $twig)
    {
        $this -> twig = $twig;
    }


    /**
     * @return string
     * @Route("/", name="home", methods={"GET"})
     */
    public function index()
    {
        return new Response($this->twig->render("page/home.html.twig"));
    }

}