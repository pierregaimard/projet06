<?php

namespace App\Controller\Security;

use App\Entity\AccountValidation;
use App\Entity\User;
use App\Form\AccountValidationType;
use App\Form\SignupType;
use App\Service\Notification\Notification;
use App\Service\Notification\NotificationManager;
use App\Service\Security\EmailValidation\EmailValidation;
use App\Service\Security\User\UserManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SignupController extends AbstractController
{
    /**
     * @var EmailValidation
     */
    private EmailValidation $emailValidation;

    /**
     * @var NotificationManager
     */
    private NotificationManager $notificationManager;

    public function __construct(EmailValidation $emailValidation, NotificationManager $notificationManager)
    {
        $this->emailValidation     = $emailValidation;
        $this->notificationManager = $notificationManager;
    }

    /**
     * @Route("/sign-up", name="sign-up")
     *
     * @param Request $request
     *
     * @return Response
     *
     * @throws TransportExceptionInterface
     */
    public function signUp(Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(SignupType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Save user
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($user);
            $manager->flush();

            // Send account email validation to user
            $this->emailValidation->sendValidationLink(
                $user,
                'account_email_validation',
                'Account validation',
                'email/account_email_validation.html.twig'
            );

            // Success notification
            $this->notificationManager->add(new Notification(
                'Your account has been created!. We sent you an email to confirm your subscription.',
                Notification::TYPE_SUCCESS
            ));
            $this->notificationManager->dispatch();

            return $this->redirectToRoute('home');
        }

        return $this->render('security/signup/signup.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/Security/Account/EmailValidation/{token}/{expires}", name="account_email_validation")
     *
     * @param string                       $token
     * @param string                       $expires
     * @param Request                      $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param UserManager                  $userManager
     *
     * @return Response
     */
    public function accountEmailValidation(
        string $token,
        string $expires,
        Request $request,
        UserPasswordEncoderInterface $passwordEncoder,
        UserManager $userManager
    ): Response {
        $accountValidation = new AccountValidation();
        $form = $this->createForm(AccountValidationType::class, $accountValidation);
        $form->handleRequest($request);

        $message = null;        // Error message
        $invalidToken = false;  // Email token

        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $userRepository = $manager->getRepository(User::class);

            // Username check
            $user = $userRepository->findOneBy(['username' => $accountValidation->getUsername()]);
            if (!$user instanceof User) {
                $message = 'app.signup.account_not_found';
            }

            // User password check
            if (!$message && !$passwordEncoder->isPasswordValid($user, $accountValidation->getPassword())) {
                $message = 'app.signup.invalid_password';
            }

            // Token check
            if (!$message && !$this->emailValidation->isTokenValid($user, $token, $expires)) {
                $message = 'app.signup.invalid_token';
                $invalidToken = true;
            }

            // If success
            if (!$message) {
                // Update user status
                $userManager->activate($user);

                // Success notification
                $this->notificationManager->add(new Notification(
                    'Your account has been validated!',
                    Notification::TYPE_SUCCESS
                ));
                $this->notificationManager->dispatch();

                return $this->redirectToRoute('home');
            }
        }

        return $this->render(
            'security/signup/account_validation.html.twig',
            [
                'form' => $form->createView(),
                'message' => $message,
                'invalidToken' => $invalidToken
            ]
        );
    }
}
