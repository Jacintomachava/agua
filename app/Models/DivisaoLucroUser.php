<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DivisaoLucroUser extends Model
{
    use HasFactory;

    protected $table = 'divisao_lucro_user';

    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function mesFactura()
    {
        return $this->belongsTo(Mes::class, 'mes_factura_id');
    }

    public function mesPagamento()
    {
        return $this->belongsTo(Mes::class, 'mes_pagamento_id');
    }
}
