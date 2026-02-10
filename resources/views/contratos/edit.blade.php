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
                    <h2>Editar o Contrato</h2>
                    <p class="f-m-light mt-1">
                        Faça a Actualização Contrato
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

                    <input class="form-control"  type="text" name="id" value="{{$contacto->id}}"  hidden >

                    <div class="col-4">
                      <label class="form-label" for="passwordwizard">Nome<span class="txt-danger">*</span></label>
                      <input class="form-control"  type="text" name="nome" value="{{$contacto->nome}}" >
                    </div>

                    <div class="col-4">
                      <label class="form-label" for="passwordwizard">Valor de Contracto<span class="txt-danger">*</span></label>
                      <input class="form-control"  type="text" name="valor_contrato" value="{{$contacto->valor_contrato}}" >
                    </div>

                    <div class="col-4" hidden>
                        <label class="form-label" for="passwordwizard">Consumo (m&sup3;)<span class="txt-danger">*</span></label>
                          <select class="form-select"  name="consumo" readonly style="background-color: #f5f5f5; color: #555;">
                                <option value="1">1m&sup3;</option>
                          </select>
                    </div>

                    <div class="col-4">
                      <label class="form-label" for="passwordwizard">Valor Consumo Por 1m&sup3;<span class="txt-danger">*</span></label>
                      <input class="form-control"  type="text" name="valor_consumo"  value="{{$contacto->valor}}" >
                    </div>

                    <div class="col-4">
                        <label class="form-label" for="passwordwizard">Consumo Minimo Em m&sup3;<span class="txt-danger">*</span></label>
                          <select class="form-select"  name="consumo_minimo">
                                <option value="1" @if($contacto->consumo_minimo == 1) selected @endif  >1m&sup3;</option>
                                <option value="2" @if($contacto->consumo_minimo == 2) selected @endif>2m&sup3;</option>
                                <option value="3" @if($contacto->consumo_minimo == 3) selected @endif>3m&sup3;</option>
                                <option value="4" @if($contacto->consumo_minimo == 4) selected @endif>4m&sup3;</option>
                                <option value="5" @if($contacto->consumo_minimo == 5) selected @endif>5m&sup3;</option>
                                <option value="6" @if($contacto->consumo_minimo == 6) selected @endif>6m&sup3;</option>
                                <option value="7" @if($contacto->consumo_minimo == 7) selected @endif>7m&sup3;</option>
                                <option value="8" @if($contacto->consumo_minimo == 8) selected @endif>8m&sup3;</option>
                                <option value="9" @if($contacto->consumo_minimo == 9) selected @endif>9m&sup3;</option>
                                <option value="10" @if($contacto->consumo_minimo == 10) selected @endif>10m&sup3;</option>
                          </select>
                    </div>

                    <div class="col-4">
                        <label class="form-label" for="passwordwizard">Prazo Pagamento<span class="txt-danger">*</span></label>
                          <select class="form-select"  name="prazo_pagamento">
                                <option value="10" @if($contacto->prazo_pagamento == 10) selected @endif>Dia 10 de Cada Mes</option>
                                <option value="1" @if($contacto->prazo_pagamento == 1) selected @endif >Dia 1 de Cada Mes</option>
                                <option value="2" @if($contacto->prazo_pagamento == 2) selected @endif>Dia 2 de Cada Mes</option>
                                <option value="3" @if($contacto->prazo_pagamento == 3) selected @endif>Dia 3 de Cada Mes</option>
                                <option value="4" @if($contacto->prazo_pagamento == 4) selected @endif>Dia 4 de Cada Mes</option>
                                <option value="5" @if($contacto->prazo_pagamento == 5) selected @endif>Dia 5 de Cada Mes</option>
                                <option value="6" @if($contacto->prazo_pagamento == 6) selected @endif>Dia 6 de Cada Mes</option>
                                <option value="7" @if($contacto->prazo_pagamento == 7) selected @endif>Dia 7 de Cada Mes</option>
                                <option value="8" @if($contacto->prazo_pagamento == 8) selected @endif>Dia 8 de Cada Mes</option>
                                <option value="9" @if($contacto->prazo_pagamento == 9) selected @endif>Dia 9 de Cada Mes</option>
                                <option value="11" @if($contacto->prazo_pagamento == 11) selected @endif>Dia 11 de Cada Mes</option>
                                <option value="12" @if($contacto->prazo_pagamento == 12) selected @endif>Dia 12 de Cada Mes</option>
                                <option value="13" @if($contacto->prazo_pagamento == 13) selected @endif>Dia 13 de Cada Mes</option>
                                <option value="14" @if($contacto->prazo_pagamento == 14) selected @endif>Dia 14 de Cada Mes</option>
                                <option value="15" @if($contacto->prazo_pagamento == 15) selected @endif>Dia 15 de Cada Mes</option>
                                <option value="16" @if($contacto->prazo_pagamento == 16) selected @endif>Dia 16 de Cada Mes</option>
                                <option value="17" @if($contacto->prazo_pagamento == 17) selected @endif>Dia 17 de Cada Mes</option>
                                <option value="18" @if($contacto->prazo_pagamento == 18) selected @endif>Dia 18 de Cada Mes</option>
                                <option value="19" @if($contacto->prazo_pagamento == 19) selected @endif>Dia 19 de Cada Mes</option>
                                <option value="20" @if($contacto->prazo_pagamento == 20) selected @endif>Dia 20 de Cada Mes</option>
                                <option value="21" @if($contacto->prazo_pagamento == 21) selected @endif>Dia 21 de Cada Mes</option>
                                <option value="22" @if($contacto->prazo_pagamento == 22) selected @endif>Dia 22 de Cada Mes</option>
                                <option value="23" @if($contacto->prazo_pagamento == 23) selected @endif>Dia 23 de Cada Mes</option>
                                <option value="24" @if($contacto->prazo_pagamento == 24) selected @endif>Dia 24 de Cada Mes</option>
                                <option value="25" @if($contacto->prazo_pagamento == 25) selected @endif>Dia 25 de Cada Mes</option>
                                <option value="26" @if($contacto->prazo_pagamento == 26) selected @endif>Dia 26 de Cada Mes</option>
                                <option value="27" @if($contacto->prazo_pagamento == 27) selected @endif>Dia 27 de Cada Mes</option>
                                <option value="28" @if($contacto->prazo_pagamento == 28) selected @endif>Dia 28 de Cada Mes</option>
                                <option value="29" @if($contacto->prazo_pagamento == 29) selected @endif>Dia 29 de Cada Mes</option>
                                <option value="30" @if($contacto->prazo_pagamento == 30) selected @endif>Dia 30 de Cada Mes</option>
                          </select>
                    </div>

                    <div class="col-4">
                      <label class="form-label" for="passwordwizard">Multa Em (%)<span class="txt-danger">*</span></label>
                      <input class="form-control"  type="text" name="multa" value="{{$contacto->multa}}" >
                    </div>

                  </form1>
                  <div class="wizard-footer d-flex gap-2 justify-content-end">
                      <button id="botao_salvar" type="submit" class="btn btn-success w-100">
                          <span id="botao_texto">{{__('Actualizar Contrato')}} </span>
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
                url: "{{route('contrato.update')}}",
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
                                window.location.href = '/contratos';
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

