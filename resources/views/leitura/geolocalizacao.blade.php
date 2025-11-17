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
                    <h2>Actualizar Geolocalizacao do Cliente</h2>
                    <p class="f-m-light mt-1">
                        Faça a Actualizacao da geolocalizacao 
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

                    <input class="form-control" type="hidden" name="contador" value="{{ $cliente->contador }}">

                    <div class="col-4">
                      <label class="form-label" for="passwordwizard">Nome do Cliente<span class="txt-danger">*</span></label>
                      <input class="form-control"  type="text" name="nome" value="{{$cliente->cliente->nome}}" readonly style="background-color: #f5f5f5; color: #555;" >
                    </div>

                    <div class="col-4">
                      <label class="form-label" for="passwordwizard">Bairro<span class="txt-danger">*</span></label>
                      <input class="form-control"  type="text" name="endereco" value="{{$cliente->bairro}}" readonly style="background-color: #f5f5f5; color: #555;" >
                    </div>

                    <div class="col-4">
                      <label class="form-label" for="passwordwizard">Quarteirao<span class="txt-danger">*</span></label>
                      <input class="form-control"  type="text" name="nome" value="{{$cliente->quarteirao}}" readonly style="background-color: #f5f5f5; color: #555;" >
                    </div>

                    <div class="col-4">
                      <label class="form-label" for="passwordwizard">Casa<span class="txt-danger">*</span></label>
                      <input class="form-control"  type="text" name="endereco" value="{{$cliente->casa}}" readonly style="background-color: #f5f5f5; color: #555;" >
                    </div>

                    <div class="col-4">
                      <label class="form-label" for="latitude">Latitude <span class="txt-danger">*</span></label>
                      <input class="form-control" type="text" id="latitude" name="latitude" value="{{ $cliente->latitude ?? '' }}">
                    </div>

                    <div class="col-4">
                      <label class="form-label" for="longitude">Longitude <span class="txt-danger">*</span></label>
                      <input class="form-control" type="text" id="longitude" name="longitude" value="{{ $cliente->longitude ?? '' }}">
                    </div>


                  </form1>
                  <div class="wizard-footer d-flex gap-2 justify-content-end">
                      <button id="botao_salvar" type="submit" class="btn btn-success w-100">
                          <span id="botao_texto">{{__('Actualizacao Geolocalizacao')}} </span>
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
document.addEventListener("DOMContentLoaded", () => {
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(
      function (position) {
        document.getElementById('latitude').value = position.coords.latitude;
        document.getElementById('longitude').value = position.coords.longitude;
      },
      function (error) {
        console.warn("Erro ao obter localização:", error.message);
      }
    );
  } else {
    alert("Geolocalização não é suportada pelo seu navegador.");
  }
});
</script>

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
            latitude: {
                required: true,
            },
            longitude: {
                required: true,
            }
     },
        submitHandler: function(form) {
            $.ajax({
                type: "POST",
                url: "{{route('geolocalizacao.store')}}",
                data: $(form).serialize(), // Corrigido para usar `form` em vez de `this`

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

