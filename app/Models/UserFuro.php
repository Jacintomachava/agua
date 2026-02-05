<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserFuro extends Model
{
    use HasFactory;

    protected $table = 'user_furo';

    public function furo()
    {
        return $this->belongsTo(Furo::class, 'furo_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
