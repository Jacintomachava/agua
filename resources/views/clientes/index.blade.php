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
                    Lista de Clientes Contratos
                </h2>
                <div class="card-header-right-icon">
                    <a href="{{route('cliente.create')}}">
                        <button class="btn btn-pill btn-primary btn-sm">Cadastrar Cliente</button>
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
                            <th>Telefone</th>
                            <th>Contrato</th>
                            <th>Ligacao</th>
                            <th>Contador</th>
                            <th>Valor</th>
                            <th>estado</th>
                            <th>Bairro</th>
                            <th>Quarteirao</th>
                            <th>Casa</th>
                            <th>Acção</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach($clientes as $cliente)
                            <tr>
                                <td></td>
                                <td>{{$cliente->cliente->nome }}</td>
                                <td>{{$cliente->telefone_notificar}}</td>
                                <td>{{$cliente->contrato->nome}}</td>
                                <td>
                                    {{$cliente->ligacao_activa}}
                                </td>
                                <td>{{$cliente->contador}}</td>
                                <td>{{$cliente->contrato->valor_contrato}}</td>
                                <td>{{$cliente->estado_pagamento}}</td>
                                <td>{{$cliente->bairro}}</td>
                                <td>{{$cliente->quarteirao}}</td>
                                <td>{{$cliente->casa}}</td>
                                <td>
                                    <a class="btn btn-warning btn-xs activate-btn" href="" >
                                        Editar
                                    </a>
                                     <a class="btn btn-danger btn-xs activate-btn"  >
                                        Repor Senha
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
