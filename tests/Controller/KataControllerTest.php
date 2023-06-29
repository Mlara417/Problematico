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

    public $kata = '
    {
        id: "1",
        name: "John",
        question: "Test question",
        type: "easy"
    },
    {
        id: "2",
        name: "Mike",
        question: "Test2 question",
        type: "easy"
    },
    {
        id: "3",
        name: "Dan",
        question: "Test3 question",
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

    //TODO: fix this test
    /*     public function testListWithIds(): void
    {
        $expected = [
            [
                "id" => "1",
                "name" => "John",
                "question" => "Test question",
                "type" => "easy"
            ],
            [
                "id" => "2",
                "name" => "Mike",
                "question" => "Test2 question",
                "type" => "easy"
            ]
        ];

        $kataControllerMock = Mockery::mock('KataController');
        
        $kataControllerMock->shouldReceive('getCachedData')
            ->once()
            ->andReturn($this->kata);

        $kataControllerMock->shouldReceive('find')
            ->once()
            ->andReturn($expected);

        $response = static::createClient()->request('GET', '/kata?ids[]=1&ids[]=2', $this->options); 
        
        $this->assertJsonEquals($expected);
    } */

    public function testSync(): void
    {
        $kataControllerMock = Mockery::mock('KataController');

        $response = static::createClient()->request('GET', '/kata/sync', $this->options);

        $this->assertResponseIsSuccessful("Katas sync\'d successfully");
       /* $this->assertJsonContains(['@id' => '/']); */
    }
}
