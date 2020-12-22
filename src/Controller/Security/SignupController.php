<?php

namespace App\Controller\Security;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SignupController extends AbstractController
{
    /**
     * @Route("/sign-up", name="sign-up")
     *
     * @return Response
     */
    public function signUp(): Response
    {
        return $this->render('security/signup/signup.html.twig');
    }
}
