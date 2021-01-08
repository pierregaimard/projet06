<?php

namespace App\Controller\Trick;

use App\Entity\Trick;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class TrickEditController extends AbstractController
{
    public const ACTION = 'edit';

    /**
     * @Route("/tricks/edit/{slug}", name="trick_edit")
     *
     * @param $slug
     */
    public function edit($slug)
    {
        $manager = $this->getDoctrine()->getManager();
        $trick = $manager->getRepository(Trick::class)->findOneBy(['slug' => $slug]);

        return $this->render(
            'trick/edit.html.twig',
            [
                'trick' => $trick,
                'action' => self::ACTION
            ]
        );
    }
}
