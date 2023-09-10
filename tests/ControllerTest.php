<?php

namespace App\Tests;

use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;

class ControllerTest extends TestCase
{
    public function test_get_users()
    {
        $client = new Client([
            'base_uri' => 'http://localhost:8000',
            'defaults' => [
                'exceptions' => true
            ]
        ]);

        $response = $client->get('/api/employees');

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertIsArray(json_decode($response->getBody(), true));
        $this->assertNotEmpty(json_decode($response->getBody(), true));
    }

    public function test_get_users_by_id()
    {
        $client = new Client([
            'base_uri' => 'http://localhost:8000',
            'defaults' => [
                'exceptions' => true
            ]
        ]);

        $response = $client->get('/api/employees/1');

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertIsArray(json_decode($response->getBody(), true));
        $this->assertNotEmpty(json_decode($response->getBody(), true));
    }

    public function test_fail_get_users_by_id()
    {
        $client = new Client([
            'base_uri' => 'http://localhost:8000',
            'defaults' => [
                'exceptions' => true
            ]
        ]);

        $response = $client->get('/api/employees/x');

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertIsArray(json_decode($response->getBody(), true));
        $this->assertEmpty(json_decode($response->getBody(), true));
    }
}
