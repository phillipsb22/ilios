<?php

declare(strict_types=1);

namespace App\Entity\DTO;

use App\Attribute as IA;

/**
 * Class LearningMaterialUserRoleDTO
 * Data transfer object for a learning material user role
 */
#[IA\DTO('learningMaterialUserRoles')]
class LearningMaterialUserRoleDTO
{
    #[IA\Id]
    #[IA\Expose]
    #[IA\Type('integer')]
    public int $id;

    #[IA\Expose]
    #[IA\Type('string')]
    public string $title;

    public function __construct(int $id, string $title)
    {
        $this->id = $id;
        $this->title = $title;
    }
}
