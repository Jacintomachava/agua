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
                    Lista de Furos
                </h2>
                <div class="card-header-right-icon">
                    <a href="{{route('furo.create')}}">
                        <button class="btn btn-pill btn-primary btn-sm">Cadastrar Furos</button>
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
                            <th>Enderenco</th>
                            <th>Empresa</th>
                            <th>Acção</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach($furos as $furo)
                            <tr>
                                <td></td>
                                <td>{{$furo->nome}}</td>
                                <td>{{$furo->endereco }}</td>
                                <td>{{$furo->empresa->nome}}</td>

                                <td>
                                    <a class="btn btn-warning btn-xs activate-btn" href="" >
                                        Editar
                                    </a>
                                     <a class="btn btn-danger btn-xs activate-btn"  >
                                        Apagar
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
