<?php

declare(strict_types=1);

namespace App\Tests\Fixture;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use App\Entity\Report;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Bundle\FixturesBundle\ORMFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadReportData extends AbstractFixture implements
    ORMFixtureInterface,
    ContainerAwareInterface,
    DependentFixtureInterface
{
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
        $data = $this->container
            ->get('App\Tests\DataLoader\ReportData')
            ->getAll();
        foreach ($data as $arr) {
            $entity = new Report();
            $entity->setId($arr['id']);
            $entity->setTitle($arr['title']);
            $entity->setSubject($arr['subject']);
            if (array_key_exists('prepositionalObject', $arr)) {
                $entity->setPrepositionalObject($arr['prepositionalObject']);
            }
            if (array_key_exists('prepositionalObjectTableRowId', $arr)) {
                $entity->setPrepositionalObjectTableRowId($arr['prepositionalObjectTableRowId']);
            }
            $entity->setUser($this->getReference('users' . $arr['user']));

            if (array_key_exists('school', $arr)) {
                $entity->setSchool($this->getReference('schools' . $arr['school']));
            }
            $manager->persist($entity);
            $this->addReference('reports' . $arr['id'], $entity);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return ['App\Tests\Fixture\LoadUserData'];
        return ['App\Tests\Fixture\LoadSchoolData'];
    }
}
