
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
        width="100" height="90"><br>
    <h6>{{ strtoupper($empresa->nome) }}</h6>
    <h6><u>LISTA DE CLIENTE</u></h6>
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
            <th>Geo.</th>
            <th>Leitura</th>
            <th>Saldo</th>
            <th>Divida</th>
            <th>Act/Ina.</th>
            <th>Data</th>
        </tr>
     @foreach($clientes as $cliente)
        <tr>
            <td>{{ $orderNumber }}</td>
            <td>{{$cliente->contador}}</td>
            <td>{{$cliente->cliente->nome}}</td>
            <td>{{$cliente->telefone_notificar }}</td>
            <td>{{$cliente->bairro}}</td>
            <td>{{$cliente->cliente->quarteirao}}</td>
            <td>{{$cliente->cliente->casa}}</td>
            <td>
                @if($cliente->localizacao_activa==1)
                    <span style="color: #07b664ff">Activa</span>
                @else
                    <span style="color: #dd1313ff">Pendente</span>
                @endif
            </td>
            <td>{{$cliente->ultima_leitura}}</td>
            <td>{{ number_format($cliente->saldo, 2, ',', '.') }}</td>
            <td>{{ number_format($cliente->divida, 2, ',', '.') }}</td>
            <td>
                @if($cliente->ligacao_activa==1)
                    <span style="color: #07b664ff">Activa</span>
                @else
                    <span style="color: #dd1313ff">Cortado</span>
                @endif
            </td>
            <td>{{ \Carbon\Carbon::parse($cliente->created_at)->format('d-M-Y') }}</td>

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
