<?php

namespace App\Controller\Trick;

use App\Entity\Comment;
use App\Entity\Trick;
use App\Form\CommentType;
use App\Service\Notification\Notification;
use App\Service\Notification\NotificationManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TrickViewController extends AbstractController
{
    /**
     * @Route("/tricks/view/{slug}", name="trick_view")
     *
     * @param string              $slug
     * @param Request             $request
     * @param NotificationManager $notification
     *
     * @return Response
     */
    public function view(string $slug, Request $request, NotificationManager $notification): Response
    {
        $manager = $this->getDoctrine()->getManager();
        $trick = $manager->getRepository(Trick::class)->findOneBy(['slug' => $slug]);

        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            # Set comment data
            $comment->setAuthor($this->getUser());
            $comment->setTrick($trick);

            # Persists comment
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($comment);
            $manager->flush();

            $notification->add(new Notification(
                'Your comment has been added successfully !',
                Notification::TYPE_SUCCESS
            ));
            $notification->dispatch();

            return $this->redirectToRoute(
                'trick_view',
                ['slug' => $trick->getSlug(), '_fragment' => 'trick-comments']
            );
        }

        return $this->render(
            'trick/view.html.twig',
            [
                'trick' => $trick,
                'form' => $form->createView(),
                'edit' => false
            ]
        );
    }
}
