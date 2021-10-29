<?php

use App\Models\Hash;
use App\Repositories\HashRepository;
use Laravel\Lumen\Testing\DatabaseMigrations;

class HashRepositoryTest extends TestCase
{
    use DatabaseMigrations;

    private HashRepository $repository;

    public function setUp(): void
    {
        parent::setUp();
        $this->repository = new HashRepository(new Hash());
    }

    /* 
     * @test 
     */
    public function testShouldReturnAnListOfHashes()
    {
        $this->assertIsObject($this->repository->findAll());
    }

    /**
     * @test
     */
    public function testShouldReturnHashByInput()
    {
        $this->assertIsArray($this->repository->findPatternHashByInput('123'));
    }

    /**
     * @test
     */
    public function testShouldStoreATextInDatabase()
    {
        $payload['input'] = 'payload';
        $payload['key'] = uniqid();
        $payload['hash'] = '0000' . md5(uniqid());
        $payload['batch'] = date('now');
        $payload['attempts'] = rand(1, 1000);

        $this->assertTrue($this->repository->save($payload));
    }
}
