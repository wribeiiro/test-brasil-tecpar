<?php

use Illuminate\Http\Response;
use Laravel\Lumen\Testing\DatabaseMigrations;

class HashControllerTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();
    }

    /**
     * @test
     */
    public function testShouldReturnHashes()
    {
        $response = $this->get('api/v1/hashes');
        $response->seeJsonStructure(['data', 'status']);

        $this->assertEquals(Response::HTTP_OK, $this->response->status());
    }

    /**
     * @test
     */
    public function testShouldCreateHash()
    {
        $response = $this->post('api/v1/hashes/store', [
            'text' => uniqid(),
            'attempts' => 1
        ]);

        $response->seeJsonStructure(['data', 'status']);
        $this->assertEquals(Response::HTTP_CREATED, $this->response->status());
    }

    /**
     * @test
     */
    public function testShouldReturnTooManyAttempts()
    {
        for ($attempt = 0; $attempt < 12; $attempt++) {
            $response = $this->get('api/v1/hashes');
        }

        $response->seeJsonStructure(['error']);
        $this->assertEquals(Response::HTTP_TOO_MANY_REQUESTS, $this->response->status());
    }
}
