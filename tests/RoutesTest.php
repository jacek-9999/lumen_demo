<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class ExampleTest extends TestCase
{
    public function testPlaces()
    {
        $this->post('/places');

        $this->assertEquals(
            'ABC', $this->response->getContent()
        );
    }

    public function testPlace()
    {
        $this->post('/place');

        $this->assertEquals(
            'ZXC', $this->response->getContent()
        );
    }
}
