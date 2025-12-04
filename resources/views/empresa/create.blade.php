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
                    <h2>Fazer registo de Empresa</h2>
                    <p class="f-m-light mt-1">
                        Faça o registo da Empresa
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
                      <label class="form-label" for="passwordwizard">Nome da Empresa<span class="txt-danger">*</span></label>
                      <input class="form-control"  type="text" name="nome"  >
                    </div>

                    <div class="col-4">
                      <label class="form-label" for="passwordwizard">Logotipo<span class="txt-danger"></span></label>
                      <input class="form-control"  type="file" name="logotipo" >
                    </div>

                    <div class="col-4">
                      <label class="form-label" for="passwordwizard">NUIT<span class="txt-danger">*</span></label>
                      <input class="form-control"  type="text" name="nuit" >
                    </div>

                    <div class="col-4">
                      <label class="form-label">Provincia</label>
                      <select class="form-select"  name="provincia" id="provincia" onchange="getDistritos()" >
                              <option value="">Selecione a Provincia</option>
                              @foreach($provincias as $provincia)
                                <option value="{{$provincia->id}}">{{$provincia->nome}}</option>
                              @endforeach
                      </select>
                    </div>

                    <div class="col-4">
                      <label class="form-label">Distrito</label>
                      <select class="form-select" name="distrito" id="distrito">
                          <option value="">Selecione o Distrito</option>
                      </select>
                    </div>

                    <div class="col-4">
                      <label class="form-label" for="longitude">Bairro <span class="txt-danger">*</span></label>
                      <input class="form-control" type="text" name="bairro" >
                    </div>

                    <div class="col-4">
                      <label class="form-label" for="longitude">Preço Por Cliente <span class="txt-danger">*</span></label>
                      <input class="form-control" type="text" name="preco" >
                    </div>

                    <div class="col-4">
                      <label class="form-label" for="latitude">Nome de Utilizador <span class="txt-danger">*</span></label>
                      <input class="form-control" type="text" name="nome_utilizador" >
                    </div>

                    <div class="col-4">
                      <label class="form-label" for="longitude">Telefone Utilizador <span class="txt-danger">*</span></label>
                      <input class="form-control" type="text" name="telefone_utilizador" >
                    </div>


                  </form1>
                  <div class="wizard-footer d-flex gap-2 justify-content-end">
                      <button id="botao_salvar" type="submit" class="btn btn-success w-100">
                          <span id="botao_texto">{{__('Registar Empresa')}} </span>
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
            preco: {
                required: true,
                     min: 30
            },
            nome: {
                required: true,
                minlength: 2,
                maxlength: 200,
            },
            nuit: {
                required: true,
                minlength: 9,
                maxlength: 9,
            },
            provincia: {
                required: true
            },
            distrito: {
                required: true
            },
            bairro: {
                required: true
            },
            nome_utilizador: {
                required: true
            },
            telefone_utilizador: {
                required: true,
                minlength: 9,
                maxlength: 9,
            }
      },
        submitHandler: function(form) {

            let formData = new FormData(form); // captura todo o formulário inclusive arquivo

            $.ajax({
                type: "POST",
                url: "{{ route('empresa.storeCoWork') }}",
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

<script>

  function getDistritos() {
      let provinciaID = document.getElementById('provincia').value;
      let distritoSelect = document.getElementById('distrito');
      
      // Limpa o select de distritos
      distritoSelect.innerHTML = '<option value="">Carregando...</option>';

      if (provinciaID) {
          fetch(`/api/distritos/${provinciaID}`)
              .then(response => response.json())
              .then(data => {
                  distritoSelect.innerHTML = '<option value="">Selecione o Distrito</option>';
                  data.forEach(distrito => {
                      distritoSelect.innerHTML += `<option value="${distrito.id}">${distrito.nome}</option>`;
                  });
              })
              .catch(error => {
                  distritoSelect.innerHTML = '<option value="">Erro ao carregar</option>';
                  console.error('Erro:', error);
              });
      } else {
          distritoSelect.innerHTML = '<option value="">Selecione a Província primeiro</option>';
      }
  }

</script>

@endpush

