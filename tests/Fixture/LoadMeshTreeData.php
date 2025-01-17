<?php

declare(strict_types=1);

namespace App\Tests\Fixture;

use App\Entity\MeshTree;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Bundle\FixturesBundle\ORMFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadMeshTreeData extends AbstractFixture implements
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
            ->get('App\Tests\DataLoader\MeshTreeData')
            ->getAll();
        foreach ($data as $arr) {
            $entity = new MeshTree();
            $entity->setId($arr['id']);
            $entity->setTreeNumber($arr['treeNumber']);
            $entity->setDescriptor($this->getReference('meshDescriptors' . $arr['descriptor']));
            $this->addReference('meshTrees' . $arr['treeNumber'], $entity);
            $manager->persist($entity);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            'App\Tests\Fixture\LoadMeshDescriptorData',
        ];
    }
}
