<?php

namespace AppBundle\Traits;

/**
 * Interface SortableEntityInterface
 */
interface SortableEntityInterface
{
    /**
     * @param int $position
     */
    public function setPosition($position);

    /**
     * @return int
     */
    public function getPosition();
}