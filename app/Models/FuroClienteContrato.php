<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FuroClienteContrato extends Model
{
    use HasFactory;

    protected $table = 'furo_cliente_contrato';

    public function distrito()
    {
        return $this->belongsTo(Distrito::class, 'distrito_id');
    }

    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }

    public function furo()
    {
        return $this->belongsTo(Furo::class, 'furo_id');
    }

    public function contrato()
    {
        return $this->belongsTo(Contrato::class, 'contrato_id');
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    public function ano()
    {
        return $this->belongsTo(Ano::class, 'ano_id');
    }

    public function mes()
    {
        return $this->belongsTo(Mes::class, 'mes_id');
    }
}
