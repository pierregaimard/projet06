<?php

namespace App\Controller\Security;

use App\Entity\AccountPassword;
use App\Form\AccountInformationType;
use App\Form\AccountPasswordType;
use App\Service\Notification\Notification;
use App\Service\Notification\NotificationManager;
use App\Service\Security\EmailValidation\EmailValidation;
use App\Service\Security\User\UserManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
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

    /**
     * @Route("/account/email-validation", name="account_send_email_validation")
     *
     * @param EmailValidation     $emailValidation
     * @param NotificationManager $notification
     *
     * @return Response
     *
     * @throws TransportExceptionInterface
     */
    public function sendEmailValidationLink(
        EmailValidation $emailValidation,
        NotificationManager $notification
    ): Response {
        $emailValidation->sendValidationLink(
            $this->getUser(),
            'account_email_validation',
            'Account validation',
            'email/account_email_validation.html.twig'
        );

        $notification->add(new Notification(
            'We sent you a new account email validation. Please check your mails!',
            Notification::TYPE_INFO
        ));
        $notification->dispatch();

        return $this->redirectToRoute('account');
    }
}
