@extends('layouts.app')

@push('css')

@endpush

@section('conteudo')

  <div class="container-fluid">
    <div class="row">
      <div class="col-xl-12">
        <div class="card height-equal title-line">
          <div class="card-header">
            <div class="row">
                <div class="col-7">
                    <h2>Registo de Contrato</h2>
                    <p class="f-m-light mt-1">
                        Faça o registo do Contrato
                    </p>
                </div> 
                <div class="col-5">

                </div> 
            </div>          
          </div>


            <div class="card-body basic-wizard important-validation">
              <div id="msform1">

              <form class="row g-3 needs-validation custom-input" id="msform" enctype="multipart/form-data">

                  @csrf
                  
                  <form1 class="stepper-one row g-3 needs-validation custom-input" >


                    <div class="col-4">
                      <label class="form-label" for="passwordwizard">Descrição<span class="txt-danger">*</span></label>
                      <input class="form-control"  type="text" name="descricao"  >
                    </div>

                    <div class="col-4">
                        <label class="form-label" for="passwordwizard">Categorias<span class="txt-danger">*</span></label>
                          <select class="form-select"  name="categoria">
                                <option value="">Seleciona a Categoria</option>
                                @foreach($categorias as $categoria)
                                    <option value="{{$categoria->id}}">{{$categoria->nome}}</option>
                                @endforeach
                          </select>
                    </div>

                    <div class="col-4">
                      <label class="form-label" for="passwordwizard">Valor Despesa<span class="txt-danger">*</span></label>
                      <input class="form-control"  type="text" name="valor_despesa"  >
                    </div>

                    <div class="col-4">
                      <label class="form-label" for="passwordwizard">Valor Pago<span class="txt-danger">*</span></label>
                      <input class="form-control"  type="text" name="valor_pago"  >
                    </div>

                    <div class="col-4">
                        <label class="form-label" for="passwordwizard">Estado Pagamento<span class="txt-danger">*</span></label>
                          <select class="form-select"  name="estado">
                                <option value="">Seleciona o estado</option>
                                <option value="Pago">Pago</option>
                                <option value="Completo">Completo</option>
                                <option value="Pago Parcial">Pago Parcial</option>
                                <option value="Pendente">Pendente</option>
                          </select>
                    </div>

                    <div class="col-4">
                      <label class="form-label" for="passwordwizard">Data Pagamento<span class="txt-danger">*</span></label>
                      <input class="form-control"  type="date" name="data_pagamento"  >
                    </div>

                  </form1>
                  <div class="wizard-footer d-flex gap-2 justify-content-end">
                      <button id="botao_salvar" type="submit" class="btn btn-success w-100">
                          <span id="botao_texto">{{__('Registar Despesa')}} </span>
                          <i id="icon_enviar" class="ri-arrow-right-line label-icon align-middle fs-16 ms-2"></i>
                      </button>
                  </div>
              </form> 
  
            </div>
              <br>

            </div>

        </div>
      </div>
    </div>
  </div>

@endsection


@push('js')

furo.store

<script>

$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $("#msform").validate({
        // Adicionar regras para cada campo
        rules: {
            descricao: {
                required: true,
                minlength: 2,
            },
            valor_despesa: {
                required: true,
                number: true
            },
            valor_pago: {
                number: true
            },
            data_pagamento: {
                required: true,
            },
            estado: {
                required: true
            }
     },
        submitHandler: function(form) {
            $.ajax({
                type: "POST",
                url: "{{route('despesas.store')}}",
                data: $(form).serialize(), // Corrigido para usar `form` em vez de `this`

                beforeSend: function () {
                    // Desabilita o botão de envio e altera o ícone para mostrar que está autenticando
                    $('#botao_salvar').attr('disabled', true);
                    $('#icon_enviar').removeClass('ri-arrow-right-line').addClass('spinner-border ri-loader-2-line');
                    $('#botao_texto').text('Regsitando Contrato...');
                },

                success: function(response) {
                    // Habilita o botão e retorna o ícone original
                    $('#botao_salvar').attr('disabled', false);
                    $('#icon_enviar').removeClass('spinner-border ri-loader-2-line').addClass('ri-arrow-right-line');
                    $('#botao_texto').text('Registar Contrato');

                    // Redireciona ou exibe uma mensagem de erro com base na resposta
                    if(response.status == 1) {

                        Swal.fire({
                            icon: 'success',
                            title: 'Sucesso!',
                            text: response.message,
                            confirmButtonText: 'OK'
                        }).then((result) => {

                            if (result.isConfirmed) {
                                window.location.href = '/despesas';
                            }

                        });
                          
                    }

                    if(response.status == 0) {

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
                    $('#botao_texto').text('Registar Contrato');

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

