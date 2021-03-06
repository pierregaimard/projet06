<?php

namespace App\Controller\Trick;

use App\Entity\Trick;
use App\Entity\TrickImage;
use App\Entity\TrickVideo;
use App\Form\TrickEditType;
use App\Form\TrickHeadingImageType;
use App\Form\TrickImageType;
use App\Form\TrickVideoType;
use App\Service\Notification\Notification;
use App\Service\Notification\NotificationManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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

        # ImageForm
        $image = new TrickImage();
        $imageForm = $this->createForm(TrickImageType::class, $image);
        if ($this->manageImageForm($image, $imageForm, $request, $trick, $notification)) {
            return $this->redirectToRoute('trick_edit', ['slug' => $trick->getSlug()]);
        }

        # Video form
        $video = new TrickVideo();
        $videoForm = $this->createForm(TrickVideoType::class, $video);
        if ($this->manageVideoForm($video, $videoForm, $request, $trick, $notification)) {
            return $this->redirectToRoute('trick_edit', ['slug' => $trick->getSlug()]);
        }

        return $this->render(
            'trick/edit.html.twig',
            [
                'trick' => $trick,
                'edit' => true,
                'headingForm' => $headingFormView,
                'infoForm' => $infoForm->createView(),
                'imageForm' => $imageForm->createView(),
                'videoForm' => $videoForm->createView(),
            ]
        );
    }

    /**
     * @param TrickImage          $image
     * @param FormInterface       $imageForm
     * @param Request             $request
     * @param Trick               $trick
     * @param NotificationManager $notification
     *
     * @return bool
     */
    private function manageImageForm(
        TrickImage $image,
        FormInterface $imageForm,
        Request $request,
        Trick $trick,
        NotificationManager $notification
    ): bool {
        $manager = $this->getDoctrine()->getManager();

        $imageForm->handleRequest($request);
        if ($imageForm->isSubmitted() && $imageForm->isValid()) {
            $idImage = $request->request->get('idImage');
            $message = 'The image has been added successfully';

            # If changes an existing image
            if ($idImage !== "") {
                $file = $image->getUploadedFile();
                $image = $manager->getRepository(TrickImage::class)->find($idImage);
                $image->setUploadedFile($file);
                $message = 'The image has been updated successfully';
            }

            $image->setTrick($trick);
            $manager->persist($image);
            $manager->flush();

            $notification->add(new Notification($message, Notification::TYPE_SUCCESS));
            $notification->dispatch();

            return true;
        }

        return false;
    }

    /**
     * @param TrickVideo          $video
     * @param FormInterface       $videoForm
     * @param Request             $request
     * @param Trick               $trick
     * @param NotificationManager $notification
     *
     * @return bool
     */
    private function manageVideoForm(
        TrickVideo $video,
        FormInterface $videoForm,
        Request $request,
        Trick $trick,
        NotificationManager $notification
    ): bool {
        $manager = $this->getDoctrine()->getManager();
        $videoForm->handleRequest($request);

        if ($videoForm->isSubmitted() && $videoForm->isValid()) {
            $idVideo = $request->request->get('idVideo');
            $message = 'The video has been added successfully';

            # If changes an existing image
            if ($idVideo !== "") {
                $tag = $video->getTag();
                $video = $manager->getRepository(TrickVideo::class)->find($idVideo);
                $video->setTag($tag);
                $message = 'The video has been updated successfully';
            }

            $video->setTrick($trick);
            $manager->persist($video);
            $manager->flush();

            $notification->add(new Notification($message, Notification::TYPE_SUCCESS));
            $notification->dispatch();

            return true;
        }

        return false;
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
     * @param NotificationManager $notification
     *
     * @return Response
     */
    public function removeMedia($type, $id, NotificationManager $notification)
    {
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
