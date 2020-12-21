<?php

namespace App\Controller;

use App\Entity\Trick;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function home(): Response
    {
        $manager = $this->getDoctrine()->getManager();
        $tricks = $manager->getRepository(Trick::class)->findWithCategoryAndImages(0, 8);

        return $this->render('home/home.html.twig', ['tricks' => $tricks]);
    }

    /**
     * @Route("/loadmore", name="load_more_tricks")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function loadMore(Request $request): Response
    {
        $offset     = (int)$request->request->get('offset');
        $limit      = (int)$request->request->get('limit');
        $repository = $this->getDoctrine()->getManager()->getRepository(Trick::class);
        $tricks     = $repository->findWithCategoryAndImages($offset, $limit);
        $count      = $repository->count([]);
        $end        = $count <= $offset + 4;
        $data       = [];
        
        foreach ($tricks as $trick) {
            $data[] = $this->renderView('home/_trick_card.html.twig', [
                'view' => $this->generateUrl('home'),
                'imageName' => $trick->getHeadingImage()->getFileName(),
                'category' => $trick->getCategory()->getName(),
                'name' => $trick->getName(),
                'description' => $trick->getShortDescription(),
                'edit' => $this->generateUrl('home'),
                'remove' => $this->generateUrl('home')
            ]);
        }

        return new JsonResponse([
            'data' => $data,
            'end' => $end
        ]);
    }
}
