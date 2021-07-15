<?php

declare(strict_types=1);

namespace App\Entity;

use App\Attribute as IA;
use App\Traits\ActivatableEntity;
use App\Traits\CategorizableEntity;
use App\Traits\IdentifiableEntity;
use App\Traits\MeshDescriptorsEntity;
use App\Traits\SessionConsolidationEntity;
use App\Traits\SortableEntity;
use App\Traits\StringableIdEntity;
use App\Traits\TitledEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\SessionObjectiveRepository;

/**
 * Class SessionObjective
 */
#[ORM\Table(name: 'session_x_objective')]
#[ORM\Index(columns: ['session_id'], name: 'IDX_FA74B40B613FECDF')]
#[ORM\Entity(repositoryClass: SessionObjectiveRepository::class)]
#[IA\Entity]
class SessionObjective implements SessionObjectiveInterface
{
    use IdentifiableEntity;
    use StringableIdEntity;
    use SessionConsolidationEntity;
    use TitledEntity;
    use MeshDescriptorsEntity;
    use ActivatableEntity;
    use CategorizableEntity;
    use SortableEntity;

    /**
     * @var int
     * @Assert\Type(type="integer")
     */
    #[ORM\Column(name: 'session_objective_id', type: 'integer')]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[IA\Expose]
    #[IA\Type('integer')]
    #[IA\ReadOnly]
    protected $id;

    /**
     * @var SessionInterface
     * @Assert\NotNull()
     */
    #[ORM\ManyToOne(targetEntity: 'Session', inversedBy: 'sessionObjectives')]
    #[ORM\JoinColumn(name: 'session_id', referencedColumnName: 'session_id', onDelete: 'CASCADE')]
    #[IA\Expose]
    #[IA\Type('entity')]
    protected $session;

    /**
     * @var int
     * @Assert\NotBlank()
     * @Assert\Type(type="integer")
     */
    #[ORM\Column(name: 'position', type: 'integer')]
    #[IA\Expose]
    #[IA\Type('integer')]
    protected $position;

    /**
     * @var Collection
     */
    #[ORM\ManyToMany(targetEntity: 'Term', inversedBy: 'sessionObjectives')]
    #[ORM\JoinTable(name: 'session_objective_x_term')]
    #[ORM\JoinColumn(name: 'session_objective_id', referencedColumnName: 'session_objective_id', onDelete: 'CASCADE')]
    #[ORM\InverseJoinColumn(name: 'term_id', referencedColumnName: 'term_id', onDelete: 'CASCADE')]
    #[ORM\OrderBy(['id' => 'ASC'])]
    #[IA\Expose]
    #[IA\Type('entityCollection')]
    protected $terms;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Type(type="string")
     * @Assert\Length(
     *      min = 1,
     *      max = 65000
     * )
     */
    #[ORM\Column(type: 'text')]
    #[IA\Expose]
    #[IA\Type('string')]
    #[IA\RemoveMarkup]
    protected $title;

    /**
     * @var Collection
     */
    #[ORM\ManyToMany(targetEntity: 'CourseObjective', inversedBy: 'sessionObjectives')]
    #[ORM\JoinTable('session_objective_x_course_objective')]
    #[ORM\JoinColumn(name: 'session_objective_id', referencedColumnName: 'session_objective_id', onDelete: 'CASCADE')]
    #[ORM\InverseJoinColumn(
        name: 'course_objective_id',
        referencedColumnName: 'course_objective_id',
        onDelete: 'CASCADE'
    )]
    #[ORM\OrderBy(['id' => 'ASC'])]
    #[IA\Expose]
    #[IA\Type('entityCollection')]
    protected $courseObjectives;

    /**
     * @var Collection
     */
    #[ORM\ManyToMany(targetEntity: 'MeshDescriptor', inversedBy: 'sessionObjectives')]
    #[ORM\JoinTable(name: 'session_objective_x_mesh')]
    #[ORM\JoinColumn(name: 'session_objective_id', referencedColumnName: 'session_objective_id', onDelete: 'CASCADE')]
    #[ORM\InverseJoinColumn(
        name: 'mesh_descriptor_uid',
        referencedColumnName: 'mesh_descriptor_uid',
        onDelete: 'CASCADE'
    )]
    #[ORM\OrderBy(['id' => 'ASC'])]
    #[IA\Expose]
    #[IA\Type('entityCollection')]
    protected $meshDescriptors;

    /**
     * @var SessionObjectiveInterface
     */
    #[ORM\ManyToOne(targetEntity: 'SessionObjective', inversedBy: 'descendants')]
    #[ORM\JoinColumn(name: 'ancestor_id', referencedColumnName: 'session_objective_id')]
    #[IA\Expose]
    #[IA\Type('entity')]
    protected $ancestor;

    /**
     * @var Collection
     */
    #[ORM\OneToMany(targetEntity: 'SessionObjective', mappedBy: 'ancestor')]
    #[ORM\OrderBy(['id' => 'ASC'])]
    #[IA\Expose]
    #[IA\Type('entityCollection')]
    protected $descendants;

    /**
     * @var bool
     * @Assert\NotNull()
     * @Assert\Type(type="bool")
     */
    #[ORM\Column(type: 'boolean')]
    #[IA\Expose]
    #[IA\Type('boolean')]
    protected $active;

    public function __construct()
    {
        $this->position = 0;
        $this->active = true;
        $this->terms = new ArrayCollection();
        $this->courseObjectives = new ArrayCollection();
        $this->meshDescriptors = new ArrayCollection();
        $this->descendants = new ArrayCollection();
    }

    /**
     * @inheritdoc
     */
    public function setSession(SessionInterface $session): void
    {
        $this->session = $session;
    }

    /**
     * @inheritdoc
     */
    public function getSession(): SessionInterface
    {
        return $this->session;
    }

    /**
     * @inheritDoc
     */
    public function getIndexableCourses(): array
    {
        return [$this->session->getCourse()];
    }

    /**
     * @inheritdoc
     */
    public function setCourseObjectives(Collection $courseObjectives)
    {
        $this->courseObjectives = new ArrayCollection();

        foreach ($courseObjectives as $courseObjective) {
            $this->addCourseObjective($courseObjective);
        }
    }

    /**
     * @inheritdoc
     */
    public function addCourseObjective(CourseObjectiveInterface $courseObjective)
    {
        if (!$this->courseObjectives->contains($courseObjective)) {
            $this->courseObjectives->add($courseObjective);
        }
    }

    /**
     * @inheritdoc
     */
    public function removeCourseObjective(CourseObjectiveInterface $courseObjective)
    {
        $this->courseObjectives->removeElement($courseObjective);
    }

    /**
     * @inheritdoc
     */
    public function getCourseObjectives()
    {
        return $this->courseObjectives;
    }

    /**
     * @inheritdoc
     */
    public function setAncestor(SessionObjectiveInterface $ancestor = null)
    {
        $this->ancestor = $ancestor;
    }

    /**
     * @inheritdoc
     */
    public function getAncestor()
    {
        return $this->ancestor;
    }

    /**
     * @inheritdoc
     */
    public function getAncestorOrSelf()
    {
        $ancestor = $this->getAncestor();

        return $ancestor ? $ancestor : $this;
    }

    /**
     * @inheritdoc
     */
    public function setDescendants(Collection $descendants)
    {
        $this->descendants = new ArrayCollection();

        foreach ($descendants as $descendant) {
            $this->addDescendant($descendant);
        }
    }

    /**
     * @inheritdoc
     */
    public function addDescendant(SessionObjectiveInterface $descendant)
    {
        if (!$this->descendants->contains($descendant)) {
            $this->descendants->add($descendant);
        }
    }

    /**
     * @inheritdoc
     */
    public function removeDescendant(SessionObjectiveInterface $descendant)
    {
        $this->descendants->removeElement($descendant);
    }

    /**
     * @inheritdoc
     */
    public function getDescendants()
    {
        return $this->descendants;
    }

    /**
     * @inheritdoc
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @inheritdoc
     */
    public function setPosition($position)
    {
        $this->position = $position;
    }

    /**
     * @inheritdoc
     */
    public function setActive($active)
    {
        $this->active = $active;
    }

    /**
     * @inheritdoc
     */
    public function setMeshDescriptors(Collection $meshDescriptors)
    {
        $this->meshDescriptors = new ArrayCollection();

        foreach ($meshDescriptors as $meshDescriptor) {
            $this->addMeshDescriptor($meshDescriptor);
        }
    }

    /**
     * @inheritdoc
     */
    public function addMeshDescriptor(MeshDescriptorInterface $meshDescriptor)
    {
        if (!$this->meshDescriptors->contains($meshDescriptor)) {
            $this->meshDescriptors->add($meshDescriptor);
        }
    }

    /**
     * @inheritdoc
     */
    public function removeMeshDescriptor(MeshDescriptorInterface $meshDescriptor)
    {
        $this->meshDescriptors->removeElement($meshDescriptor);
    }
}
