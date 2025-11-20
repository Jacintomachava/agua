<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tubagem extends Model
{
    use HasFactory;

    protected $table = 'tubagem';

    protected $fillable = [
        'latitude',
        'longitude',
        'empresa_id',
        'ordem',
        'updated_at',
        'created_at',
    ];
}
