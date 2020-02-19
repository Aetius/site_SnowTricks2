<?php


namespace App\Controller\Video;


use App\Entity\Video;
use App\Form\Video\EditVideoType;
use App\Services\Video\VideoManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class VideoController extends AbstractController
{
    /**
     *@Route("/video/edit/{id}", name="video_edit", methods={"GET|POST"})
     * @IsGranted("ROLE_EDITOR")
     */
    public function edit(Video $video, Request $request, VideoManager $manager)
    {
        $form = $this->createForm(EditVideoType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $manager->edit($video, $form->getData());
            $this->addFlash('success', "flash.trick.edit");
            return $this->redirectToRoute('trick_edit', ['id'=> $video->getTrick()->getId()]);
        }

        return $this->render('video/_edit.html.twig', [
            'video' => $video,
            'form' => $form->createView()
        ]);
    }

    /**
     *@Route("/video/delete/{id}", name="video_delete", methods={"GET"})
     * @IsGranted("ROLE_EDITOR")
     */
    public function delete(Video $video, Request $request, VideoManager $manager )
    {
        $trickId = $video->getTrick()->getId();

        if ($this->isCsrfTokenValid('delete'.$video->getId(), $request->get('_token'))) {
            $manager->delete($video);
            $this->addFlash('success', 'deleting_photo');
        }


        return $this->redirectToRoute("trick_edit", ['id' => $trickId]);
    }


}