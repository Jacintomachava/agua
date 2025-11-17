@extends('layouts.app')

@push('css')
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
@endpush

@section('conteudo')

<div class="col-xl-12 order-md-iii">
    <div class="card title-line overflow-hidden member-wrapper">
        <div class="card-header card-no-border">
            <div class="header-top">
                <h2>
                    <img class="img-40 img-fluid m-r-20" src="{{ URL('/assets/images/job-search/2.jpg')}}" alt="">
                    Lista de Clientes
                </h2>
                <div class="card-header-right-icon">
                    <a href="{{route('cliente.meuClientes')}}">
                        <button class="btn btn-pill btn-info btn-sm">Clientes</button>
                    </a>
                    <a href="{{route('cliente.create')}}">
                        <button class="btn btn-pill btn-primary btn-sm">Geolocalizacao(Mapa)</button>
                    </a>
                </div>
            </div>
        </div>
    </div> <!-- Fechamento da div "card title-line overflow-hidden member-wrapper" -->
</div> <!-- Fechamento da div "col-xl-12 order-md-iii" -->

<div class="col-sm-12" style="margin-top: -2%">
    <div class="card">
        <div class="card-header pb-0 card-no-border">
        </div>
        <div class="card-body">

            <div class="table-responsive custom-scrollbar">
                <table class="display" id="basic-1">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Nome</th>
                            <th>Bairro</th>
                            <th>Q.</th>
                            <th>C.</th>
                            <th>Mês</th>
                            <th>Leitura</th>
                            <th>Ultima Leitura</th>
                            <th>Leitura Actual</th>
                            <th>Valor</th>
                            <th>Acção</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach($leituras as $leitura)
                            <tr>
                                <td></td>
                                <td>{{$leitura->furoClienteContrato->cliente->nome }}</td>
                                <td>{{$leitura->furoClienteContrato->bairro}}</td>
                                <td>{{$leitura->furoClienteContrato->quarteirao}}</td>
                                <td>{{$leitura->furoClienteContrato->casa}}</td>
                                <td>{{$leitura->mes->nome}} - {{$leitura->ano->ano}}</td>

                                <td>
                                    @if($leitura->estado_leitura==0)
                                        <a class="btn btn-danger btn-xs activate-btn"  >
                                            Pendente
                                        </a>
                                    @else
                                        <a class="btn btn-success btn-xs activate-btn" >
                                            Feita
                                        </a>
                                    @endif
                                </td>

                                <td>

                                    {{$leitura->valor_leitura - $leitura->consumo}}
                                    
                                </td>

                                <td>
                                    @if($leitura->estado_leitura==0)
                                        ...
                                    @else
                                        {{$leitura->valor_leitura }}
                                    @endif
                                </td>

                                <td>
                                    @if($leitura->estado_leitura==0)
                                        ...
                                    @else
                                        {{$leitura->valor_a_pagar}} 
                                    @endif
                                </td>


                                <td>
                                    <a class="btn btn-warning btn-xs activate-btn" href="{{route('leitura.edit',['contratoID'=>$leitura->id])}}" >
                                        Fazer Leitura
                                    </a>
                                     <a class="btn btn-danger btn-xs activate-btn" href="{{route('localizar.casa',['contratoID'=>$leitura->furoClienteContrato->contador])}}"  >
                                        Geolocalizacao
                                    </a>
                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

@endsection

@push('js')


@endpush
