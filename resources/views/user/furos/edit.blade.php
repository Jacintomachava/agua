@extends('layouts.app')

@push('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
@endpush

@section('conteudo')

  <div class="container-fluid">
    <div class="row">
      <div class="col-xl-12">
        <div class="card height-equal title-line">
          <div class="card-header">
            <div class="row">
                <div class="col-7">
                    <h2>Actualizar o Utilizador</h2>
                    <p class="f-m-light mt-1">
                        Faça a actualização do Utilizador
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

                    <input class="form-control"  type="text" name="user_id" value="{{$user->id}}" hidden >

                    <div class="col-4">
                      <label class="form-label" for="passwordwizard">Nome do Utilizador<span class="txt-danger">*</span></label>
                      <input class="form-control"  type="text" name="nome" value="{{$user->nome}}" >
                    </div>

                    <div class="col-4">
                      <label class="form-label" for="passwordwizard">Telefone<span class="txt-danger">*</span></label>
                      <input class="form-control"  type="text" name="telefone" value="{{$user->telefone}}" >
                    </div>

                   <div class="col-4">
                      <label class="form-label">Provincia</label>
                      <select class="form-select"  name="provincia" id="provincia" onchange="getDistritos()" >
                              <option value="">Selecione a Provincia</option>
                                @foreach($provincias as $provincia)
                                    <option value="{{$provincia->id}}"
                                        {{ $user->distrito->provincia->id == $provincia->id ? 'selected' : '' }}>
                                        {{$provincia->nome}}
                                    </option>
                                @endforeach
                      </select>
                    </div>

                    <div class="col-4">
                      <label class="form-label">Distrito</label>
                      <select class="form-select" name="distrito" id="distrito">
                           <option value="">Selecione o Distrito</option>
                          @foreach($distritos as $distrito)
                             <option value="{{$distrito->id}}"  @if($user->distrito_id == $distrito->id) selected @endif >{{$distrito->nome}}</option>
                          @endforeach
                      </select>
                    </div>

                    <div class="form-group col-4">
                        <label for="roles" class="form-label">Atribuir Funcoes<span class="txt-danger">*</span></label>
                        <select class="form-select contactos" name="roles[]" multiple="multiple" >
                            @foreach($roles as $role)
                                <option value="{{ $role->name }}" {{ in_array($role->name, $userRoles) ? 'selected' : '' }}>
                                    {{ $role->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-4">
                        <label for="furos" class="form-label">Atribuir Furo(s)<span class="txt-danger">*</span></label>
                        <select class="form-select contactos1" name="furos[]" multiple="multiple">
                            @foreach($furos as $furo)
                                <option value="{{ $furo->id }}" {{ in_array($furo->id, $userFuros) ? 'selected' : '' }} >
                                    {{ $furo->nome }} - {{$furo->endereco}}
                                </option>
                            @endforeach
                        </select>
                    </div>


                  </form1>
                  <div class="wizard-footer d-flex gap-2 justify-content-end">
                      <button id="botao_salvar" type="submit" class="btn btn-success w-100">
                          <span id="botao_texto">{{__('Actualizar utilizador')}} </span>
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

<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

<script>
    $(document).ready(function() {
        $('.contactos').select2({
            placeholder: "{{ __('Seleciona a(s) Funcoes') }}",
            allowClear: true
        });
    });
</script>

<script>
    $(document).ready(function() {
        $('.contactos1').select2({
            placeholder: "{{ __('Seleciona o(s) Furo(s)') }}",
            allowClear: true
        });
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
            nome: {
                required: true,
                minlength: 2,
            },
            telefone: {
                required: true,
                minlength: 9,
                maxlength: 9
            },
            provincia: {
                required: true,
            },
            distrito: {
                required: true,
            },
            roles: {
                required: true,
            },
            furos: {
                required: true,
            }
     },
        submitHandler: function(form) {
            $.ajax({
                type: "POST",
                url: "{{route('userFuro.update')}}",
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

