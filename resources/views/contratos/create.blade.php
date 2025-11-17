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


                    <div class="col-6">
                      <label class="form-label" for="passwordwizard">Nome<span class="txt-danger">*</span></label>
                      <input class="form-control"  type="text" name="nome"  >
                    </div>

                    <div class="col-3">
                      <label class="form-label" for="passwordwizard">Valor Contrato<span class="txt-danger">*</span></label>
                      <input class="form-control"  type="text" name="valor_contrato"  >
                    </div>

                    <div class="col-3">
                        <label class="form-label" for="passwordwizard">Consumo (m&sup3;)<span class="txt-danger">*</span></label>
                          <select class="form-select"  name="consumo">
                                <option value="1">1m&sup3;</option>
                                <option value="2">2m&sup3;</option>
                                <option value="3">3m&sup3;</option>
                                <option value="4">4m&sup3;</option>
                                <option value="5">5m&sup3;</option>
                          </select>
                    </div>

                    <div class="col-3">
                      <label class="form-label" for="passwordwizard">Valor C.(m&sup3;)<span class="txt-danger">*</span></label>
                      <input class="form-control"  type="text" name="valor_consumo"  >
                    </div>

                    <div class="col-3">
                        <label class="form-label" for="passwordwizard">Consumo Minimo (m&sup3;)<span class="txt-danger">*</span></label>
                          <select class="form-select"  name="consumo_minimo">
                                <option value="1">1m&sup3;</option>
                                <option value="2">2m&sup3;</option>
                                <option value="3">3m&sup3;</option>
                                <option value="4">4m&sup3;</option>
                                <option value="5">5m&sup3;</option>
                                <option value="6">6m&sup3;</option>
                                <option value="7">7m&sup3;</option>
                                <option value="8">8m&sup3;</option>
                                <option value="9">9m&sup3;</option>
                                <option value="10">10m&sup3;</option>
                          </select>
                    </div>

                    <div class="col-3">
                        <label class="form-label" for="passwordwizard">Prazo Pagamento<span class="txt-danger">*</span></label>
                          <select class="form-select"  name="prazo_pagamento">
                                <option value="10">Dia 10 de Cada Mes</option>
                                <option value="1">Dia 1 de Cada Mes</option>
                                <option value="2">Dia 2 de Cada Mes</option>
                                <option value="3">Dia 3 de Cada Mes</option>
                                <option value="4">Dia 4 de Cada Mes</option>
                                <option value="5">Dia 5 de Cada Mes</option>
                                <option value="6">Dia 6 de Cada Mes</option>
                                <option value="7">Dia 7 de Cada Mes</option>
                                <option value="8">Dia 8 de Cada Mes</option>
                                <option value="9">Dia 9 de Cada Mes</option>
                                <option value="11">Dia 11 de Cada Mes</option>
                                <option value="12">Dia 12 de Cada Mes</option>
                                <option value="13">Dia 13 de Cada Mes</option>
                                <option value="14">Dia 14 de Cada Mes</option>
                                <option value="15">Dia 15 de Cada Mes</option>
                                <option value="16">Dia 16 de Cada Mes</option>
                                <option value="17">Dia 17 de Cada Mes</option>
                                <option value="18">Dia 18 de Cada Mes</option>
                                <option value="19">Dia 19 de Cada Mes</option>
                                <option value="20">Dia 20 de Cada Mes</option>
                                <option value="21">Dia 21 de Cada Mes</option>
                                <option value="22">Dia 22 de Cada Mes</option>
                                <option value="23">Dia 23 de Cada Mes</option>
                                <option value="24">Dia 24 de Cada Mes</option>
                                <option value="25">Dia 25 de Cada Mes</option>
                                <option value="26">Dia 26 de Cada Mes</option>
                                <option value="27">Dia 27 de Cada Mes</option>
                                <option value="28">Dia 28 de Cada Mes</option>
                                <option value="29">Dia 29 de Cada Mes</option>
                                <option value="30">Dia 30 de Cada Mes</option>
                          </select>
                    </div>

                    <div class="col-3">
                      <label class="form-label" for="passwordwizard">Multa Em (%)<span class="txt-danger">*</span></label>
                      <input class="form-control"  type="text" name="multa"  >
                    </div>

                  </form1>
                  <div class="wizard-footer d-flex gap-2 justify-content-end">
                      <button id="botao_salvar" type="submit" class="btn btn-success w-100">
                          <span id="botao_texto">{{__('Registar Contrato')}} </span>
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
            designacao: {
                required: true,
                minlength: 2,
            },
            consumo: {
                required: true,
                number: true
            },
            metro_cubico: {
                required: true,
            },
            valor: {
                required: true,
                number: true
            },
            multa: {
                required: true,
                number: true
            }
     },
        submitHandler: function(form) {
            $.ajax({
                type: "POST",
                url: "{{route('contrato.store')}}",
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
                          });

                          window.location.reload();
                          
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

