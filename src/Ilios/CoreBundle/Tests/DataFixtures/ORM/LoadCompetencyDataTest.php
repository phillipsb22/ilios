<?php

namespace Ilios\CoreBundle\Tests\DataFixtures\ORM;

use Ilios\CoreBundle\Entity\Manager\CompetencyManagerInterface;
use Ilios\CoreBundle\Entity\CompetencyInterface;

/**
 * Class LoadCompetencyDataTest
 * @package Ilios\CoreBundle\Tests\DataFixtures\ORM
 */
class LoadCompetencyDataTest extends AbstractDataFixtureTest
{
    /**
     * {@inheritdoc}
     */
    public function getDataFileName()
    {
        return 'competency.csv';
    }

    /**
     * {@inheritdoc}
     */
    public function getEntityManagerServiceKey()
    {
        return 'ilioscore.competency.manager';
    }

    /**
     * {@inheritdoc}
     */
    public function getFixtures()
    {
        return [
            'Ilios\CoreBundle\DataFixtures\ORM\LoadCompetencyData',
        ];
    }

    /**
     * @param array $data
     * @param CompetencyInterface $entity
     */
    protected function assertDataEquals(array $data, $entity)
    {
        // `competency_id`,`title`,`parent_competency_id`,`school_id`
        $this->assertEquals($data[0], $entity->getId());
        $this->assertEquals($data[1], $entity->getTitle());
        if (empty($data[2])) {
            $this->assertNull($entity->getParent());
        } else {
            $this->assertEquals($data[2], $entity->getParent()->getId());
        }
        $this->assertEquals($data[3], $entity->getSchool()->getId());
    }

    /**
     * @param array $data
     * @return CompetencyInterface
     * @override
     */
    protected function getEntity(array $data)
    {
        /**
         * @var CompetencyManagerInterface $em
         */
        $em = $this->em;
        return $em->findCompetencyBy(['id' => $data[0]]);
    }
}
