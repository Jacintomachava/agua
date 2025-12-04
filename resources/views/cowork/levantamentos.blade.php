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
                    Lista de Levantamento
                </h2>
                <div class="card-header-right-icon">
                    <a href="#">
                        <button class="btn btn-pill btn-primary btn-sm" data-bs-toggle="modal" data-bs-target=".bd-example-modal-lg">Levantamento</button>
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
                            <th>Saldo</th>
                            <th>V. Levantado</th>
                            <th>Telefone</th>
                            <th>Codigo</th>
                            <th>Data</th>
                            <th>Hora</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach($levantamentos as $levantamento)
                            <tr>
                                <td></td>
                                <td>{{$levantamento->saldo_actual}}</td>
                                <td>{{$levantamento->valor_levantado}}</td>
                                <td>{{$levantamento->telefone}}</td>
                                <td>{{$levantamento->codigo}}</td>
                                <td>{{$levantamento->created_at}}</td>
                                <td>{{$levantamento->created_at}}</td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModal" aria-hidden="true">
    <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form  id="form">  
        <div class="modal-header">
            <h4 class="modal-title" id="myExtraLargeModal">Saldo Disponivel: {{ Auth::user()->saldo }}</h4>
            <button class="btn-close py-0" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body dark-modal">

            @csrf

            <div class="row">
                
                <div class="col-4">
                    <label class="form-label" for="passwordwizard">Digite Valor<span class="txt-danger">*</span></label>
                    <input class="form-control" type="text" name="valor" placeholder="Valor Mpesa"  >
                </div>

                <div class="col-4">
                    <label class="form-label" for="passwordwizard">Telefone Mpesa<span class="txt-danger">*</span></label>
                    <input class="form-control" type="text" name="telefone" placeholder="Numero Mpesa" >
                </div>

                <div class="col-4">
                    <label class="form-label" for="passwordwizard">Senha de login<span class="txt-danger">*</span></label>
                    <input class="form-control" type="text" name="senha" placeholder="Senha Login" >
                </div>


            </div>

        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cancelar</button>
            <button class="btn btn-primary" id="botao_salvar" type="submit" >
                <span id="botao_texto">{{__('Fazer Levantamento')}}</span>
                <i id="icon_enviar" class="ri-arrow-right-line label-icon align-middle fs-16 ms-2"></i>
            </button>
        </div>
      </form>  
    </div>
    </div>
</div>

@endsection

@push('js')


<script>

$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $("#form").validate({
        // Adicionar regras para cada campo
        rules: {
            valor: {
                required: true,
                min: 100,
                max: 10000
            },
            telefone: {
                required: true,
                minlength: 9,
                maxlength: 9,
            },
            senha: {
                required: true
            },
        },
        submitHandler: function(form) {
            $.ajax({
                type: "POST",
                url: "{{route('fazer.levantamento')}}",
                data: $(form).serialize(), // Corrigido para usar `form` em vez de `this`

                beforeSend: function () {
                    // Desabilita o botão de envio e altera o ícone para mostrar que está autenticando
                    $('#botao_salvar').attr('disabled', true);
                    $('#icon_enviar').removeClass('ri-arrow-right-line').addClass('spinner-border ri-loader-2-line');
                    $('#botao_texto').text('Comprando Credito...');
                },

                success: function(response) {
                    // Habilita o botão e retorna o ícone original
                    $('#botao_salvar').attr('disabled', false);
                    $('#icon_enviar').removeClass('spinner-border ri-loader-2-line').addClass('ri-arrow-right-line');
                    $('#botao_texto').text('Comprar Credito');

                    // Redireciona ou exibe uma mensagem de erro com base na resposta
                    if(response.status == 1) {

                      Swal.fire({
                            icon: 'success',
                            title: 'Sucesso!',
                            text: response.message,
                      });

                      window.location.reload();

                    } else if(response.status == 0) {

                      Swal.fire({
                            icon: 'error',
                            title: 'Erro!',
                            text: response.message,
                      });

                    }
                },
                error: function(errors) {
                    // Habilita o botão e retorna o ícone original em caso de erro
                    $('#botao_salvar').attr('disabled', false);
                    $('#icon_enviar').removeClass('spinner-border ri-loader-2-line').addClass('ri-arrow-right-line');
                    $('#botao_texto').text('Comprar Credito');

                    // Exibe a mensagem de erro
                    Swal.fire({
                        icon: 'error',
                        title: 'Erro!',
                        text: errors.responseJSON.message,
                    });

                }
            });
        }
    });
});

</script>


@endpush
