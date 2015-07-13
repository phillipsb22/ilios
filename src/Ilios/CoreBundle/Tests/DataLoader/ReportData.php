<?php

namespace Ilios\CoreBundle\Tests\DataLoader;

class ReportData extends AbstractDataLoader
{
    protected function getData()
    {
        $arr = array();

        $arr[] = array(
            'id' => 1,
            'title' => $this->faker->title(25),
            'subject' => $this->faker->title(25),
        );
        
        $arr[] = array(
            'id' => 2,
            'title' => $this->faker->title(25),
            'subject' => $this->faker->title(25),
        );

        return $arr;
    }

    public function create()
    {
        return array(
            'id' => 3,
            'title' => $this->faker->title(25),
            'subject' => $this->faker->title(25),
        );
    }

    public function createInvalid()
    {
        return [];
    }
}
