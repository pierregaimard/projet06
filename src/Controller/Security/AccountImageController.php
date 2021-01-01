<?php

namespace App\Controller\Security;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AccountImageController extends AbstractController
{
    /**
     * @Route("/account/avatar", name="account_avatar")
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function addImage(Request $request): JsonResponse
    {
        // Get cropped file
        $fileData = $request->files->get('avatar');
        $file = new File($fileData);

        try {
            // Save file
            $filename = $this->getUser()->getUsername() . '.jpeg';
            $file->move($this->getParameter('app.server_account_img_dir'), $filename);

            // Update user picture attribute
            $user = $this->getUser();
            $user->setPicture($filename);
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($user);
            $manager->flush();
        } catch (FileException $exception) {
            return new JsonResponse(
                [
                    'result' =>
                        'Sorry but a problem occurred!.' .
                        'Error: ' . $exception->getCode() . "\n" .
                        'Message: ' . $exception->getMessage() . "\n" .
                        'Trace: ' . $exception->getTraceAsString() . "\n" .
                        'Please contact the administrator.'
                ],
                500
            );
        }

        return new JsonResponse();
    }
}
