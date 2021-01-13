<?php

namespace App\Controller\Security;

use App\Service\Account\AccountImageManager;
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
     * @param Request             $request
     * @param AccountImageManager $imageManager
     *
     * @return JsonResponse
     */
    public function addImage(Request $request, AccountImageManager $imageManager): JsonResponse
    {
        // Get cropped file
        $fileData = $request->files->get('avatar');
        $file = new File($fileData);

        $user = $this->getUser();
        try {
            $imageManager->save($file, $user);
        } catch (FileException $fileException) {
            return new JsonResponse(['result' => $fileException->getMessage()]);
        }
        $user->setPicture($imageManager->getFileName($user));

        $manager = $this->getDoctrine()->getManager();
        $manager->persist($user);
        $manager->flush();

        return new JsonResponse([]);
    }
}
