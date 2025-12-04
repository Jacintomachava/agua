<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Leitura extends Model
{
    use HasFactory;

    protected $table = 'leituras';

    protected $casts = [
        'data_leitura' => 'date',
    ];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }

    public function furoClienteContrato()
    {
        return $this->belongsTo(FuroClienteContrato::class, 'furo_cliente_contrato_id');
    }

    public function furo()
    {
        return $this->belongsTo(Furo::class, 'furo_id');
    }

    public function ano()
    {
        return $this->belongsTo(Ano::class, 'ano_id');
    }

    public function mes()
    {
        return $this->belongsTo(Mes::class, 'mes_id');
    }

    public function LeituraFeitoPor()
    {
        return $this->belongsTo(User::class, 'leitura_feita_por');
    }
}
