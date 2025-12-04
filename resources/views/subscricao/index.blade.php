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
                    Lista de Mensalidade Sistema
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
                            <th>Factura</th>
                            <th>Mês</th>
                            <th>Nr Cli.</th>
                            <th>Preço</th>
                            <th>Multa</th>
                            <th>Valor</th>
                            <th>Estado</th>
                            <th>Prazo Pag.</th>
                            <th>Data Pag.</th>
                            <th>Acção</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach($mensalidades as $mensalidade)
                            <tr>
                                <td></td>
                                <td>{{$mensalidade->codigo}}</td>
                                <td>{{$mensalidade->mes->nome}}-{{$mensalidade->ano->ano}}</td>
                                <td>{{$mensalidade->clientes }}</td>
                                <td>{{$mensalidade->valor_por_cliente}}</td>
                                <td>{{$mensalidade->multa}}%</td>
                                <td>
                                    {{
                                         number_format(
                                            ($mensalidade->clientes * $mensalidade->valor_por_cliente) * (1 + $mensalidade->multa / 100),
                                            2, ',', '.'
                                        )
                                    }}
                                </td>
                                <td>
                                    @if($mensalidade->pagou==0)
                                        <a class="btn btn-danger btn-xs activate-btn" href="" >
                                            Pendente
                                        </a>
                                    @else
                                        <a class="btn btn-success btn-xs activate-btn" href="" >
                                            Pago
                                        </a>
                                    @endif
                                </td>
                                <td>{{\Carbon\Carbon::parse($mensalidade->prazo_pagamento)->format('d-m-Y')}}</td>
                                <td>
                                    @if($mensalidade->pagou==0)
                                        ...
                                    @else
                                        {{\Carbon\Carbon::parse($mensalidade->data_pagamento)->format('d-m-Y')}}
                                    @endif
                                </td>
                                <td>
                                    <a class="btn btn-warning btn-xs activate-btn" href="" >
                                        Fatura
                                    </a>
                                     
                                    @if($mensalidade->pagou==0)
                                        <a class="btn btn-danger btn-xs activate-btn" href="{{route('pagamentoSubscricao.show',$mensalidade->codigo)}}"  >
                                            Pagar
                                        </a>
                                    @else
                                        <a class="btn btn-success btn-xs activate-btn" href="" >
                                            Recibo
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
