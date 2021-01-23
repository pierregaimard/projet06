<?php

namespace App\Controller\Security;

use App\Service\Notification\Notification;
use App\Service\Notification\NotificationManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Routing\Annotation\Route;

class AccountRemoveController extends AbstractController
{
    /**
     * @Route("/account/delete", name="account_delete")
     *
     * @param NotificationManager   $notification
     * @param SessionInterface      $session
     * @param TokenStorageInterface $tokenStorage
     *
     * @return Response
     */
    public function delete(
        NotificationManager $notification,
        SessionInterface $session,
        TokenStorageInterface $tokenStorage
    ): Response {
        $user = $this->getUser();
        $session->invalidate();
        $tokenStorage->setToken(null);
        $manager = $this->getDoctrine()->getManager();
        $manager->remove($user);

        $manager->flush();

        $notification->add(new Notification(
            'Your account has been deleted successfully.',
            Notification::TYPE_SUCCESS
        ));
        $notification->dispatch();

        return $this->redirectToRoute('home');
    }
}
