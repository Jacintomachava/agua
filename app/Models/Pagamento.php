<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pagamento extends Model
{
    use HasFactory;

    protected $table = 'pagamentos';

    public function fatura()
    {
        return $this->belongsTo(Fatura::class, 'factura_id');
    }

    public function tipo()
    {
        return $this->belongsTo(TipoPagamento::class, 'tipo_pagamento_id');
    }

    public function forma()
    {
        return $this->belongsTo(FormaPagamento::class, 'forma_pagamento_id');
    }

    public function banco()
    {
        return $this->belongsTo(BancoCarteira::class, 'tipo_banco');
    }

    public function leitura()
    {
        return $this->belongsTo(Leitura::class, 'leitura_id');
    }

}
