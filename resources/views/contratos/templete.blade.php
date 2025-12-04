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

                    <input type="hidden" name="conteudo" id="conteudo">

                    <div class="col-sm-12">
                      <div class="card">
                        <div class="card-body">
                          <div class="toolbar-box">
                            <div id="toolbar7"><span class="ql-formats">
                                <select class="ql-size">
                                  <option value="small">Small</option>
                                  <option selected="">Normal</option>
                                  <option value="large">Large</option>
                                  <option value="huge">Huge</option>
                                </select></span><span class="ql-formats">
                                <button class="ql-bold">Bold</button>
                                <button class="ql-italic">Italic</button>
                                <button class="ql-underline">Underline</button>
                                <button class="ql-strike">Strike</button>
                                <button class="ql-script" value="sub"></button>
                                <button class="ql-script" value="super"></button></span><span class="ql-formats">
                                <button class="ql-header" value="1"></button>
                                <button class="ql-header" value="2"></button></span><span class="ql-formats">
                                <button class="ql-list" value="ordered">List</button>
                                <button class="ql-list" value="bullet">Bullet</button>
                                <button class="ql-indent" value="-1"></button>
                                <button class="ql-indent" value="+1"></button></span><span class="ql-formats">
                                <button class="ql-link">Link</button>
                                <button class="ql-image">Image</button>
                                <button class="ql-video">Video</button>
                                <select class="ql-color"></select>
                                <select class="ql-background"></select></span>
                              <!-- Add more options here--><span class="ql-formats">
                                <button class="ql-blockquote">Blockquote</button>
                                <button class="ql-code-block"></button></span><span class="ql-formats">
                                <button class="ql-align" value=""></button>
                                <button class="ql-align" value="center"></button>
                                <button class="ql-align" value="right"></button>
                                <button class="ql-align" value="justify"></button></span><span class="ql-formats"> 
                                <button class="ql-clean"></button></span>
                            </div>
                            <div class="quill-paragraph" id="editor7">
                              <p></p>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>

                  </form1>
                  <div class="wizard-footer d-flex gap-2 justify-content-end">
                      <button id="botao_salvar" type="submit" class="btn btn-success w-100">
                          <span id="botao_texto">{{__('Registar Templete')}} </span>
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
var quill = new Quill('#editor7', {
    theme: 'snow',
    modules: {
        toolbar: '#toolbar7'
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
            counteudo: {
                required: true,
                minlength: 2,
            },
            
      },
        submitHandler: function(form) {

            // 1️⃣ CAPTURAR O CONTEÚDO DO QUILL
            let html = quill.root.innerHTML;
            $("#conteudo").val(html);
            
            $.ajax({
                type: "POST",
                url: "{{route('contrato.templete')}}",
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

