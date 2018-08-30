<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use AppBundle\Traits\IdentifiableEntityInterface;
use AppBundle\Traits\MeshDescriptorsEntityInterface;
use AppBundle\Traits\SortableEntityInterface;
use AppBundle\Traits\TitledEntityInterface;
use AppBundle\Traits\CoursesEntityInterface;
use AppBundle\Traits\SessionsEntityInterface;
use AppBundle\Traits\ProgramYearsEntityInterface;

/**
 * Interface ObjectiveInterface
 */
interface ObjectiveInterface extends
    IdentifiableEntityInterface,
    TitledEntityInterface,
    CoursesEntityInterface,
    SessionsEntityInterface,
    ProgramYearsEntityInterface,
    LoggableEntityInterface,
    MeshDescriptorsEntityInterface,
    SortableEntityInterface
{
    /**
     * @param CompetencyInterface $competency
     */
    public function setCompetency(CompetencyInterface $competency);

    /**
     * @return CompetencyInterface
     */
    public function getCompetency();

    /**
     * @param Collection $parents
     */
    public function setParents(Collection $parents);

    /**
     * @param ObjectiveInterface $parent
     */
    public function addParent(ObjectiveInterface $parent);

    /**
     * @param ObjectiveInterface $parent
     */
    public function removeParent(ObjectiveInterface $parent);

    /**
     * @return ObjectiveInterface[]
     */
    public function getParents();

    /**
     * @param Collection $children
     */
    public function setChildren(Collection $children);

    /**
     * @param ObjectiveInterface $child
     */
    public function addChild(ObjectiveInterface $child);

    /**
     * @param ObjectiveInterface $child
     */
    public function removeChild(ObjectiveInterface $child);

    /**
     * @return ObjectiveInterface[]
     */
    public function getChildren();

    /**
     * @param ObjectiveInterface $ancestor
     */
    public function setAncestor(ObjectiveInterface $ancestor);

    /**
     * @return ObjectiveInterface
     */
    public function getAncestor();

    /**
     * @return ObjectiveInterface
     */
    public function getAncestorOrSelf();

    /**
     * @param Collection $children
     */
    public function setDescendants(Collection $children);

    /**
     * @param ObjectiveInterface $child
     */
    public function addDescendant(ObjectiveInterface $child);

    /**
     * @param ObjectiveInterface $child
     */
    public function removeDescendant(ObjectiveInterface $child);

    /**
     * @return ArrayCollection|ObjectiveInterface[]
     */
    public function getDescendants();
}
