<?php

namespace App\Controller\Trick;

use App\Entity\Trick;
use App\Form\TrickHeadingImageType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class TrickEditController extends AbstractController
{
    public const ACTION = 'edit';

    /**
     * @Route("/tricks/edit/{slug}", name="trick_edit")
     *
     * @param $slug
     */
    public function edit($slug, Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $trick = $manager->getRepository(Trick::class)->findOneBy(['slug' => $slug]);

        # Heading image form
        $headingForm = $this->createForm(TrickHeadingImageType::class, $trick, [
            'image_choices' => $trick->getImages()
        ]);
        $headingForm->handleRequest($request);
        if ($headingForm->isSubmitted() && $headingForm->isValid()) {
            $manager->flush();
        }

        return $this->render(
            'trick/edit.html.twig',
            [
                'trick' => $trick,
                'action' => self::ACTION,
                'headingForm' => $headingForm->createView()
            ]
        );
    }

    /**
     * @Route("/tricks/update/remove-heading-image/{id}", name="trick_remove_heading_image")
     */
    public function removeHeadingImage($id)
    {
        $manager = $this->getDoctrine()->getManager();
        $trick   = $manager->getRepository(Trick::class)->find($id);
        $trick->setHeadingImage(null);
        $manager->flush();

        return $this->redirectToRoute('trick_edit', ['slug' => $trick->getSlug()]);
    }
}
