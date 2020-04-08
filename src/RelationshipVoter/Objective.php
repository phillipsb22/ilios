<?php

declare(strict_types=1);

namespace App\RelationshipVoter;

use App\Entity\CourseInterface;
use App\Entity\ObjectiveInterface;
use App\Entity\ProgramYearInterface;
use App\Entity\SessionInterface;
use App\Classes\SessionUserInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

/**
 * Class Objective
 */
class Objective extends AbstractVoter
{
    /**
     * {@inheritdoc}
     */
    protected function supports($attribute, $subject)
    {
        return $subject instanceof ObjectiveInterface && in_array($attribute, array(
                self::VIEW, self::CREATE, self::EDIT, self::DELETE
            ));
    }

    /**
     * @param string $attribute
     * @param ObjectiveInterface $objective
     * @param TokenInterface $token
     * @return bool
     */
    protected function voteOnAttribute($attribute, $objective, TokenInterface $token)
    {
        $user = $token->getUser();
        if (!$user instanceof SessionUserInterface) {
            return false;
        }

        if ($user->isRoot()) {
            return true;
        }

        switch ($attribute) {
            case self::VIEW:
                return true;
                break;
            case self::CREATE:
            case self::EDIT:
            case self::DELETE:
                if (!$objective->getCourseObjectives()->isEmpty()) { // got courses? if so, it's a course objective.
                    return $this->isCreateEditDeleteGrantedForCourseObjective($objective, $user);
                } elseif (!$objective->getSessionObjectives()->isEmpty()) { // and so on..
                    return $this->isCreateEditDeleteGrantedForSessionObjective($objective, $user);
                } elseif (!$objective->getProgramYearObjectives()->isEmpty()) { // and so on ..
                    return $this->isCreateEditDeleteGrantedForProgramYearObjective($objective, $user);
                }
                break;
        }

        return false;
    }

    /**
     * @param ObjectiveInterface $objective
     * @param SessionUserInterface $user
     * @return bool
     */
    protected function isCreateEditDeleteGrantedForProgramYearObjective(
        ObjectiveInterface $objective,
        SessionUserInterface $user
    ) {

        /* @var ProgramYearInterface $programYear */
        $programYear = $objective->getProgramYearObjectives()->first()->getProgramYear();
        return $this->permissionChecker->canUpdateProgramYear($user, $programYear);
    }

    /**
     * @param ObjectiveInterface $objective
     * @param SessionUserInterface $user
     * @return bool
     */
    protected function isCreateEditDeleteGrantedForSessionObjective(
        ObjectiveInterface $objective,
        SessionUserInterface $user
    ) {
        /* @var SessionInterface $session */
        $session = $objective->getSessionObjectives()->first()->getSession();

        return $this->permissionChecker->canUpdateSession($user, $session);
    }

    /**
     * @param ObjectiveInterface $objective
     * @param SessionUserInterface $user
     * @return bool
     */
    protected function isCreateEditDeleteGrantedForCourseObjective($objective, $user)
    {
        /* @var CourseInterface $course */
        $course = $objective->getCourseObjectives()->first()->getCourse();
        return $this->permissionChecker->canUpdateCourse($user, $course);
    }
}
