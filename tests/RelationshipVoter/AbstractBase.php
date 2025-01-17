<?php

declare(strict_types=1);

namespace App\Tests\RelationshipVoter;

use App\Classes\SessionUserInterface;
use App\RelationshipVoter\AbstractVoter;
use App\Tests\TestCase;
use Mockery as m;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;

class AbstractBase extends TestCase
{
    /** @var  m\MockInterface */
    protected $permissionChecker;

    /** @var  VoterInterface */
    protected $voter;

    /**
     * Remove all mock objects
     */
    public function tearDown(): void
    {
        parent::tearDown();
        unset($this->voter);
        unset($this->permissionChecker);
    }

    /**
     * Creates a mock token that has the given user.
     * @param SessionUserInterface $sessionUser A (mock) user entity.
     */
    protected function createMockTokenWithSessionUser(SessionUserInterface $sessionUser = null): TokenInterface
    {
        $mock = m::mock(TokenInterface::class);
        $mock->shouldReceive('getUser')->andReturn($sessionUser);
        return $mock;
    }

    /**
     * Creates a mock token with a non-root user
     */
    protected function createMockTokenWithNonRootSessionUser(): TokenInterface
    {
        $sessionUser = m::mock(SessionUserInterface::class);
        $sessionUser->shouldReceive('isRoot')->andReturn(false);
        return $this->createMockTokenWithSessionUser($sessionUser);
    }

    /**
     * Creates a mock token with a user that's performs non-learner functions.
     */
    protected function createMockTokenWithSessionUserPerformingNonLearnerFunction(): TokenInterface
    {
        $sessionUser = m::mock(SessionUserInterface::class);
        $sessionUser->shouldReceive('isRoot')->andReturn(false);
        $sessionUser->shouldReceive('performsNonLearnerFunction')->andReturn(true);
        return $this->createMockTokenWithSessionUser($sessionUser);
    }

    /**
     * Creates a mock token with a user that's doesn't perform non-learner functions.
     */
    protected function createMockTokenWithSessionUserPerformingOnlyLearnerFunction(): TokenInterface
    {
        $sessionUser = m::mock(SessionUserInterface::class);
        $sessionUser->shouldReceive('isRoot')->andReturn(false);
        $sessionUser->shouldReceive('performsNonLearnerFunction')->andReturn(false);
        return $this->createMockTokenWithSessionUser($sessionUser);
    }

    /**
     * Creates a mock token with a root user
     */
    protected function createMockTokenWithRootSessionUser(): TokenInterface
    {
        $sessionUser = m::mock(SessionUserInterface::class);
        $sessionUser->shouldReceive('isRoot')->andReturn(true);
        return $this->createMockTokenWithSessionUser($sessionUser);
    }

    /**
     * Check that "root" users are granted access in all votes on the given entity.
     * @param m\MockInterface $mockEntity
     * @param array $entityAttrs
     */
    protected function checkRootEntityAccess(
        $mockEntity,
        $entityAttrs = [AbstractVoter::VIEW, AbstractVoter::DELETE, AbstractVoter::CREATE, AbstractVoter::EDIT]
    ) {
        $sessionUser = m::mock(SessionUserInterface::class);
        $sessionUser->shouldReceive('isRoot')->andReturn(true);
        $token = $this->createMockTokenWithSessionUser($sessionUser);
        foreach ($entityAttrs as $attr) {
            $response = $this->voter->vote($token, $mockEntity, [$attr]);
            $this->assertEquals(VoterInterface::ACCESS_GRANTED, $response, "${attr} allowed");
        }
    }

    /**
     * Check that "root" users are granted access in all votes on the given DTO.
     * @param $dtoClass
     */
    protected function checkRootDTOAccess($dtoClass)
    {
        $sessionUser = m::mock(SessionUserInterface::class);
        $sessionUser->shouldReceive('isRoot')->andReturn(true);
        $token = $this->createMockTokenWithSessionUser($sessionUser);
        $response = $this->voter->vote($token, m::mock($dtoClass), [AbstractVoter::VIEW]);
        $this->assertEquals(VoterInterface::ACCESS_GRANTED, $response, "DTO View allowed");
    }
}
