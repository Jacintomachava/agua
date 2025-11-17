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
                    <h2>Fazer Pagamento</h2>
                    <p class="f-m-light mt-1">
                        Faça o Pagamento 
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


                    <input class="form-control"  type="hidden" name="id" value="{{$leitura->id}}">
                    <input class="form-control"  type="hidden" name="furo" value="{{$leitura->furo_id }}">
                    <input class="form-control"  type="hidden" name="fatura" value="{{$leitura->numero_factura}}"  >
                    <input class="form-control"  type="hidden" name="furo_cliente_contrato" value="{{$leitura->furoClienteContrato->id}}">

                    <div class="col-3">
                      <label class="form-label" for="passwordwizard">Nome do Cliente<span class="txt-danger">*</span></label>
                      <input class="form-control"  type="text" name="nome" value="{{$leitura->furoClienteContrato->cliente->nome}}" readonly style="background-color: #f5f5f5; color: #555;" >
                    </div>

                    <div class="col-3">
                      <label class="form-label" for="passwordwizard">Bairro<span class="txt-danger">*</span></label>
                      <input class="form-control"  type="text" name="bairro" value="{{$leitura->furoClienteContrato->bairro}}" readonly style="background-color: #f5f5f5; color: #555;">
                    </div>

                    <div class="col-3">
                      <label class="form-label" for="passwordwizard">Quarteirao<span class="txt-danger">*</span></label>
                      <input class="form-control"  type="text" name="quarteirao" value="{{$leitura->furoClienteContrato->quarteirao}}" readonly style="background-color: #f5f5f5; color: #555;" >
                    </div>

                    <div class="col-3">
                      <label class="form-label" for="passwordwizard">Casa<span class="txt-danger">*</span></label>
                      <input class="form-control"  type="text" name="casa"  value="{{$leitura->furoClienteContrato->casa}}" readonly style="background-color: #f5f5f5; color: #555;">
                    </div>

                    <div class="col-3">
                      <label class="form-label" for="passwordwizard">Valor Consumo {{$leitura->mes->nome}}<span class="txt-danger">*</span></label>
                      <input class="form-control"  type="text" name="consumo" value="{{$valor}}" readonly style="background-color: #f5f5f5; color: #555;">
                    </div>

                    <div class="col-3">
                      <label class="form-label" for="passwordwizard">Divida<span class="txt-danger">*</span></label>
                      <input class="form-control"  type="text" name="divida" value="{{$leitura->furoClienteContrato->divida}}" readonly style="background-color: #f5f5f5; color: #555;">
                    </div>

                    <div class="col-3">
                      <label class="form-label" for="passwordwizard">Multa (%)<span class="txt-danger">*</span></label>
                      <input class="form-control"  type="text" name="multa" value="{{$leitura->multa}}" readonly style="background-color: #f5f5f5; color: #555;">
                    </div>

                    <div class="col-3">
                      <label class="form-label" for="passwordwizard">Valor Total<span class="txt-danger">*</span></label>
                      <input class="form-control"  type="text" name="valor_total" value="{{ number_format(($leitura->valor_a_pagar + $leitura->furoClienteContrato->divida) + (($leitura->valor_a_pagar + $leitura->furoClienteContrato->divida) * $leitura->multa / 100), 2,',','.') }}" readonly style="background-color: #f5f5f5; color: #555;">
                    </div>

                    <div class="col-3">
                      <label class="form-label" for="passwordwizard">Valor a Pagar<span class="txt-danger">*</span></label>
                      <input class="form-control"  type="text" name="valor_pago"  >
                    </div>

                      <div class="col-3">
                         <label class="form-label" for="passwordwizard">Forma Pagamento<span class="txt-danger">*</span></label>
                           <select class="form-select"  name="forma_pagamento" id="forma" onchange="getBancos()" >
                              @foreach($formasPagamentos as $formasPagamento)
                                <option value="{{$formasPagamento->id}}">{{$formasPagamento->nome}}</option>
                              @endforeach
                           </select>
                      </div>

                      <div class="col-3">
                        <label class="form-label">Banco Carteira</label>
                        <select class="form-select" name="banco_carteira" id="bancos">
                            <option value="">Selecione o Banco/Carteira</option>
                        </select>
                      </div>

                    <div class="col-3">
                      <label class="form-label" for="passwordwizard">Nova Divida<span class="txt-danger">*</span></label>
                      <input class="form-control"  type="text" name="nova_divida" readonly style="background-color: #f5f5f5; color: #555;" >
                    </div>


                  </form1>
                  <div class="wizard-footer d-flex gap-2 justify-content-end">
                      <button id="botao_salvar" type="submit" class="btn btn-success w-100">
                          <span id="botao_texto">{{__('Registar Pagamento')}} </span>
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

    function getBancos() {
        let provinciaID = document.getElementById('forma').value;
        let distritoSelect = document.getElementById('bancos');
        
        // Limpa o select de distritos
        distritoSelect.innerHTML = '<option value="">Carregando...</option>';

        if (provinciaID) {
            fetch(`/api/bancos/carteiras/${provinciaID}`)
                .then(response => response.json())
                .then(data => {
                    distritoSelect.innerHTML = '<option value="">Selecione o Banco Carteira</option>';
                    data.forEach(distrito => {
                        distritoSelect.innerHTML += `<option value="${distrito.id}">${distrito.nome}</option>`;
                    });
                })
                .catch(error => {
                    distritoSelect.innerHTML = '<option value="">Erro ao carregar</option>';
                    console.error('Erro:', error);
                });
        } else {
            distritoSelect.innerHTML = '<option value="">Selecione a Forma de pagamento primeiro</option>';
        }
    }

</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const valorTotalInput = document.querySelector('input[name="valor_total"]');
    const novaDividaInput = document.querySelector('input[name="nova_divida"]');
    const valorPagoInput = document.querySelector('input[name="valor_pago"]');

    // Função para converter string formatada para float
    function parseCurrency(value) {
        if (!value) return 0;
        // remove pontos e substitui vírgula por ponto
        return parseFloat(value.replace(/\./g, '').replace(',', '.')) || 0;
    }

    // Função para formatar float em moeda brasileira
    function formatCurrency(value) {
        return value.toLocaleString('pt-MZ', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    valorPagoInput.addEventListener('input', function() {
        const total = parseCurrency(valorTotalInput.value);
        const pago = parseCurrency(valorPagoInput.value);
        const novaDivida = total - pago;

        novaDividaInput.value = formatCurrency(novaDivida >= 0 ? novaDivida : 0);
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
            valor_pago: {
                required: true,
                max: {{ ($leitura->valor_a_pagar + $leitura->furoClienteContrato->divida) + (($leitura->valor_a_pagar + $leitura->furoClienteContrato->divida) * $leitura->multa / 100) }},
                min: 100
            }
       },
        submitHandler: function(form) {
            $.ajax({
                type: "POST",
                url: "{{route('pagamentos.store')}}",
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

