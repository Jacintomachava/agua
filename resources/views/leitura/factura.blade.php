
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Factura Agua</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <style>
    body {
        font-family: DejaVu Sans, sans-serif;
        font-size: 10px; /* ideal para impressora térmica */
        margin: 0;
        padding: 2%;
        width: 48mm;
    }

    .recibo {
        width: 100%;
        padding: 5px;
    }
    .table-bordered {
        border: 1px solid #dee2e6;
    }
    .table-bordered td, .table-bordered th {
        border: 1px solid #dee2e6;
    }

    table {
        font-size: 10px;
        width: 100%;
    }

    hr {
        border: 0;
        border-top: 1px dashed #000;
    }
</style>

</head>
<body>

<div class="header">
    <center><img src="{{public_path('images/logotipo.png')}}" width="90" height="90">
    <b>{{ strtoupper($empresa->nome) }}</b><br>
    <b>{{ $empresa->endereco }}</b><br>
    <b>NUIT: {{ $empresa->nuit }}</b></center>
</div>
<div class="content">

    @php
        use Illuminate\Support\Str;
    @endphp

    <br>
    <p style="text-align: right;">
         Factura nº:<b><br> #{{$fatura->numero_factura}}</b>
    </p>

    <b>Cliente</b>:<br> <i>{{$cliente->cliente->nome}}</i>
    <hr>
     Morada: {{$cliente->bairro}}<br>
     Quarteirao: {{$cliente->quarteirao}}<br>
     Casa: {{$cliente->casa}}<br>
    <hr>

    
    <table class="table table-bordered" style="font-size: 9pt">
        <tr>
            <th colspan="2" style="background-color:#808387; color: white "><center>Factura</center></th>
        </tr>
        <tr>
            <td>MES</td>
            <td>{{$leitura->mes->nome}} - {{$leitura->ano->ano}}</td>
        </tr>
        <tr>
            <td>L. Anterior</td>
            <td>{{$leitura->valor_leitura - $leitura->consumo}}m3</td>
        </tr>
        <tr>
            <td>L. Actual</td>
            <td>{{$leitura->valor_leitura}}m3</td>
        </tr>
        <tr>
            <td>Consumo</td>
            <td>{{$leitura->consumo}}m3</td>
        </tr>

        <tr>
            <td>Valor</td>
            <td>{{$leitura->valor_a_pagar}}MT</td>
        </tr>
        <tr>
            <td>Divida</td>
            <td>{{$cliente->divida}}MT</td>
        </tr>
        <tr>
            <td>Total</td>
            <td>{{$leitura->valor_a_pagar+$cliente->divida}}MT</td>
        </tr>
    </table>

    <br>
    <div>
       <b style="text-align: center;">NOTA</b>
    </div>
    
    <p style="text-align: justify; font-size: 8pt;">
        Nota: O cliente é sujeito a uma MULTA de 25% após o último dia do pagamento (Artigo 2-e do contracto) e o fornecedor poderá interromper o fornecimento de água sem aviso prévio.<br><br>
    </p>
</div>


</body>
</html>
