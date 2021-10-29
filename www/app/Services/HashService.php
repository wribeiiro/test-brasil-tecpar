<?php

namespace App\Services;

use App\Repositories\HashRepository;
use Carbon\Carbon;

class HashService
{
    const PREFIX_HASH = '0000';

    private int $currentAttempt;
    private int $attempts;
    private string $text;

    public function __construct(
        private HashRepository $hashRepository
    ) {
        $this->currentAttempt = 0;
        $this->attempts = 0;
        $this->text = "";
    }

    /**
     * Create the hash based without PREFIX_HASH and without HashKey
     * and returns an MD5 hash with found key
     * 
     * @return array
     */
    private function calculateHash(): array
    {
        $hashInformation = [];

        while ($this->currentAttempt < $this->getAttempts()) {
            $hashKey = $this->createHashKey();
            $hash = md5($this->getText() . $hashKey);

            $hashInformation = [
                'key' => $hashKey,
                'hash' => $hash
            ];

            if ($this->validateHash($hash)) {
                return $hashInformation;
            }

            $this->currentAttempt++;
        }

        throw new \Exception("It was not possible to find a hash pass a greater number of tries!");
    }

    /**
     * Create an random random key
     *
     * @return self
     */
    private function createHashKey(): string
    {
        return substr(str_shuffle(base64_encode(uniqid())), 0, 8);
    }

    /**
     * Check if the hash is valid
     *
     * @param string $hash
     * @return boolean
     */
    private function validateHash(string $hash): bool
    {
        return (substr($hash, 0, 4) === self::PREFIX_HASH);
    }

    /**
     * Get the text to hash
     *
     * @return string
     */
    private function getText(): string
    {
        return $this->text;
    }

    /**
     * Set the text
     *
     * @return self
     */
    public function setText(string $text): self
    {
        $this->text = $text;
        return $this;
    }

    /**
     * Get the number of attempts
     *
     * @return int
     */
    public function getAttempts(): int
    {
        return $this->attempts;
    }

    /**
     * Set the number of attempts
     *
     * @param integer $attempts
     * @return self
     */
    public function setAttempts(int $attempts = 1000000): self
    {
        $this->attempts = $attempts;
        return $this;
    }

    /**
     * Return a array with information of hashed string
     * 
     * - stringInput - Clean string received
     * - hashKey - The key generated
     * - hashString - The hash string generated
     * - attempts - The number of attempts to generated hash
     * 
     * @return array
     */
    public function getHashedString(): array
    {
        $hashInformation = $this->calculateHash();

        $hashData = [
            'input' => $this->getText(),
            'key' => $hashInformation['key'],
            'hash' => $hashInformation['hash'],
            'batch' => Carbon::now(),
            'attempts' => $this->currentAttempt
        ];

        $hashByInput = $this->hashRepository->findPatternHashByInput($this->getText());

        if (!$hashByInput) {
            $this->hashRepository->save($hashData);
        }

        return $hashData;
    }

    /**
     * Find All
     *
     * @param integer $attemptsFilter
     * @return void
     */
    public function findAll(int $attemptsFilter = 0)
    {
        return $this->hashRepository->findAll($attemptsFilter);
    }
}
