<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Candidate extends Model
{
    use HasFactory;

    protected $fillable = [
        'chairman_name',
        'vice_chairman_name',
        'chairman_photo',
        'vice_chairman_photo',
        'vision',
        'mission',
    ];
}
