<?php

namespace ContactDirectory\PersonBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class CRUDControllerTest extends WebTestCase {

    public function testCreatePerson(){

        $client = static::createClient();
        $client->request('POST', '/api/v1/person/');
        $this->assertJsonResponse($client->getResponse(), 200);
    }

    public function testUpdatePerson(){

        $client = static::createClient();
        $client->request('PUT', '/api/v1/person/1');
        $this->assertJsonResponse($client->getResponse(), 200);
    }

    public function  testGetPeople(){

        $client = static::createClient();
        $client->request('GET', '/api/v1/person/');
        $this->assertJsonResponse($client->getResponse(), 200);
    }

    public function  testGetPerson(){

        $client = static::createClient();
        $client->request('GET', '/api/v1/person/1');
        $this->assertJsonResponse($client->getResponse(), 200);
    }

    public function testDeletePerson(){

        $client = static::createClient();
        $client->request('DELETE', '/api/v1/person/1');
        $this->assertJsonResponse($client->getResponse(), 200);
    }

    protected function assertJsonResponse(Response $response, $statusCode = 200, $contentType = 'application/json')
    {
        $this->assertEquals(
            $statusCode, $response->getStatusCode(),
            $response->getContent()
        );
        $this->assertTrue(
            $response->headers->contains('Content-Type', $contentType),
            $response->headers
        );
    }
} 