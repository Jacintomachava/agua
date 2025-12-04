<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mensalidade extends Model
{
    use HasFactory;

    protected $table = 'mensalidades';

    public function mes()
    {
        return $this->belongsTo(Mes::class, 'mes_id');
    }

    public function ano()
    {
        return $this->belongsTo(Ano::class, 'ano_id');
    }

    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }
}
