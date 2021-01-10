<?php

namespace App\Controller\Trick;

use App\Entity\Trick;
use App\Form\TrickEditType;
use App\Form\TrickHeadingImageType;
use App\Service\Notification\Notification;
use App\Service\Notification\NotificationManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Exception;

class TrickEditController extends AbstractController
{
    /**
     * @Route("/tricks/edit/{slug}", name="trick_edit")
     *
     * @param $slug
     * @param Request $request
     * @param NotificationManager $notification
     *
     * @return Response
     */
    public function edit($slug, Request $request, NotificationManager $notification): Response
    {
        $manager = $this->getDoctrine()->getManager();
        $trick = $manager->getRepository(Trick::class)->findOneBy(['slug' => $slug]);

        # Heading image form
        $headingFormView = null;
        if (!$trick->getImages()->isEmpty()) {
            $headingForm = $this->createForm(TrickHeadingImageType::class, $trick, [
                'image_choices' => $trick->getImages()
            ]);
            $headingForm->handleRequest($request);
            if ($headingForm->isSubmitted() && $headingForm->isValid()) {
                $manager->flush();
            }
            $headingFormView = $headingForm->createView();
        }

        # Information form
        $infoForm = $this->createForm(TrickEditType::class, $trick);
        $infoForm->handleRequest($request);
        if ($infoForm->isSubmitted() && $infoForm->isValid()) {
            $manager->flush();

            $notification->add(new Notification(
                'Information has been updated successfully!',
                Notification::TYPE_SUCCESS
            ));
            $notification->dispatch();

            return $this->redirectToRoute('trick_view', ['slug' => $trick->getSlug()]);
        }

        return $this->render(
            'trick/edit.html.twig',
            [
                'trick' => $trick,
                'edit' => true,
                'headingForm' => $headingFormView,
                'infoForm' => $infoForm->createView(),
            ]
        );
    }

    /**
     * @Route("/tricks/update/remove-heading-image/{id}", name="trick_remove_heading_image")
     *
     * @param $id
     *
     * @return Response
     */
    public function removeHeadingImage($id): Response
    {
        $manager = $this->getDoctrine()->getManager();
        $trick   = $manager->getRepository(Trick::class)->find($id);
        $trick->setHeadingImage(null);
        $manager->flush();

        return $this->redirectToRoute('trick_edit', ['slug' => $trick->getSlug()]);
    }

    /**
     * @Route("tricks/update/remove-media/{type}/{id}", name="trick_media_remove")
     *
     * @param $type
     * @param $id
     *
     * @return Response
     *
     * @throws Exception
     */
    public function removeMedia($type, $id, NotificationManager $notification)
    {
        if (!class_exists($type)) {
            throw new Exception('This entity class do not exists');
        }
        $manager = $this->getDoctrine()->getManager();
        $media = $manager->getRepository($type)->find($id);
        $manager->remove($media);
        $manager->flush();

        $notification->add(new Notification(
            'The media has been removed successfully!',
            Notification::TYPE_SUCCESS,
        ));
        $notification->dispatch();

        return $this->redirectToRoute('trick_edit', ['slug' => $media->getTrick()->getSlug()]);
    }
}
