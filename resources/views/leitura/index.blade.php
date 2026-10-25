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
                    <a href="{{route('todas.leituras')}}" >
                        <button class="btn btn-pill btn-secondary btn-sm">Meses Anteriores</button>
                    </a>
                    <a href="{{route('registar.leitura')}}" >
                        <button class="btn btn-pill btn-success btn-sm">Preenchar Leitura</button>
                    </a>
                    <a href="{{route('leitura.fazer')}}" target="_blank">
                        <button class="btn btn-pill btn-danger btn-sm">Leituras a Fazer PDF</button>
                    </a>
                    <a href="{{route('facturas.todos')}}" target="_blank">
                        <button class="btn btn-pill btn-info btn-sm">Facturas PDF</button>
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
                                        {{number_format($leitura->valor_a_pagar,2,',','.')}}
                                        @endif
                                    </td>
                                    <td>
                                        @if($leitura->estado_leitura==0)
                                        ...
                                        @else
                                        {{number_format($leitura->furoClienteContrato->divida,2,',','.')}}
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
                                           {{
                                                number_format(
                                                    $leitura->valor_a_pagar
                                                    + $leitura->furoClienteContrato->divida
                                                    - $leitura->furoClienteContrato->saldo
                                                    + ($leitura->valor_a_pagar * $leitura->multa / 100),
                                                    2,
                                                    ',',
                                                    '.'
                                                )
                                            }}
                                        @endif
                                    </td>


                                    <td>
                                        <a class="btn btn-warning btn-xs activate-btn" href="{{route('leitura.edit',['contratoID'=>$leitura->id])}}">
                                            Leitura
                                        </a>
                                        
                                        @if($leitura->estado_leitura==0)
                                            <a class="btn btn-danger btn-xs activate-btn" href="{{route('localizar.casa',['contratoID'=>$leitura->furoClienteContrato->id])}}">
                                                Localizar
                                            </a>
                                        @else
                                            <a class="btn btn-info btn-xs activate-btn" target="_blank" href="{{route('facturas.leitura',['id'=>$leitura->id])}}">
                                                Fatura
                                            </a>
                                        @endif

                                            <a href="javascript:void(0)"
                                                class="btn btn-danger btn-xs btn-divida"
                                                data-id="{{ $leitura->furoClienteContrato->id }}"
                                                data-nome="{{ $leitura->furoClienteContrato->cliente->nome }}">
                                                <i class="fas fa-money-bill-wave"></i>
                                                Dívida/Adi.
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


<div class="modal fade" id="modalDivida" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Dívida / Adiantamento</h5>
                <button type="button" class="btn-close"
                    data-bs-dismiss="modal"></button>
            </div>

            <form id="formDivida">
                @csrf

                <div class="modal-body">

                    <input type="hidden" name="cliente_id" id="cliente_id">

                    <div class="mb-3">
                        <label class="form-label">Cliente</label>
                        <input type="text"
                            id="cliente_nome"
                            class="form-control"
                            readonly  disabled>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tipo de operação</label>

                        <select name="tipo" id="tipo"
                            class="form-select" required>
                            <option value="DIVIDA">Dívida</option>
                            <option value="ADIANTAMENTO">Adiantamento</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Valor (MT)</label>
                        <input type="number"
                            name="valor"
                            id="valor"
                            class="form-control"
                            min="0.01"
                            step="0.01"
                            required>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                        data-bs-dismiss="modal">
                        Cancelar
                    </button>

                    <button type="submit" class="btn btn-primary"
                        id="btnGuardarDivida">
                        Guardar
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>

@endsection

@push('js')


<script>

$(document).on('click', '.btn-divida', function () {

    let clienteId = $(this).data('id');
    let clienteNome = $(this).data('nome');

    $('#formDivida')[0].reset();

    $('#cliente_id').val(clienteId);
    $('#cliente_nome').val(clienteNome);

    $('#modalDivida').modal('show');

    bootstrap.Modal.getOrCreateInstance(document.getElementById('modalDivida')).show();

});


$('#formDivida').on('submit', function (e) {

    e.preventDefault();

    let botao = $('#btnGuardarDivida');

    botao.prop('disabled', true).text('A guardar...');

    $.ajax({
        url: "{{ route('clientes.divida-adiantamento') }}",
        type: "POST",
        data: $(this).serialize(),

        success: function (response) {

            Swal.fire({
                icon: 'success',
                title: 'Sucesso!',
                text: response.message,
                timer: 2000,
                showConfirmButton: false
            }).then(() => {
                location.reload();
            });
        },

        error: function (xhr) {

            let mensagem = xhr.responseJSON?.message
                || 'Erro ao actualizar os dados.';

            Swal.fire({
                icon: 'error',
                title: 'Erro!',
                text: mensagem
            });
        },

        complete: function () {
            botao.prop('disabled', false).text('Guardar');
        }
    });
});


</script>

@endpush
