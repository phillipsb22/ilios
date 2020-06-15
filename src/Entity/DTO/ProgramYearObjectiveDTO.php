<?php

declare(strict_types=1);

namespace App\Entity\DTO;

use App\Annotation as IS;

/**
 * Class ProgramYearObjectiveDTO
 *
 * @IS\DTO("programYearObjectives")
 */
class ProgramYearObjectiveDTO
{
    /**
     * @var int
     * @IS\Id
     * @IS\Expose
     * @IS\Type("integer")
     */
    public $id;

    /**
     * @var int
     *
     * @IS\Expose
     * @IS\Related("objectives")
     * @IS\Type("integer")
     */
    public $objective;

    /**
     * @var int
     * @IS\Expose
     * @IS\Type("integer")
     *
     */
    public $position;

    /**
     * @var array
     *
     * @IS\Expose
     * @IS\Related
     * @IS\Type("array<string>")
     */
    public $terms;

    /**
     * @var int
     *
     * @IS\Expose
     * @IS\Related("programYears")
     * @IS\Type("integer")
     */
    public $programYear;

    /**
     * Needed for Voting, not exposed in the API
     * @var bool
     *
     * @IS\Type("boolean")
     */
    public $programYearIsLocked;

    /**
     * Needed for Voting, not exposed in the API
     * @var bool
     *
     * @IS\Type("boolean")
     */
    public $programYearIsArchived;


    /**
     * Constructor
     * @param int $id
     * @param int $position
     */
    public function __construct($id, $position)
    {
        $this->id = $id;
        $this->position = $position;
        $this->terms = [];
    }
}