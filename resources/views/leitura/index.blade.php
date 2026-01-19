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
                    <a href="{{route('leituras.pendentes')}}">
                        <button class="btn btn-pill btn-warning btn-sm">Leituras Pendentes</button>
                    </a>
                    <a href="{{route('facturas.todos')}}" target="_blank">
                        <button class="btn btn-pill btn-success btn-sm">Facturas de Todos</button>
                    </a>
                    <a href="{{route('todas.leituras')}}" >
                        <button class="btn btn-pill btn-secondary btn-sm">Meses Anteriores</button>
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
                            <th>Codigo</th>
                            <th>Nome</th>
                            <th>Mês</th>
                            <th>Leitura</th>
                            <th>V. Leitura</th>
                            <th>Consumo</th>
                            <th>Valor</th>
                            <th>Divida</th>
                            <th>Multa</th>
                            <th>Total</th>
                            <th>Acção</th>
                        </tr>
                    </thead>
                    <tbody>

                        @if($leituras==null)
                            <tr>
                                <td colspan="12" class="text-center">
                                    <center>Sem Nenhum Leitura</center>
                                </td>
                            </tr>  
                        @else

                            @foreach($leituras as $leitura)
                                <tr>
                                    <td></td>
                                    <td>{{$leitura->furoClienteContrato->codigo }}</td>
                                    <td>{{$leitura->furoClienteContrato->cliente->nome }}</td>
                                    <td>{{$leitura->mes->nome}} </td>

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
                                        @if($leitura->estado_leitura==0)
                                            ...
                                        @else
                                            {{$leitura->valor_leitura}}
                                        @endif
                                    </td>
                                    <td>
                                        @if($leitura->estado_leitura==0)
                                        ...
                                        @else
                                        {{$leitura->consumo}}m&sup3;
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
                                        @if($leitura->estado_leitura==0)
                                        ...
                                        @else
                                        {{$leitura->divida_anterior}}
                                        @endif
                                    </td>
                                    <td>
                                        @if($leitura->estado_leitura==0)
                                        ...
                                        @else
                                        {{$leitura->multa}}
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
                                        <a class="btn btn-warning btn-xs activate-btn" href="{{route('leitura.edit',['contratoID'=>$leitura->id])}}">
                                            Leitura
                                        </a>
                                        
                                        @if($leitura->estado_leitura==0)
                                            <a class="btn btn-danger btn-xs activate-btn" href="{{route('localizar.casa',['contratoID'=>$leitura->furoClienteContrato->contador])}}">
                                                Localizar
                                            </a>
                                        @else
                                            <a class="btn btn-info btn-xs activate-btn" target="_blank" href="{{route('facturas.leitura',['id'=>$leitura->id])}}">
                                                Fatura
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach

                        @endif

                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

@endsection

@push('js')


@endpush
