<?php

namespace Ilios\CoreBundle\Entity\DTO;

use Ilios\ApiBundle\Annotation as IS;

/**
 * Class SessionTypeDTO
 * Data transfer object for a session type
 * @package Ilios\CoreBundle\Entity\DTO
 *
 * @IS\DTO
 */
class SessionTypeDTO
{
    /**
     * @var integer
     * @IS\Expose
     * @IS\Type("integer")
     */
    public $id;

    /**
     * @var string
     *
     * @IS\Expose
     * @IS\Type("string")
    */
    public $title;

    /**
     * @var string
     *
     * @IS\Expose
     * @IS\Type("string")
    */
    public $calendarColor;

    /**
     * @var boolean
     *
     * @IS\Expose
     * @IS\Type("boolean")
    */
    public $assessment;

    /**
     * @var integer
     *
     * @IS\Expose
     * @IS\Type("entity")
     */
    public $assessmentOption;

    /**
     * @var integer
     *
     * @IS\Expose
     * @IS\Type("entity")
     */
    public $school;

    /**
     * @var array
     *
     * @IS\Expose
     * @IS\Type("array<string>")
     */
    public $aamcMethods;

    /**
     * @var array
     *
     * @IS\Expose
     * @IS\Type("array<string>")
     */
    public $sessions;

    /**
     * SessionTypeDTO constructor.
     * @param $id
     * @param $title
     * @param $calendarColor
     * @param $assessment
     */
    public function __construct($id, $title, $calendarColor, $assessment)
    {
        $this->id = $id;
        $this->title = $title;
        $this->calendarColor = $calendarColor;
        $this->assessment = $assessment;

        $this->aamcMethods = [];
        $this->sessions = [];
    }
}
