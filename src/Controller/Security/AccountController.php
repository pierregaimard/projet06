<?php

namespace App\Controller\Security;

use App\Entity\AccountPassword;
use App\Form\AccountInformationType;
use App\Form\AccountPasswordType;
use App\Service\Notification\Notification;
use App\Service\Notification\NotificationManager;
use App\Service\Security\User\UserManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountController extends AbstractController
{
    /**
     * @Route("/account", name="account")
     *
     * @param Request             $request
     * @param UserManager         $userManager
     * @param NotificationManager $notification
     *
     * @return Response
     */
    public function account(Request $request, UserManager $userManager, NotificationManager $notification): Response
    {
        $manager = $this->getDoctrine()->getManager();

        // User information form
        $user = $this->getUser();
        $formInfo = $this->createForm(AccountInformationType::class, $user);

        $formInfo->handleRequest($request);

        if ($formInfo->isSubmitted() && $formInfo->isValid()) {
            $manager->persist($user);
            $manager->flush();

            $notification->add(new Notification(
                'Your information has been updated successfully.',
                Notification::TYPE_SUCCESS
            ));
            $notification->dispatch();

            $this->redirectToRoute('account');
        }

        // User password form
        $accountPassword = new AccountPassword();
        $formPassword = $this->createForm(AccountPasswordType::class, $accountPassword);
        $formPassword->handleRequest($request);

        if ($formPassword->isSubmitted() && $formPassword->isValid()) {
            // Set & persists new user password
            $user->setPlainPassword($accountPassword->getPlainPassword());
            $userManager->setPassword($user);
            $manager->persist($user);
            $manager->flush();

            $notification->add(new Notification(
                'Your password has been updated successfully.',
                Notification::TYPE_SUCCESS
            ));
            $notification->dispatch();

            $this->redirectToRoute('account');
        }

        return $this->render(
            'security/account/account.html.twig',
            [
                'account_img_dir' => $this->getParameter('app.public_account_img_dir'),
                'formInfo' => $formInfo->createView(),
                'formPassword' => $formPassword->createView()
            ]
        );
    }
}
