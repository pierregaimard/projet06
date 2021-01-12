<?php

namespace App\Controller\Trick;

use App\Entity\Trick;
use App\Form\TrickType;
use App\Service\Notification\Notification;
use App\Service\Notification\NotificationManager;
use App\Service\Trick\Video\TrickVideoTagManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class TrickAddController extends AbstractController
{
    /**
     * @Route("/tricks/add", name="trick_add")
     * @IsGranted("ROLE_USER")
     *
     * @param Request             $request
     * @param NotificationManager $notification
     *
     * @return Response
     */
    public function add(Request $request, NotificationManager $notification): Response
    {
        $trick = new Trick();
        $form = $this->createForm(TrickType::class, $trick);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($trick);
            $manager->flush();

            $notification->add(new Notification(
                'Your new trick has been added successfully!',
                Notification::TYPE_SUCCESS
            ));
            $notification->dispatch();

            return $this->redirectToRoute('home', ['_fragment' => 'app-tricks-section']);
        }

        return $this->render('trick/add.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/tricks/add/checkName", name="trick_add_name_check")
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function checkUniqueName(Request $request): JsonResponse
    {
        $name = $request->request->get('name');

        # Search trick name duplication
        $manager = $this->getDoctrine()->getManager();
        $trickRepository = $manager->getRepository(Trick::class);
        $trick = $trickRepository->findOneBy(['name' => $name]);

        return new JsonResponse([
            'unique' => !$trick instanceof Trick,
            'value' => $name,
        ]);
    }

    /**
     * @Route("/tricks/add/checkTag", name="trick_add_tag_check")
     *
     * @param Request              $request
     * @param TrickVideoTagManager $tagManager
     *
     * @return JsonResponse
     */
    public function checkVideoTag(Request $request, TrickVideoTagManager $tagManager): JsonResponse
    {
        $result = $tagManager->isValid($request->request->get('tag'));

        return new JsonResponse(['valid' => $result]);
    }

    /**
     * @Route("/tricks/remove/{id}", name="trick_remove")
     *
     * @param                     $id
     * @param NotificationManager $notification
     *
     * @return Response
     */
    public function remove($id, NotificationManager $notification): Response
    {
        $manager = $this->getDoctrine()->getManager();
        $trick = $manager->getRepository(Trick::class)->find($id);

        foreach ($trick->getImages() as $image) {
            $manager->remove($image);
        }
        foreach ($trick->getVideos() as $video) {
            $manager->remove($video);
        }
        $manager->flush();

        $manager->remove($trick);
        $manager->flush();

        $notification->add(new Notification(
            '<b>' . $trick->getName() . '</b> trick has been removed successfully!',
            Notification::TYPE_SUCCESS
        ));
        $notification->dispatch();

        return $this->redirectToRoute('home', ['_fragment' => 'app-tricks-section']);
    }
}
