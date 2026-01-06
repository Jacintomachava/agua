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
                    Lista de Empresas
                </h2>
                <div class="card-header-right-icon">
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
                            <th>#</th>
                            <th>Nome</th>
                            <th>Telefone</th>
                            <th>NUIT</th>
                            <th>Distrito</th>
                            <th>Valor/Cliente</th>
                            <th>Subscricao</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach($empresas as $empresa)
                            <tr>
                                <td></td>
                                <td>{{$empresa->codigo}}</td>
                                <td>{{$empresa->nome }}</td>
                                <td>{{$empresa->telefone}}</td>
                                <td>{{$empresa->nuit}}</td>
                                <td>{{$empresa->distrito->nome}}</td>
                                <td>{{$empresa->valor_por_cliente}}MT</td>
                                <td>

                                    @if($empresa->subscricao==1)
                                      <a class="btn btn-success btn-xs activate-btn" href="#" >
                                        Activa
                                      </a>
                                    @else
                                      <a class="btn btn-warning btn-xs activate-btn" href="#" >
                                        Inativa
                                      </a>
                                    @endif
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
