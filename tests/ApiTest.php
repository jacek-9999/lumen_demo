<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class ApiTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate:fresh');
        $this->artisan('db:seed');
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

    public function testCreatingPlaces()
    {
        $this->assertEquals(20, \App\Place::all()->count());
        $response = $this->call('POST',
            '/places', [], [], [],
            ['CONTENT_TYPE' => 'application/json'],
            $this->getPlacesList()
        );
        $this->assertEquals(
            '{"21":"Lokalizacja 1","22":"Lokalizacja 2","23":"Lokalizacja 3","24":"Lokalizacja 4","25":"Lokalizacja 5","26":"Lokalizacja 6"}',
            $this->response->getContent()
        );

        // additional test for reading new added place
        $params = '{
            "name": "Imię użytkownika",
            "lat": 25.2,
            "lng": 11.2,
            "threshold": 1
        }';
        $response = $this->call('POST',
            '/place', [], [], [],
            ['CONTENT_TYPE' => 'application/json'],
            $params
        );
        $this->assertContains('Lokalizacja 3', $response->getContent());
        $this->assertEquals(26, \App\Place::all()->count());
    }


    private function getPlacesList()
    {
        return
        '[
            {
            "name": "Lokalizacja 1",
            "lat": 21.132312,
            "lng": 21.132312
            },
            {
            "name": "Lokalizacja 2",
            "lat": 42.03452,
            "lng": 31.3128
            },
            {
            "name": "Lokalizacja 3",
            "lat": 25.23161,
            "lng": 11.23161
            },
            {
            "name": "Lokalizacja 4",
            "lat": 9.241213,
            "lng": 18.51271
            },
            {
            "name": "Lokalizacja 5",
            "lat": 13.35123,
            "lng": 23.7951
            },
            {
            "name": "Lokalizacja 6",
            "lat": 15.4124,
            "lng": 7.6123
            }
        ]';
    }
}
