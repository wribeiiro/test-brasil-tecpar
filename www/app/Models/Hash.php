<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hash extends Model
{
    /**
     * @var string
     */
    protected $table = 'hashes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'input', 'key', 'hash', 'attempts', 'batch'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'created_at',
        'updated_at'
    ];
}
