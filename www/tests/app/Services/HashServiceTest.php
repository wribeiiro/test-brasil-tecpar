<?php

use App\Services\HashService;
use App\Repositories\HashRepository;

class HashServiceTest extends TestCase
{
    private HashService $hashService;
    private HashRepository $repository;

    public function setUp(): void
    {
        parent::setUp();

        $this->repository = $this->createMock(HashRepository::class);

        $this->repository
            ->expects($this->any())
            ->method('findPatternHashByInput')
            ->with('my string to hash')
            ->willReturn([]);

        $this->hashService = new HashService($this->repository);
    }

    /**
     * @test
     */
    public function testShouldCalculateHashExpectException()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("It was not possible to find a hash pass a greater number of tries!");

        $this->hashService->setText('my string to hash');
        $this->hashService->setAttempts(1);
        $this->hashService->getHashedString();
    }

    /**
     * @test
     */
    public function testShouldCalculateAnHash()
    {
        $this->hashService->setText('my string to hash');
        $this->hashService->setAttempts(1000000);

        $result = $this->hashService->getHashedString();

        $this->assertIsArray($result);
        $this->assertCount(5, $result);
    }
}
