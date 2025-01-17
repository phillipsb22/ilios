<?php

declare(strict_types=1);

namespace App\Tests\Fixture;

use App\Entity\AamcMethod;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Bundle\FixturesBundle\ORMFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadAamcMethodData extends AbstractFixture implements
    ORMFixtureInterface,
    ContainerAwareInterface
{
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
        $data = $this->container
            ->get('App\Tests\DataLoader\AamcMethodData')
            ->getAll();
        foreach ($data as $arr) {
            $entity = new AamcMethod();
            $entity->setId($arr['id']);
            $entity->setDescription($arr['description']);
            $entity->setActive($arr['active']);

            $manager->persist($entity);
            $this->addReference('aamcMethods' . $arr['id'], $entity);
        }

        $manager->flush();
    }
}
