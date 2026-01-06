
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Extracto Leitura</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            font-size: 9pt;
        }
        .header, .footer {
            text-align: center;
        }
        .content {
            margin: 20px 0;
        }
        .section-title {
            font-weight: bold;
            margin-top: 20px;
        }
        .table {
            width: 100%;
            border-collapse: collapse;

        }
        .table, .table th, .table td {
            border: 1px solid #000;
        }
        .table th, .table td {
            padding: 3px;
            vertical-align: top;
            border-top: 1px solid #dee2e6;
        }
        .note {
            margin-top: 20px;
            font-size: 0.9em;
            color: #555;
        }
        .flex-container {
            display: flex;
            justify-content: space-between; /* Alinha os elementos ao máximo nas extremidades */
            align-items: center; /* Centraliza verticalmente os elementos */
        }
        .right-align {
            text-align: right; /* Alinha o texto à direita */
        }
        .container {
            width: 100%;
            padding-right: 15px;
            padding-left: 15px;
            margin-right: auto;
            margin-left: auto;
        }
        .row {
            display: flex;
            flex-wrap: wrap;
            margin-right: -15px;
            margin-left: -15px;
        }
        .col-md-4 {
            position: relative;
            width: 100%;
            padding-right: 15px;
            padding-left: 15px;
            flex: 0 0 33.333333%;
            max-width: 33.333333%;
        }
        .table-bordered {
            border: 1px solid #dee2e6;
        }
        .table-bordered td, .table-bordered th {
            border: 1px solid #dee2e6;
        }
        .right-align1 {
            float: right;
        }
        .watermark {
            position: fixed;
            top: 70%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-30deg);
            z-index: 0;
            text-align: center;
            opacity: 0.9;
            font-size: 40px;
            color: green;
            pointer-events: none;
        }
        /* Footer */
        .footer {
            position: fixed;
            bottom: 20px;
            left: 0;
            right: 0;
            width: 100%;
            text-align: center;
            font-size: 12px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
  
    </style>
</head>
<body>

<div class="header">
    <img alt="{{$empresa->nome}}" 
        src="{{ public_path('/logotipo/'.$empresa->logotipo) }}" 
        width="100" height="90"><br>
    <h5>{{ strtoupper($empresa->nome) }}</h5>
    <h5>Extracto de cliente </h5>
</div>
<div class="content">

    @php
        use Illuminate\Support\Str;
    @endphp

    <p style="text-align: right;">
        Multa:<b> {{$cliente->contrato->multa}}%</b><br>
        Prazo:<b> Dia {{$cliente->contrato->prazo_pagamento}}</b><br>
        Taxa: <b>{{Str::upper($cliente->contrato->valor)}}MT/m&sup3;</b>
    </p>

    <b>DADOS CLIENTE</b> 
    <hr>
     Cliente: <b>{{ Str::upper($cliente->cliente->nome) }} </b> 
     <br>
     Bairro: <b>{{$cliente->bairro}}</b>  <span style="margin-left: 10%">Quarteirao: </span><b>{{$cliente->cliente->quarteirao}} </b>
     <span style="margin-left: 10%">Casa: </span><b>{{$cliente->cliente->casa}} </b><br>
     <span>Contracto: </span><b>{{$cliente->contrato->valor_contrato}} </b>
     <span style="margin-left: 10%">valor Pago: </span><b>{{$cliente->valor_pago}} </b>
     <span style="margin-left: 10%">Divida: </span><b>{{$cliente->saldo}} </b>
    <hr> 
    <table class="table table-bordered" style="font-size: 7pt; ">
        <tr style="background: #0a0a0cff; color: #e8ecf3ff">
            <th>Mês</th>
            <th>Leitura</th>
            <th>Consumo</th>
            <th>Valor</th>
            <th>Divida</th>
            <th>Multa</th>
            <th>Saldo</th>
            <th>Total</th>
            <th>Prazo</th>
            <th>Estado</th>
            <th>valor Pag.</th>
            <th>Data Pag.</th>
        </tr>
     @foreach($pagamentos as $pagamento)
        <tr style="text-align: center">
            <td>{{$pagamento->leitura->mes->nome}}-{{$leitura->ano->ano}} </td>
            <td>{{$pagamento->leitura->valor_leitura}}m&sup3;</td>
            <td>{{$pagamento->leitura->consumo}}m&sup3;</td>
            <td>{{$pagamento->leitura->valor_a_pagar}}</td>
            <td>{{$pagamento->leitura->saldo}}</td>
            <td>{{$pagamento->leitura->saldo_usado}}</td>
            <td>{{$pagamento->leitura->multa}}</td>
            <td>{{$pagamento->leitura->valor_a_pagar}}</td>
            <td>{{ \Carbon\Carbon::parse($pagamento->leitura->prazo_pagamento)->format('d-M-Y') }}</td>
            <td>
                @if($pagamento->leitura->estado_pagamento=='Pago')
                    <span style="color: #07b664ff">Pago</span>
                @elseif($pagamento->leitura->estado_pagamento=='Parcial')    
                    <span style="color: #b7c40cff">Parcial</span>
                @else
                    <span style="color: #dd1313ff">Pendente </span>
                @endif
            </td>
            <td>
                
                @if($pagamento->leitura->estado_pagamento=='Pendente')
                    ...
                @else
                   {{$pagamento->leitura->valor_pago}}
                @endif
            </td>
            <td>
                @if($pagamento->leitura->estado_pagamento=='Pendente')
                    ...
                @else
                   {{ \Carbon\Carbon::parse($pagamento->leitura->data_pagamento)->format('d-M-Y') }}
                @endif
            </td>
        </tr>
      @endforeach
    </table>

    <p style="text-align: left; font-size: 8pt;">
        Saldo Actual:<b> {{$cliente->saldo}}</b><br>
        Divida Actual:<b> {{$cliente->divida}}</b><br>
    </p>


    <br><br>
    <p style="text-align: right; font-size: 7pt;">
        <i>Impresso no dia {{ now()->format('d-M-Y H:i') }}</i>
    </p>

    <br>
    <table style="width: 100%; text-align: center; margin-top: 20px;font-size: 9pt;">
        <tr>
            <td>
                O(a) Responsavel <br>
                _________________________________________________
            </td>
        </tr>
    </table>


</div>
<div class="footer">
        <div>
            Contactos  <i><b> </b></i> site <u>https://agua.ac.mz</u> WhatsApp +258 86 824 4468 <b></b>   
        </div>
 </div>

</body>
</html>
