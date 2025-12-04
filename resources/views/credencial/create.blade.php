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
                    <h2>Fazer registo de Credenciais</h2>
                    <p class="f-m-light mt-1">
                        Faça o registo de Credenciais Mpesa
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
                      <label class="form-label">Ambiente</label>
                      <select class="form-select"  name="ambiente" >
                              <option value="">Selecione o Ambiente</option>
                              <option value="live">Producao</option>
                              <option value="test">Teste</option>
                      </select>
                    </div>

                    <div class="col-6">
                      <label class="form-label" for="passwordwizard">Service Code<span class="txt-danger">*</span></label>
                      <input class="form-control"  type="text" name="service_code"  >
                    </div>

                    <div class="col-6">
                      <label class="form-label" for="passwordwizard">API Key<span class="txt-danger"></span></label>
                      <input class="form-control"  type="text" name="api_key" >
                    </div>

                    <div class="col-6">
                      <label class="form-label" for="passwordwizard">Public Key<span class="txt-danger">*</span></label>
                      <input class="form-control"  type="text" name="public_key" >
                    </div>

                  </form1>
                  <div class="wizard-footer d-flex gap-2 justify-content-end">
                      <button id="botao_salvar" type="submit" class="btn btn-success w-100">
                          <span id="botao_texto">{{__('Registar Credenciais')}} </span>
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


<script>

$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    let ultimaLeitura = {{ $ultimaLeitura ?? 0 }}; // valor vindo do PHP

    $("#msform").validate({
        // Adicionar regras para cada campo
        rules: {
            ambiente: {
                required: true
            },
            service_code: {
                required: true,
                minlength: 6,
                maxlength: 6,
            },
            api_key: {
                required: true,
            },
            public_key: {
                required: true
            }
      },
        submitHandler: function(form) {

            let formData = new FormData(form); // captura todo o formulário inclusive arquivo

            $.ajax({
                type: "POST",
                url: "{{ route('credencial.store') }}",
                data: formData, // Corrigido para usar `form` em vez de `this`
                contentType: false,
                processData: false,
                cache: false,

                beforeSend: function () {
                    // Desabilita o botão de envio e altera o ícone para mostrar que está autenticando
                    $('#botao_salvar').attr('disabled', true);
                    $('#icon_enviar').removeClass('ri-arrow-right-line').addClass('spinner-border ri-loader-2-line');
                    $('#botao_texto').text('Regsitando Furo...');
                },

                success: function(response) {
                    // Habilita o botão e retorna o ícone original
                    $('#botao_salvar').attr('disabled', false);
                    $('#icon_enviar').removeClass('spinner-border ri-loader-2-line').addClass('ri-arrow-right-line');
                    $('#botao_texto').text('Registar Furo');

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
                    $('#botao_texto').text('Registar Furo');

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

