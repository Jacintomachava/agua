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
                    <h2>Pagamento Da Mensalidade de Sistema</h2>
                    <p class="f-m-light mt-1">
                        Faça o pagamento do sistema
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
                      <label class="form-label" for="passwordwizard">Factura<span class="txt-danger">*</span></label>
                      <input class="form-control"  type="text" name="codigo" value="{{$mensalidade->codigo}}" readonly style="background-color: #f5f5f5; color: #555;">
                    </div>

                    <div class="col-4">
                      <label class="form-label" for="passwordwizard">Mes<span class="txt-danger">*</span></label>
                      <input class="form-control"  type="text" name="mes" value="{{$mensalidade->mes->nome}}-{{$mensalidade->ano->ano}}" readonly style="background-color: #f5f5f5; color: #555;" >
                    </div>

                    <div class="col-4">
                      <label class="form-label" for="passwordwizard">Nr. Cliente<span class="txt-danger">*</span></label>
                      <input class="form-control"  type="text" name="clientes" value="{{$mensalidade->clientes}}" readonly style="background-color: #f5f5f5; color: #555;">
                    </div>

                    <div class="col-4">
                      <label class="form-label" for="passwordwizard">Multa<span class="txt-danger">*</span></label>
                      <input class="form-control"  type="text" name="multa" value="{{$mensalidade->multa}}%" readonly style="background-color: #f5f5f5; color: #555;">
                    </div>

                    <div class="col-4">
                      <label class="form-label" for="passwordwizard">Valor a pagar<span class="txt-danger">*</span></label>
                      <input class="form-control"  type="text" name="valor" value="{{($mensalidade->clientes * $mensalidade->valor_por_cliente) * (1 + $mensalidade->multa / 100)}}" readonly style="background-color: #f5f5f5; color: #555;">
                    </div>

                    <div class="col-4">
                      <label class="form-label" for="passwordwizard">Telefone Mpesa<span class="txt-danger">*</span></label>
                      <input class="form-control"  type="text" name="telefone"  >
                    </div>

                  </form1>
                  <div class="col-4"></div>  
                  <div class="col-4">
                      <button id="botao_salvar" type="submit" class="btn btn-success w-100">
                        <span id="botao_texto">{{__('Efectuar o Pagamento')}}</span>
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
            telefone: {
                required: true,
                minlength: 9,
                maxlength: 9,
            }
     },
        submitHandler: function(form) {
            $.ajax({
                type: "POST",
                url: "{{route('pagamentoSubscricao.store')}}",
                data: $(form).serialize(), // Corrigido para usar `form` em vez de `this`

                beforeSend: function () {
                    // Desabilita o botão de envio e altera o ícone para mostrar que está autenticando
                    $('#botao_salvar').attr('disabled', true);
                    $('#icon_enviar').removeClass('ri-arrow-right-line').addClass('spinner-border ri-loader-2-line');
                    $('#botao_texto').text('Efectuar o Pagamento...');
                },

                success: function(response) {
                    // Habilita o botão e retorna o ícone original
                    $('#botao_salvar').attr('disabled', false);
                    $('#icon_enviar').removeClass('spinner-border ri-loader-2-line').addClass('ri-arrow-right-line');
                    $('#botao_texto').text('Efectuar o Pagamento');

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
                    $('#botao_texto').text('Efectuar o Pagamento');

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

