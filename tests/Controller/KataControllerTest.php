<?php

namespace App\Tests\Controller;

use Mockery;
use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;

/**
 * @covers KataController
 */
class KataControllerTest extends ApiTestCase
{
    //using postman mock server to mock the api
    public $options = [
        'auth_basic' => null,
        'auth_bearer' => null,
        'query' => [],
        'headers' => ['accept' => ['application/ld+json']],
        'body' => '',
        'json' => null,
        'base_uri' => 'https://429690c9-6f2b-4fa8-89c5-fcb50a971d0b.mock.pstmn.io',
        'extra' => [],
    ];

    public $kata = '{
        id: "1",
        name: "John",
        question: "Test question",
        type: "easy"
        },
        {
            id: "2",
            name: "Mike",
            question: "Test question",
            type: "easy"
        }';

    public $vfs;
    public $easyKatas;
    public $kataController;

    public function testList(): void
    {
        $kataControllerMock = Mockery::mock('KataController');
        
        $kataControllerMock->shouldReceive('getCachedData')
            ->once()
            ->andReturn($this->kata);

        $response = static::createClient()->request('GET', '/kata', $this->options); 
        
        $this->assertResponseIsSuccessful();
        
    }

    public function testSync(): void
    {
        $kataControllerMock = Mockery::mock('KataController');

        $response = static::createClient()->request('GET', '/kata/sync', $this->options); 
        
        $this->assertResponseIsSuccessful("Katas sync\'d successfully");
        /* $this->assertJsonContains(['@id' => '/']); */
    }

}
