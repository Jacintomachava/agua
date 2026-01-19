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
                    Lista de Tipo Contratos
                </h2>
                <div class="card-header-right-icon">
                    <a href="{{route('contrato.create')}}">
                        <button class="btn btn-pill btn-primary btn-sm">Cadastrar Contrato</button>
                    </a>
                    @if($contrato!=null)
                        <a href="{{route('contrato.editarTemplete')}}">
                            <button class="btn btn-pill btn-warning btn-sm">Editar Templete</button>
                        </a>
                    @else
                        <a href="{{route('contrato.templete')}}">
                            <button class="btn btn-pill btn-warning btn-sm">Cadastrar Templete</button>
                        </a>
                    @endif
                    
                    
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
                            <th>V. Contrato</th>
                            <th>Custo/m&sup3;</th>
                            <th>Cunsumo M. (m&sup3;)</th>
                            <th>Prazo Pagamento</th>
                            <th>Multa</th>
                            <th>Acção</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach($contratos as $contrato)
                            <tr>
                                <td></td>
                                <td>{{$contrato->nome }}</td>
                                <td>{{$contrato->valor_contrato}}MT</td>
                                <td>{{$contrato->valor}}MT/{{$contrato->metro_cubico}}m&sup3;</td>
                                <td>{{$contrato->consumo_minimo}}m&sup3; ({{$contrato->consumo_minimo*$contrato->valor}}MT)</td>
                                <td>Dia {{$contrato->prazo_pagamento}} Cada Mes</td>
                                <td>{{$contrato->multa}}%</td>

                                <td>
                                    <a class="btn btn-warning btn-xs activate-btn" href="" >
                                        Editar
                                    </a>
                                     <a class="btn btn-danger btn-xs activate-btn"  >
                                        Remover
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
