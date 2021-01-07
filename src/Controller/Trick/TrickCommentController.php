<?php

namespace App\Controller\Trick;

use App\Entity\Comment;
use App\Entity\Trick;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class TrickCommentController extends AbstractController
{
    /**
     * @var int
     */
    private int $commentsLimit;

    /**
     * @param $commentsLimit
     */
    public function __construct($commentsLimit)
    {
        $this->commentsLimit = (int)$commentsLimit;
    }

    /**
     * @Route("/tricks/view/comments/get/{idTrick}", name="trick_view_comments")
     *
     * @param $idTrick
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function loadComments($idTrick, Request $request): JsonResponse
    {
        $offset = $request->request->get('offset');
        $commentElements = null;

        $manager = $this->getDoctrine()->getManager();
        $trick = $manager->getRepository(Trick::class)->find($idTrick);

        $commentsRepository = $manager->getRepository(Comment::class);
        $comments = $commentsRepository->findBy(
            ['trick' => $trick],
            ['creationTime' => 'DESC'],
            $this->commentsLimit,
            $offset
        );

        foreach ($comments as $comment) {
            $toDelete = false;
            if ($this->getUser() && $this->getUser()->getId() === $comment->getAuthor()->getId()) {
                $toDelete = true;
            }

            $commentElements[] = [
                'id' => $comment->getId(),
                'src' => $this->getParameter('app.public_account_img_dir') . $comment->getAuthor()->getPicture(),
                'author' => ucwords($comment->getAuthor()->getFirstName() . ' ' . $comment->getAuthor()->getLastName()),
                'createdAt' => $comment->getCreationTime()->format('j F Y'),
                'comment' => $comment->getContent(),
                'toDelete' => $toDelete,
            ];
        }

        return new JsonResponse([
            'comments' => $commentElements,
            'loadMore' => $this->isLoadMore($offset, $trick),
        ]);
    }

    /**
     * @Route("/tricks/view/comments/delete/{idTrick}", name="trick_comment_delete")
     * @IsGranted("ROLE_USER")
     *
     * @param $idTrick
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function deleteComment($idTrick, Request $request): JsonResponse
    {
        $idComment = $request->request->get('id');
        $offset    = (int)$request->request->get('offset');

        $manager = $this->getDoctrine()->getManager();
        $trick   = $manager->getRepository(Trick::class)->find($idTrick);
        $comment = $manager->getRepository(Comment::class)->find($idComment);

        $manager->remove($comment);
        $manager->flush();

        return new JsonResponse([
            'done' => true,
            'loadMore' => $this->isLoadMore($offset, $trick),
        ]);
    }

    /**
     * @param int   $offset
     * @param Trick $trick
     *
     * @return bool
     */
    private function isLoadMore(int $offset, Trick $trick): bool
    {
        $manager = $this->getDoctrine()->getManager();
        $commentsRepository = $manager->getRepository(Comment::class);

        return $offset + $this->commentsLimit < $commentsRepository->count(['trick' => $trick]);
    }
}
