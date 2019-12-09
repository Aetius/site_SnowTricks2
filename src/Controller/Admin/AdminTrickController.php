<?php

namespace App\Controller\Admin;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


class AdminTrickController extends AbstractController{

    /**
     *@Route("/admin/dashboard", name="connexion", methods={"GET|POST"})
     * @IsGranted("ROLE_USER")
     */
    public function connexion()
    {
        return $this->render('/admin/AdminTrick.html.twig');
    }
}