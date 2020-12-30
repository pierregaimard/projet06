<?php

namespace App\Controller\Security;

use App\Entity\User;
use App\Form\ForgotPasswordStepTwoType;
use App\Form\ForgotPasswordStepOneType;
use App\Service\Notification\Notification;
use App\Service\Notification\NotificationManager;
use App\Service\Security\EmailValidation\EmailValidation;
use App\Service\Security\User\UserManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/forgot-password", name="forgot_password_")
 */
class ForgotPasswordController extends AbstractController
{
    /**
     * @var EmailValidation
     */
    private EmailValidation $emailValidation;

    /**
     * @var NotificationManager
     */
    private NotificationManager $notificationManager;

    /**
     * @param EmailValidation     $emailValidation
     * @param NotificationManager $notificationManager
     */
    public function __construct(EmailValidation $emailValidation, NotificationManager $notificationManager)
    {
        $this->emailValidation     = $emailValidation;
        $this->notificationManager = $notificationManager;
    }

    /**
     * @Route("/email", name="step1")
     *
     * @param Request         $request
     * @param EmailValidation $emailValidation
     *
     * @return Response
     *
     * @throws TransportExceptionInterface
     */
    public function forgotPassword(Request $request, EmailValidation $emailValidation): Response
    {
        $user = new User();
        $form = $this->createForm(ForgotPasswordStepOneType::class, $user);
        $form->handleRequest($request);

        $message = null;
        $emailSent = false;

        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $userTest = $manager->getRepository(User::class)->findOneBy(['email' => $user->getEmail()]);
            if (!$userTest instanceof User) {
                $message = 'app.signup.account_not_found';
            }

            if (!$message) {
                $emailValidation->sendValidationLink(
                    $userTest,
                    'forgot_password_step2',
                    'Reset password',
                    'email/reset_password_check.html.twig'
                );

                $emailSent = true;
            }
        }

        return $this->render(
            'security/forgot_password/forgot_password_step1.html.twig',
            [
                'form' => $form->createView(),
                'message' => $message,
                'emailSent' => $emailSent,
            ]
        );
    }

    /**
     * @Route("/change/{token}/{expires}", name="step2")
     *
     * @param string      $token
     * @param string      $expires
     * @param Request     $request
     * @param UserManager $userManager
     *
     * @return Response
     */
    public function changePassword(
        string $token,
        string $expires,
        Request $request,
        UserManager $userManager
    ): Response {
        $formUser = new User();
        $form = $this->createForm(ForgotPasswordStepTwoType::class, $formUser);
        $form->handleRequest($request);

        $message = null;        // Error message
        $invalidToken = false;  // Email token

        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $userRepository = $manager->getRepository(User::class);

            // Username check
            $user = $userRepository->findOneBy(['username' => $formUser->getUsername()]);
            if (!$user instanceof User) {
                $message = 'app.signup.account_not_found';
            }

            // Token check
            if (!$message && !$this->emailValidation->isTokenValid($user, $token, $expires)) {
                $message = 'app.change_password.invalid_token';
                $invalidToken = true;
            }

            // If success
            if (!$message) {
                // Update user password
                $user->setPlainPassword($formUser->getPlainPassword());
                $userManager->setPassword($user);
                $manager->flush();

                $this->notificationManager->add(new Notification(
                    'Your password has been changed successfully!',
                    Notification::TYPE_SUCCESS
                ));
                $this->notificationManager->dispatch();

                return $this->redirectToRoute('home');
            }
        }

        return $this->render(
            'security/forgot_password/forgot_password_step2.html.twig',
            [
                'form' => $form->createView(),
                'message' => $message,
                'invalidToken' => $invalidToken,
            ]
        );
    }
}
