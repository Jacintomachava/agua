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
                    Pagamento Leituras
                </h2>
                <div class="card-header-right-icon">
                    ...
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
                            <th>Mês</th>
                            <th>Leitura</th>
                            <th>Valor</th>
                            <th>Divida</th>
                            <th>Multa</th>
                            <th>Total</th>
                            <th>Pagamento</th>
                            <th>Acção</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach($leituras as $leitura)
                            <tr>
                                <td></td>
                                <td>{{$leitura->furoClienteContrato->cliente->nome }}</td>
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
                                    
                                    @if($leitura->estado_leitura==0)
                                        ...
                                    @elseif($leitura->estado_leitura==1 && $leitura->estado_pagamento=='Pendente')
                                        {{$leitura->valor_a_pagar}} 
                                    @elseif($leitura->estado_leitura==1 && $leitura->estado_pagamento!='Pendente')
                                        ...
                                    @endif
                                    
                                </td>

                                <td>
                                    
                                    @if($leitura->estado_leitura==0)
                                        ...
                                    @elseif($leitura->estado_leitura==1 )
                                        {{$leitura->furoClienteContrato->divida}}
                                    @endif
                                </td>

                                <td>
                                    
                                    @if($leitura->estado_leitura==0)
                                        ...
                                    @elseif($leitura->estado_leitura==1 && $leitura->estado_pagamento=='Pendente')
                                        {{$leitura->multa}}%
                                    @elseif($leitura->estado_leitura==1 && $leitura->estado_pagamento!='Pendente')
                                        ...
                                    @endif
                                </td>

                                <td>

                                    @if($leitura->estado_leitura==0)
                                        ...
                                    @elseif($leitura->estado_leitura==1 && $leitura->estado_pagamento=='Pendente')
                                        {{($leitura->valor_a_pagar+$leitura->furoClienteContrato->divida)+($leitura->valor_a_pagar+$leitura->furoClienteContrato->divida)*$leitura->multa/100}} MT
                                    @elseif($leitura->estado_leitura==1 && $leitura->estado_pagamento!='Pendente')
                                        {{$leitura->furoClienteContrato->divida}}
                                    @endif
                                </td>
                                <td>
                                   {{$leitura->estado_pagamento}}
                                   @if($leitura->estado_leitura==0)
                                        ({{($leitura->valor_a_pagar+$leitura->furoClienteContrato->divida)+($leitura->valor_a_pagar+$leitura->furoClienteContrato->divida)*$leitura->multa/100}} MT)
                                    @elseif($leitura->estado_leitura==1 && $leitura->estado_pagamento=='Pendente')
                                        ({{($leitura->valor_a_pagar+$leitura->furoClienteContrato->divida)+($leitura->valor_a_pagar+$leitura->furoClienteContrato->divida)*$leitura->multa/100}} MT)
                                    @elseif($leitura->estado_leitura==1 && $leitura->estado_pagamento!='Pendente')
                                        ({{$leitura->valor_pago}})
                                    @endif
                                </td>

                                <td>
                                    <a class="btn btn-warning btn-xs activate-btn" href="{{route('pagamento.show',['contratoID'=>$leitura->id])}}" >
                                        Pagar
                                    </a>
                                    <a class="btn btn-info btn-xs activate-btn" href="{{route('fatura.index')}}" >
                                        Factura
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


<script>
function imprimir() {
    window.print();
}
</script>

@endpush
