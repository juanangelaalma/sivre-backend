<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voter extends Model
{
    use HasFactory;

    protected $fillable = [
        'username',
        'password',
    ];

    public function vote()
    {
        return $this->hasOne(Vote::class);
    }
}
