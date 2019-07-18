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
        $params = '{
            "name": "Imię użytkownika",
            "lat": 8.2312,
            "lng": 12.723
        }';

        $response = $this->call('POST',
            '/place', [], [], [],
            ['CONTENT_TYPE' => 'application/json'],
            $params
        );

        $resp = json_decode($response->getContent(), 1);
        // threshold param is not send so there can be only one point in response
        $this->assertEquals(
            1, count($resp)
        );
    }


    public function testPlaceWithTreshold()
    {
        $params = '{
            "name": "Imię użytkownika",
            "lat": 8.2312,
            "lng": 12.723,
            "threshold": 2
        }';

        $response = $this->call('POST',
            '/place', [], [], [],
            ['CONTENT_TYPE' => 'application/json'],
            $params
        );

        $resp = json_decode($response->getContent(), 1);
        $this->assertEquals(
            2, count($resp)
        );
    }
}
