<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserFuro extends Model
{
    use HasFactory;

    protected $table = 'user_furo';

    protected $fillable = [
        'user_id',
        'furo_id',
        'updated_at',
        'created_at',
    ];

    public function furo()
    {
        return $this->belongsTo(Furo::class, 'furo_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
