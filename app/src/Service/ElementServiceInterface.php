<?php
/**
 * Element service interface.
 */

namespace App\Service;

use App\Entity\Element;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * Interface ElementServiceInterface.
 */
interface ElementServiceInterface
{
    /**
     * Get paginated list.
     *
     * @param int $page Page number
     *
     * @return PaginationInterface<string, mixed> Paginated list
     */
    public function getPaginatedList(int $page): PaginationInterface;

    /**
     * Get paginated list by element.
     *
     * @param int  $page Page number
     * @param Element $element Element entity
     *
     * @return PaginationInterface Paginated list
     */
    public function createCommentByElementPaginatedList(int $page, Element $element): PaginationInterface;

    /**
     * Save entity.
     *
     * @param Element $element Element entity
     */
    public function save(Element $element): void;

    /**
     * Delete entity.
     *
     * @param Element $element Element entity
     */
    public function delete(Element $element): void;
}
