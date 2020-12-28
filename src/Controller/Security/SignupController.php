<?php

namespace App\Controller\Security;

use App\Entity\User;
use App\Form\SignupType;
use App\Service\Notification\Notification;
use App\Service\Notification\NotificationManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SignupController extends AbstractController
{
    /**
     * @Route("/sign-up", name="sign-up")
     *
     * @param Request             $request
     * @param NotificationManager $notification
     *
     * @return Response
     */
    public function signUp(Request $request, NotificationManager $notification): Response
    {
        $user = new User();
        $form = $this->createForm(SignupType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($user);

            $notification->add(new Notification(
                'Your account has been created!. We sent you an email to confirm your subscription.',
                Notification::TYPE_SUCCESS
            ));
            $notification->dispatch();

            return $this->redirectToRoute('home');
        }

        return $this->render(
            'security/signup/signup.html.twig',
            [
                'form' => $form->createView()
            ]
        );
    }
}
