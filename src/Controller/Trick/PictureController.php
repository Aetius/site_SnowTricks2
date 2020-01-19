<?php


namespace App\Controller\Trick;


use App\Entity\Picture;
use App\Entity\Trick;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PictureController extends AbstractController
{
    /**
     *@Route("/picture/{id}/delete", name="picture_delete", methods={"GET"})
     */
    public function delete(Picture $picture, Request $request)
    {
        dd('ici');
        if ($this -> isCsrfTokenValid( 'delete' . $picture -> getId(), $request -> get( '_token' ) )) {
            $em = $this -> getDoctrine() -> getManager();
            $em -> remove( $picture );
            $em -> flush();
            return $this->redirectToRoute("trick_edit");
        }
    }

}