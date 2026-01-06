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
                    Lista de Comissões
                </h2>
                <div class="card-header-right-icon">
                  <h2>Saldo: {{$saldo}}</h2>
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
                            <th>NUIT</th>
                            <th>Mes</th>
                            <th>Valor</th>
                            <th>Percentagem</th>
                            <th>Comissao</th>
                            <th>Data</th>
                            <th>Hora</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach($divisoes as $divisao)
                            <tr>
                                <td></td>
                                <td>{{$divisao->empresa->nome}}</td>
                                <td>{{$divisao->empresa->nuit }}</td>
                                <td>{{$divisao->mesFactura->nome}}</td>
                                <td>{{$divisao->valor_pago}}</td>
                                <td>{{$divisao->percentagem}}%</td>
                                <td>{{$divisao->valor}}</td>
                                <td>{{$divisao->created_at->format('d-M-Y')}}</td>
                                <td>{{$divisao->created_at->format('H:s')}}</td>
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
