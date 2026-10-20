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
                    <a href="{{route('cliente.create')}}">
                        <button class="btn btn-pill btn-primary btn-sm">Cadastrar Cliente</button>
                    </a>
                    <a href="{{route('mapa.clientes')}}">
                        <button class="btn btn-pill btn-success btn-sm">Mapa</button>
                    </a>
                    <a href="{{route('pdf.clientes')}}" target="_blank">
                        <button class="btn btn-pill btn-secondary btn-sm">Imprimir Cliente</button>
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
                            <th>#</th>
                            <th>Nome</th>
                            <th>Telefone</th>
                            <th>Bairro</th>
                            <th>Q.</th>
                            <th>C.</th>
                            <th>Act/Inat</th>
                            <th>Consumo</th>
                            <th>Valor</th>
                            <th>Saldo</th>
                            <th>Divida</th>
                            <th>Acção</th>
                        </tr>
                    </thead>
                    <tbody>

                     @if($clientes==null)
                        <tr>
                            <td colspan="12" class="text-center">
                                <center>Sem Nenhum Cliente Registado</center>
                            </td>
                        </tr>  
                     @else

                        @foreach($clientes as $cliente)
                            <tr>
                                <td></td>
                                <td>{{$cliente->contador}}</td>
                                <td>{{$cliente->cliente->nome }}</td>
                                <td>{{$cliente->telefone_notificar}}</td>
                                <td>{{$cliente->bairro}}</td>
                                <td>{{$cliente->quarteirao}}</td>
                                <td>{{$cliente->casa}} @if($cliente->localizacao_activa==1) <i  class="fa fa-map-marker" style="color: red;" ></i> @else  @endif</td>
                                <td>

                                    @if($cliente->ligacao_activa==1)
                                      <a class="btn btn-success btn-xs activate-btn" href="#" >
                                        Activa
                                      </a>
                                    @else
                                      <a class="btn btn-warning btn-xs activate-btn" href="#" >
                                        Cortada
                                      </a>
                                    @endif
                                </td>
                                <td>{{$cliente->consumo}}m&sup3;</td>
                                <td>{{ $cliente->valor_consumo }} </td>
                                <td>{{ number_format($cliente->saldo, 2,',','.') }} </td>
                                <td>{{ number_format($cliente->divida, 2,',','.') }}</td>

                                <td>
                                     <a class="btn btn-info btn-xs activate-btn"  href="{{route('cliente.show',['codigo'=>$cliente->codigo])}}">
                                        Detalhes
                                     </a>
                                     <a class="btn btn-warning btn-xs activate-btn" href="{{route('cliente.edit',['codigo'=>$cliente->codigo])}}" >
                                        Editar
                                     </a>
                                     @if($cliente->ligacao_activa==1)
                                        <a class="btn btn-danger btn-xs activate-btn" href="{{route('cliente.cortar',['codigo'=>$cliente->codigo])}}" >
                                            Cortar
                                        </a>
                                     @else
                                        <a class="btn btn-secondary btn-xs activate-btn" href="{{route('cliente.ligar',['codigo'=>$cliente->codigo])}}" >
                                            Ligar
                                        </a>
                                     @endif
                                     <a class="btn btn-success btn-xs activate-btn" href="{{route('cliente.geolocalizacao',['contratoID'=>$cliente->codigo])}}"  >
                                        Geo.
                                    </a>
                                    <a href="javascript:void(0)"
                                        class="btn btn-danger btn-xs btn-apagar-cliente"
                                        data-codigo="{{ $cliente->id }}"
                                        data-nome="{{ $cliente->cliente->nome }}">
                                            <i class="fa fa-trash"></i> Apagar
                                    </a>
                                     
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


<script>

$(document).ready(function () {

    $(document).on('click', '.btn-apagar-cliente', function (e) {

        e.preventDefault();

        let botao = $(this);
        let codigo = botao.data('codigo');
        let nome = botao.data('nome');

        let linha = botao.closest('tr');

        Swal.fire({
            title: 'Tem certeza?',
            html: `Deseja realmente apagar o cliente <b>${$('<div>').text(nome).html()}</b>?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sim, apagar!',
            cancelButtonText: 'Cancelar',
            reverseButtons: true

        }).then((result) => {

            if (result.isConfirmed) {

                Swal.fire({
                    title: 'A apagar...',
                    text: 'Por favor, aguarde.',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                $.ajax({

                    url: "{{ route('cliente.destroy', ['codigo' => '__CODIGO__']) }}"
                        .replace('__CODIGO__', encodeURIComponent(codigo)),

                    type: 'DELETE',

                    data: {
                        _token: "{{ csrf_token() }}"
                    },

                    success: function (response) {

                        Swal.fire({
                            icon: 'success',
                            title: 'Apagado!',
                            text: response.message,
                            timer: 2000,
                            showConfirmButton: false
                        });

                        linha.fadeOut(500, function () {
                            $(this).remove();
                        });

                    },

                    error: function (xhr) {

                        let mensagem = xhr.responseJSON?.message
                            || 'Ocorreu um erro ao apagar o cliente.';

                        Swal.fire({
                            icon: 'error',
                            title: 'Erro!',
                            text: mensagem
                        });

                    }

                });

            }

        });

    });

});

</script>


@endpush
