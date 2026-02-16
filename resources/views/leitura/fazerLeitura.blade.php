
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Cliente</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            font-size: 12pt;
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
        width="150" height="120">
    <h6>{{ strtoupper($empresa->nome) }}<br>
        LISTA DE CLIENTE<br>
        LEITURA DE MES {{strtoupper($mesLeitura->nome)}}
    </h6>
</div>
<div class="content">

    @php
        use Illuminate\Support\Str;
    @endphp

    @php $orderNumber = 1; @endphp

    <table class="table table-bordered" style="font-size: 7pt; ">
        <tr style="background: #0a0a0cff; color: #e8ecf3ff">
            <th>#</th>
            <th>Codigo</th>
            <th>Nome</th>
            <th>Telefone</th>
            <th>Bairro</th>
            <th>Q.</th>
            <th>C.</th>
            <th>Leitura Anterior</th>
            <th>Leitura Actual</th>
            <th>Data</th>
        </tr>
     @foreach($leituras as $leitura)
        <tr>
            <td>{{$leitura->furoClienteContrato->contador}}</td>
            <td>{{$leitura->furoClienteContrato->codigo}}</td>
            <td>{{$leitura->furoClienteContrato->cliente->nome }}</td>
            <td>{{$leitura->furoClienteContrato->telefone_notificar }}</td>
            <td>{{$leitura->furoClienteContrato->bairro}}</td>
            <td>{{$leitura->furoClienteContrato->quarteirao}}</td>
            <td>{{$leitura->furoClienteContrato->casa}}</td>
            <td><center>{{$leitura->furoClienteContrato->ultima_leitura}}m&sup3;</center></td>
            <td>
                <center>
                    @if($leitura->estado_leitura==0)

                    @else
                        {{$leitura->valor_leitura}}m&sup3;
                    @endif
                </center>
            </td>
            <td>
                @if($leitura->estado_leitura==0)

                @else
                    {{ \Carbon\Carbon::parse($leitura->update_at)->format('d-M-Y') }}
                @endif
            </td>

            @php $orderNumber++;  @endphp
        </tr>
      @endforeach
    </table>


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
