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
                    Lista das minhas Credenciais Mpesa
                </h2>
                <div class="card-header-right-icon">
                    <a href="{{route('credencial.create')}}">
                        <button class="btn btn-pill btn-primary btn-sm">Cadastrar Credenciais</button>
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
                            <th>Ambiente</th>
                            <th>Service Code</th>
                            <th>API Key</th>
                            <th>Public Key</th>
                            <th>Data</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach($credenciais as $credencial)
                            <tr>
                                <td></td>
                                <td>
                                    @if($credencial->env=='live')
                                        <a class="btn btn-success btn-xs activate-btn" >
                                            Producao
                                        </a>
                                    @else
                                        <a class="btn btn-danger btn-xs activate-btn"  >
                                          Teste
                                        </a>
                                    @endif
                                </td>
                                <td>{{$credencial->service_provaider_code }}</td>
                                <td>{{$credencial->api_key}}</td>
                                <td>{{$credencial->public_key}}</td>
                                <td>{{$credencial->created_at}}</td>
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
