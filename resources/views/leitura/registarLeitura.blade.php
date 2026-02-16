@extends('layouts.app')

@push('css')
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<style>

/* Estilo geral da tabela */
.table {
    border-collapse: collapse; /* Remove espaços entre células */
    width: 100%; /* Faz a tabela ocupar todo o espaço disponível */
    font-size: 12px; /* Define o tamanho padrão do texto */
    table-layout: fixed; /* Controla as larguras das colunas */
}

/* Quando a tela for menor que 768px (mobile/tablet) */
@media (max-width: 768px) {
    .table {
        width: 210%; /* Aumenta a largura para 120% do container */
        font-size: 12px; /* Opcional: aumenta o tamanho do texto para melhor leitura */
    }

    /* Para scroll horizontal se a tabela ficar maior que a tela */
    .table-container {
        overflow-x: auto;
    }
}

input{
    width: 100%;
    height: 15px;
    font-size: 11px;
    text-align: center;
}

/* Estilo para células */
.table th, .table td {
    border: 1px solid black; /* Bordas finas */
    padding: 5px; /* Espaçamento interno */
    text-align: center; /* Centraliza o texto */
    vertical-align: middle; /* Centraliza verticalmente */
    overflow: hidden; /* Impede que conteúdo ultrapasse a célula */
    text-overflow: ellipsis; /* Adiciona "..." ao texto cortado */
    white-space: nowrap; /* Evita quebra de linha */
}

/* Ajuste para cabeçalhos */
.table th {
    background-color: #f8f9fa; /* Fundo claro para destaque */
    font-weight: bold; /* Títulos em negrito */
}

/* Alternância de cores nas linhas */
.table tbody tr:nth-child(even) {
    background-color: #f2f2f2; /* Fundo alternado */
}

/* Rolagem horizontal (caso necessário) */
.custom-scrollbar {
    overflow-x: auto; /* Habilita rolagem horizontal */
    max-height: 650px; /* Limita a altura da tabela */
}

</style>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css">
<script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
@endpush

@section('conteudo')


<div class="row">
    <div class="col-sm-12">
        <div class="card light-table-bg title-line">

          <form method="GET" action=""  id="formNotas" >
                <div class="card-header">
                    <center>
                        <h3>REGISTAR LEITURAS DE MÊS {{strtoupper($mesLeitura->nome)}}</h3>
                    </center>
                </div>
          </form>

          <form id="form" enctype="multipart/form-data">

            @csrf

            <div class="card-block row">
                <div class="col-sm-12 col-lg-12 col-xl-12">
                    <div class="table-responsive signal-table custom-scrollbar">
                        <table class="table table-hover" id="tabela-notas">
                            <thead>
                                <tr>
                                    <th scope="col" style="border: 0.2px solid black; width: 0.04%;">#</th>
                                    <th scope="col" style="border: 0.2px solid black; width: 0.08%;">Codigo</th>
                                    <th scope="col" style="border: 0.2px solid black; width: 0.08%;">Nome</th>
                                    <th scope="col" style="border: 0.2px solid black; width: 0.08%;">Telefone</th>
                                    <th scope="col" style="border: 0.2px solid black; width: 0.08%;">Bairro</th>
                                    <th scope="col" style="border: 0.2px solid black; width: 0.08%;">Q.</th>
                                    <th scope="col" style="border: 0.2px solid black; width: 0.08%;">C.</th>
                                    <th scope="col" style="border: 0.2px solid black; width: 0.08%;">Leitura Anterior</th>
                                    <th scope="col" style="border: 0.2px solid black; width: 0.08%;">Leitura Actual</th>
                                    <th scope="col" style="border: 0.2px solid black; width: 0.08%;">Data leitura</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($leituras as $leitura)
                                    <tr>
                                        
                                        <td>{{$leitura->furoClienteContrato->contador}}</td>
                                        <td>{{$leitura->furoClienteContrato->codigo}}</td>
                                        <td>{{$leitura->furoClienteContrato->cliente->nome}}</td>
                                        <td>{{$leitura->furoClienteContrato->telefone_notificar}}</td>
                                        <td>{{$leitura->furoClienteContrato->bairro}}</td>
                                        <td>{{$leitura->furoClienteContrato->quarteirao}}</td>
                                        <td>{{$leitura->furoClienteContrato->casa}}</td>

                                        <td>
                                            <center>
                                                {{$leitura->furoClienteContrato->ultima_leitura}} m³
                                            </center>
                                        </td>

                                        {{-- Leitura Actual --}}
                                        <td>
                                            @if($leitura->estado_leitura == 0)

                                                <input type="hidden" name="leituras[{{$loop->index}}][id]" value="{{$leitura->id}}">

                                                <input type="number"
                                                    name="leituras[{{$loop->index}}][valor]"
                                                    class="form-control"
                                                    min="{{$leitura->furoClienteContrato->ultima_leitura}}"
                                                    style="text-align: center;"
                                                    >

                                            @else
                                                {{$leitura->valor_leitura}} m³
                                            @endif
                                        </td>

                                        {{-- Data --}}
                                        <td>
                                            @if($leitura->estado_leitura == 0)

                                                <input type="date"
                                                    name="leituras[{{$loop->index}}][data]"
                                                    class="form-control"
                                                    value="{{ now()->format('Y-m-d') }}"
                                                    style="text-align: center;"
                                                    required>

                                            @else
                                                {{ \Carbon\Carbon::parse($leitura->updated_at)->format('d-M-Y') }}
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <br>
                        <center>
                                <div class="text-center">
                                    <button type="submit" id="botao_salvar" class="btn btn-success">
                                        <span id="botao_texto">Guardar Leituras</span>
                                        <i id="icon_enviar" class="ri-arrow-right-line"></i>
                                    </button>
                                </div>
                        </center>
                        <br>
                    </div>
                </div>
            </div>
          </form>
        </div>
    </div>
</div>



@endsection

@push('js')


<script>

    $(document).ready(function () {

    $("#form").on("submit", function (e) {

        e.preventDefault();

        Swal.fire({
            title: 'Confirmar?',
            text: "Deseja guardar as leituras?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sim, guardar'
        }).then((result) => {

            if (!result.isConfirmed) return;

            $.ajax({
                type: "POST",
                url: "{{ route('update.todos') }}",
                data: $(this).serialize(),

                beforeSend: function () {

                    $('#botao_salvar').attr('disabled', true);
                    $('#icon_enviar').removeClass().addClass('spinner-border spinner-border-sm');
                    $('#botao_texto').text('A guardar...');
                },

                success: function (response) {

                    $('#botao_salvar').attr('disabled', false);
                    $('#icon_enviar').removeClass().addClass('ri-arrow-right-line');
                    $('#botao_texto').text('Guardar Leituras');

                    if (response.status == 1) {

                        Swal.fire({
                            icon: 'success',
                            title: 'Sucesso',
                            text: response.message
                        }).then(() => {
                            location.reload();
                        });

                    } else {

                        Swal.fire('Erro', response.message, 'error');
                    }
                },

                error: function () {

                    $('#botao_salvar').attr('disabled', false);

                    Swal.fire(
                        'Erro',
                        'Erro ao comunicar com servidor.',
                        'error'
                    );
                }
            });

        });

    });

});

</script>


@endpush
