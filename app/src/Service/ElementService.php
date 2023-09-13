<?php
/**
 * Element service.
 */

namespace App\Service;

use App\Entity\Element;
use App\Repository\CommentRepository;
use App\Repository\ElementRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class ElementService.
 */
class ElementService implements ElementServiceInterface
{
    /**
     * Element repository.
     */
    private ElementRepository $elementRepository;

    /**
     * Comment repository.
     */
    private CommentRepository $commentRepository;

    /**
     * Paginator.
     */
    private PaginatorInterface $paginator;

    /**
     * CategoryService constructor.
     *
     * @param ElementRepository  $elementRepository Element repository
     * @param PaginatorInterface $paginator         Paginator
     * @param CommentRepository  $commentRepository Comment repository
     */
    public function __construct(ElementRepository $elementRepository, PaginatorInterface $paginator, CommentRepository $commentRepository)
    {
        $this->elementRepository = $elementRepository;
        $this->commentRepository = $commentRepository;
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
            $this->elementRepository->queryAll(),
            $page,
            ElementRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * Paginated list of comments by element.
     *
     * @param int     $page    Page number
     * @param Element $element Element entity
     *
     * @return PaginationInterface Paginated list
     */
    public function createCommentByElementPaginatedList(int $page, Element $element): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->commentRepository->queryByElement($element),
            $page,
            CommentRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * Save entity.
     *
     * @param Element $element Element entity
     */
    public function save(Element $element): void
    {
        $this->elementRepository->save($element);
    }

    /**
     * Delete entity.
     *
     * @param Element $element Element entity
     */
    public function delete(Element $element): void
    {
        $comments = $this->commentRepository->findBy(['element' => $element]);
        foreach ($comments as $comment) {
            $this->commentRepository->delete($comment);
        }
        $this->elementRepository->delete($element);
    }
}
