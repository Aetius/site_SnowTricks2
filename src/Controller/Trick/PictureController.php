<?php


namespace App\Controller\Trick;


use App\Entity\Picture;
use App\Services\Picture\EditorService;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PictureController extends AbstractController
{


    /**
     * @Route("/edit/picture/{id}/delete", name="picture_delete", methods={"GET"})
     */
    public function delete(Picture $picture, Request $request, EditorService $editPhoto)
    {
        $trickId = $picture->getTrick()->getId();
        if ($this->isCsrfTokenValid('delete'.$picture->getId(), $request->get('_token'))) {
            $editPhoto->delete($picture);
            $this->addFlash('success', 'deleting_photo');
            return $this->redirectToRoute("trick_edit", ['id' => $trickId]);
        }
    }



}