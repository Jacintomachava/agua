<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Factura</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }

        .container {
            width: 100%;
            padding: 10px;
        }

        .header-table, 
        .data-table {
            width: 100%;
            border-collapse: collapse;
        }

        .data-table td, 
        .data-table th {
            border: 1px solid #000;
            padding: 5px;
        }

        .bold {
            font-weight: bold;
        }

        .title {
            font-size: 18px;
            text-align: center;
            margin-bottom: 5px;
        }

        .logo {
            width: 120px;
        }

        .note {
            font-size: 11px;
            margin-top: 10px;
        }

        .payment {
            margin-top: 10px;
            font-size: 12px;
        }
    </style>
    <style>
    .cut-center {
        border: none;
        border-top: 1px dashed #000;
        position: relative;
        margin: 20px 0;
    }

    .cut-center:before {
        content: "✂";
        position: absolute;
        top: -10px;
        left: 50%;
        transform: translateX(-50%);
        background: #fff;
        padding: 0 5px;
    }
    </style>
</head>

<body>

<div class="container">


    <!-- DADOS DO CLIENTE -->
    <br>
    <table class="data-table">
        <tr>
            <td colspan="2" style="text-align: center;">
                 @if(Auth::user()->empresa->logotipo==null)
                    <img src="{{public_path('images/logotipo.png')}}" width="90" height="70">
                 @else
                    <img src="{{public_path('/logotipo/'.Auth::user()->empresa->logotipo)}}" width="90" height="70">
                 @endif
            </td>
            <td colspan="3" style="text-align: center;">
                <div class="bold">{{ strtoupper($empresa->nome) }}</div>
                <div>{{ $empresa->endereco }}</div>
                <div>NUIT: {{ $empresa->nuit }}</div>
            </td>
            <td colspan="2" style="text-align: right;">
                <div class="bold">FACTURA Nº: {{$leitura->numero_factura}}</div>
                <div>Mês {{$leitura->mes->nome}}</div>
            </td>
        </tr>
        <tr>
            <td>Nome</td>
            <th colspan="6" style="text-align: center;">{{strtoupper($leitura->furoClienteContrato->cliente->nome)}}</th>
        </tr>

        <tr>
            <td>Morada</td>
            <td>Quarteirao</td>
            <td>{{$leitura->furoClienteContrato->cliente->quarteirao}}</td>
            <td>Casa</td>
            <td>{{$leitura->furoClienteContrato->cliente->casa}}</td>
            <td>Cliente</td>
            <td>{{$leitura->furoClienteContrato->codigo}}</td>
        </tr>

        <tr>
            <td>Mes</td>
            <td colspan="2" style="text-align: center;">{{$leitura->mes->nome}}</td>
            <td colspan="2" style="text-align: center;">Prazo Pag.</td>
            <td colspan="2" style="text-align: center;">{{\Carbon\Carbon::parse($leitura->prazo_pagamento)->format('d-m-Y') }}</td>
        </tr>

        <tr>
            <td>L. Anterior</td>
            <td colspan="2" style="text-align: center;">{{$leitura->valor_leitura-$leitura->consumo}}m&sup3;</td>
            <td colspan="2" style="text-align: center;">Consumo</td>
            <td colspan="2" style="text-align: center;">{{$leitura->consumo}}m&sup3;</td>
        </tr>

        <tr>
            <td>L. Actual</td>
            <td colspan="2" style="text-align: center;">{{$leitura->valor_leitura}}m&sup3;</td>
            <td colspan="2" style="text-align: center;">Divida</td>
            <td colspan="2" style="text-align: center;">{{$leitura->divida_anterior}}MT</td>
        </tr>

        <tr>
            <td>Valor/m&sup3;</td>
            <td colspan="2" style="text-align: center;">{{$leitura->furoClienteContrato->contrato->valor}}MT</td>
            <td colspan="2" style="text-align: center;">Mes Corrente</td>
            <td colspan="2" style="text-align: center;">{{$leitura->valor_a_pagar}}MT</td>
        </tr>

        <tr>
            <td>Data Leitura</td>
            <td colspan="2" style="text-align: center;">{{ \Carbon\Carbon::parse($leitura->data_leitura)->format('d-m-Y') }}</td>
            <th colspan="2" style="text-align: center;">Total a Pagar</th>
            <th colspan="2" style="text-align: center;">{{$leitura->valor_a_pagar+$leitura->divida_anterior}}MT</th>
        </tr>

        <tr>
            <td colspan="7" style="text-align: justfy;">
                Nota: O cliente é sujeito a uma MULTA de 25% após o último dia do pagamento  e o fornecedor poderá interromper o fornecimento de água sem aviso prévio.
            </td>
        </tr>
        <tr>
            <th colspan="7" style="text-align: center;">
                 Modalidades de pagamento:
            </th>
        </tr>
        <tr>
            <td colspan="7" style="text-align: center;">
                 BIM - 53130781;  BCI NIB: 000800006340814110195;  M-pesa: 84 586 8327;  E-mola: 87 586 8324
            </td>
        </tr>
        <tr>
            <td colspan="7" style="text-align: center;">
                 <b>NB: <i>Após o pagamento por meio bancário, o cliente DEVE enviar a mensagem comprovativa.</i></b>
            </td>
        </tr>
        
    </table>
    <br>
        <hr class="cut-center">
    <br>

</div>

</body>
</html>
