<?php

namespace App\Repositories;

use App\Models\Hash;

class HashRepository
{
    public function __construct(
        protected Hash $hashModel
    ) {
    }

    /**
     * Return all hashes in database
     *
     * @param integer $attemptsFilter
     * @return void
     */
    public function findAll(int $attemptsFilter = 0)
    {
        if ($attemptsFilter > 0) {
            return Hash::where('attempts', '<=', $attemptsFilter)->paginate(15, ['batch', 'id', 'input', 'key']);
        }

        return Hash::paginate(15, ['batch', 'id', 'input', 'key']);
    }

    /**
     * Find an hash by input
     *
     * @param string $stringInput
     * @return array
     */
    public function findPatternHashByInput(string $stringInput): array
    {
        return Hash::where('input', '=', $stringInput)
            ->get(['id', 'input', 'hash'])
            ->toArray();
    }

    /**
     * Store in database
     *
     * @param array $data
     * @return boolean
     */
    public function save(array $data): bool
    {
        $hash = new $this->hashModel;

        $hash->input = $data['input'];
        $hash->key = $data['key'];
        $hash->hash = $data['hash'];
        $hash->batch = $data['batch'];
        $hash->attempts = $data['attempts'];

        return $hash->save();
    }
}
