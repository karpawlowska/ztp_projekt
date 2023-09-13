<?php
/**
 * Comment service interface.
 */

namespace App\Service;

use App\Entity\Comment;
use App\Repository\CommentRepository;
use App\Repository\ElementRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Interface CommentServiceInterface.
 */
class CommentService implements CommentServiceInterface
{
    /**
     * Comment repository.
     */
    private CommentRepository $commentRepository;

    /**
     * Element repository.
     */
    private ElementRepository $elementRepository;

    /**
     * Paginator.
     */
    private PaginatorInterface $paginator;

    /**
     * CommentService constructor.
     *
     * @param CommentRepository  $commentRepository Comment repository
     * @param PaginatorInterface $paginator         Paginator
     * @param ElementRepository  $elementRepository Element repository
     */
    public function __construct(CommentRepository $commentRepository, PaginatorInterface $paginator, ElementRepository $elementRepository)
    {
        $this->commentRepository = $commentRepository;
        $this->elementRepository = $elementRepository;
        $this->paginator = $paginator;
    }

    /**
     * Get paginated list.
     *
     * @param int $page Page number
     *
     * @return PaginationInterface<string, mixed> Paginated list
     */
    public function getPaginatedList(int $page): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->commentRepository->queryAll(),
            $page,
            CommentRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * Save entity.
     *
     * @param Comment $comment   Comment entity
     * @param int     $elementId Element id
     */
    public function save(Comment $comment, int $elementId): void
    {
        $element = $this->elementRepository->findOneBy(['id' => $elementId]);
        $comment->setElement($element);
        $this->commentRepository->save($comment);
    }

    /**
     * Delete entity.
     *
     * @param Comment $comment Comment entity
     */
    public function delete(Comment $comment): void
    {
        $this->commentRepository->delete($comment);
    }

    /**
     * Update entity.
     *
     * @param Comment $comment Comment entity
     */
    public function update(Comment $comment): void
    {
        $this->commentRepository->save($comment);
    }
}
