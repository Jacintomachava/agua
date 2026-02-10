@extends('layouts.app')

@push('css')
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<style>
    grid: {
        left: '8%',
        right: '4%',
        bottom: '10%',
        containLabel: true
    },
</style>
@endpush

@section('conteudo')

<div class="row">

    <div class="col-xl-6 box-col-6">
        <div class="card title-line upgrade-card overflow-hidden">
            <div class="row align-items-end"> 
            <div class="col-sm-8 col-11"> 
                <div class="card-header"> 
                    <h6> Preço do Sistema:  {{$empresa->valor_por_cliente}} MT Por Cliente</h6>
                    <h6> Preço Por SMS:  1.85 MT </h6>
                    <h6> Preço Por WhatsApp:  0.02 MT </h6>
                    <a class="btn btn-primary btn-hover-effect btn-sm" data-bs-toggle="modal" data-bs-target=".bd-example-modal-lg">Comprar Saldo
                        <svg class="svg-sprite">
                          <use href="{{ URL('/assets/svg/icon-sprite.svg#logout')}}"> </use>
                        </svg>
                    </a>
                </div>
            </div>
            </div>
            <div class="cartoon-image"> <img class="img-fluid" src="{{ URL('/assets/images/dashboard/1.png')}}" alt="vector"></div><img class="img-fluid pattern-image" src="{{ URL('/assets/images/dashboard/bg-1.png')}}" alt="vector pattern">
        </div>
    </div>

    <div class="col-xl-3 col-md-4 col-sm-6">
        <div class="card project-widget widget-1 title-line">
            <div class="card-header card-no-border pb-0"> 
            <div class="header-top">
                <div> 
                <h3 class="f-w-600">Saldo SMS  </h3>
                </div>
                <div class="card-header-right-icon">
                <div class="dropdown icon-dropdown d-xxl-none1">
                    <button class="btn dropdown-toggle" id="active-project" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <svg>
                        <use href="{{ URL('/assets/svg/icon-sprite.svg#more-horizontal')}}"></use>
                    </svg>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="active-project">
                    </div>
                </div>
                </div>
            </div>
            <br>
            <div class="widget-middle-content">
                <div class="d-flex align-items-center"> 
                  <h2>{{$saldo->saldo}} MT</h2>
                </div>
            </div>
            </div>
            <div class="card-body widget-bottom-content">
                <div class="progress" style="height: 5px;">
                    <div class="progress-bar bg-success" role="progressbar" style="width: 56%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-4 col-sm-6">
        <div class="card project-widget widget-1 title-line">
            <div class="card-header card-no-border pb-0"> 
            <div class="header-top">
                <div> 
                <h3 class="f-w-600">Saldo Sistema </h3>
                </div>
                <div class="card-header-right-icon">
                <div class="dropdown icon-dropdown d-xxl-none1">
                    <button class="btn dropdown-toggle" id="active-project" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <svg>
                        <use href="{{ URL('/assets/svg/icon-sprite.svg#more-horizontal')}}"></use>
                    </svg>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="active-project">
                    </div>
                </div>
                </div>
            </div>
            <br>
            <div class="widget-middle-content">
                <div class="d-flex align-items-center"> 
                  <h2>
                    {{$saldo->saldo_sistema}} MT
                  </h2>
                </div>
            </div>
            </div>
            <div class="card-body widget-bottom-content">
                <div class="progress" style="height: 5px;">
                    <div class="progress-bar bg-success" role="progressbar" style="width: 56%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>

                </div>
            </div>
        </div>
    </div>

</div>


<div class="col-xxl-12" > 
    <div class="card title-line">
        <div class="card-body"> 
        <ul class="nav nav-tabs border-tab mb-0" id="bottom-tab" role="tablist">
            <li class="nav-item" role="presentation"><a class="nav-link nav-border txt-info tab-info active" id="bottom-inbox-tab" data-bs-toggle="tab" href="#bottom-inbox" role="tab" aria-controls="bottom-inbox" aria-selected="true"><i class="icofont icofont-cart-alt"></i>Saldo Comprado</a></li>
            <li class="nav-item" role="presentation"><a class="nav-link nav-border txt-info tab-info" id="bottom-home-encarregado" data-bs-toggle="tab" href="#bottom-encarregado" role="tab" aria-controls="bottom-encarregado" aria-selected="false" tabindex="-1"><i class="icofont icofont-growth"> </i>Estatistica</a></li>
            <li class="nav-item" role="presentation"><a class="nav-link nav-border txt-info tab-info" id="bottom-home-leitura" data-bs-toggle="tab" href="#bottom-leitura" role="tab" aria-controls="bottom-leitura" aria-selected="false" tabindex="-1"><i class="icofont icofont-lamp-light"> </i>Leitura Mês</a></li>
            <li class="nav-item" role="presentation"><a class="nav-link nav-border txt-info tab-info" id="bottom-home-mensagem" data-bs-toggle="tab" href="#bottom-mensagem" role="tab" aria-controls="bottom-mensagem" aria-selected="false" tabindex="-1"><i class="icofont icofont-ui-message"> </i>Mensagem</a></li>
        </ul>
        <!-- Inscricoes -->
        <div class="tab-content" id="bottom-tabContent">
           
            <!-- Estastica -->
            <div class="tab-pane fade" id="bottom-encarregado" role="tabpanel" aria-labelledby="bottom-encarregado">

                <br>
                <div class="row">
                    <div class="col-sm-6">
                        <div id="graficoCredito" style="width: 600px; min-width: 500px; height: 400px;"></div>
                    </div>
                    <div class="col-sm-6">
                        <div id="graficoLeituraCredito" style="width:600px; min-width:600px; height:400px;"></div>
                    </div>
                </div>
            </div>
            <!-- Fim Encarregados -->  
        </div>
        <!-- Fim Inscricoes -->

        
        <div class="tab-content" id="bottom-tabContent">

             <!-- Credito Comprado -->
            <div class="tab-pane fade show active" id="bottom-inbox" role="tabpanel" aria-labelledby="bottom-inbox-tab">

                <br>
                <div class="col-sm-12"> 
                  <div class="card title-line">
                    <div class="card-block row">
                      <div class="col-sm-12 col-lg-12 col-xl-12">
                        <div class="table-responsive custom-scrollbar">
                          <table class="table">
                            <thead class="table-dark">
                              <tr>
                                <th scope="col">Codigo</th>
                                <th scope="col">Pacote</th>
                                <th scope="col">Tipo</th>
                                <th scope="col">Valor</th>
                                <th scope="col">Comprado Por:</th>
                                <th scope="col">Data</th>
                                <th scope="col">Hora</th>
                              </tr>
                            </thead>
                            <tbody>

                            @foreach($pacotes as $pacote)
                                <tr>
                                    <td>{{ $pacote->codigo }}</td>
                                    <td>{{ $pacote->tipo_pacote }}</td>
                                    <td>{{ $pacote->tipo }}</td>
                                    <td>{{ $pacote->valor }}MZN</td>
                                    <td>{{ $pacote->user->nome }}</td>
                                    <td>{{ \Carbon\Carbon::parse($pacote->updated_at)->format('d-M-Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($pacote->updated_at)->format('H:s') }}</td>
                                </tr>
                            @endforeach

                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
            
            </div>
            <!-- Fim Matriculas -->
        </div>

        <div class="tab-content" id="bottom-tabContent">

             <!-- Credito Comprado -->
            <div class="tab-pane fade" id="bottom-leitura" role="tabpanel" aria-labelledby="bottom-inbox-tab">

                <br>
                <div class="col-sm-12"> 
                  <div class="card title-line">
                    <div class="card-block row">
                      <div class="col-sm-12 col-lg-12 col-xl-12">
                        <div class="table-responsive custom-scrollbar">
                          <table class="table">
                            <thead class="table-dark">
                              <tr>
                                <th scope="col"></th>
                                <th scope="col">Nº</th>
                                <th>Nome</th>
                                <th>Mês</th>
                                <th>Consumo</th>
                                <th>Estado</th>
                                <th>Saldo</th>
                                <th scope="col">Data</th>
                                <th scope="col">Hora</th>
                              </tr>
                            </thead>
                            <tbody>

                            @foreach($leituras as $leitura)
                                <tr>
                                    <td></td>
                                    <td>{{$leitura->furoClienteContrato->contador }}</td>
                                    <td>{{$leitura->furoClienteContrato->cliente->nome }}</td>
                                    <td>{{$leitura->mes->nome}} </td>
                                    <td>{{$leitura->consumo}}m&sup3; </td>
                                    <td>
                                        @if($leitura->estado_leitura==0)
                                            <a class="btn btn-danger btn-xs activate-btn"  >
                                                Pendente
                                            </a>
                                        @else
                                            <a class="btn btn-success btn-xs activate-btn" >
                                                Feita
                                            </a>
                                        @endif
                                    </td>
                                    <td>{{$leitura->credito}} </td>
                                    <td>{{ \Carbon\Carbon::parse($leitura->updated_at)->format('d-M-Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($leitura->updated_at)->format('H:s') }}</td>

                                </tr>
                            @endforeach

                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
            
            </div>
            <!-- Fim Matriculas -->
        </div>

        <div class="tab-content" id="bottom-tabContent">

             <!-- Credito Comprado -->
            <div class="tab-pane fade" id="bottom-mensagem" role="tabpanel" aria-labelledby="bottom-inbox-tab">

                <br>
                              <div class="col-sm-12"> 
                <div class="card title-line">
                  <div class="card-block row">
                    <div class="col-sm-12 col-lg-12 col-xl-12">
                      <div class="table-responsive custom-scrollbar">
                        <table class="table">
                          <thead class="table-dark">
                            <tr>
                              <th scope="col">Contactos</th>
                              <th scope="col">Canal</th>
                              <th scope="col">Tipo</th>
                              <th scope="col">conteudo</th>
                              <th scope="col">Qtd</th>
                              <th scope="col">Credito</th>
                              <th scope="col">Data</th>
                              <th scope="col">Hora</th>
                            </tr>
                          </thead>
                          <tbody>

                          @foreach($mensagens as $mensagem)
                            <tr>
                              <td>@if($mensagem->nome!=null) {{$mensagem->nome}} @else {{$mensagem->telefone}} @endif </td>
                              <td>{{$mensagem->canal}}</td>
                              <td>
                                {{$mensagem->tipo}}
                              </td>
                              <td data-container="body" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="{{$mensagem->descricao}}">
                                {{ Str::limit($mensagem->descricao, 10) }}
                              </td>
                              <td>{{$mensagem->qtd}}</td>
                              <td>{{$mensagem->credito}} MT</td>
                              <td>
                                {{ \Carbon\Carbon::parse($mensagem->created_at)->format('d-M-Y') }}
                              </td>
                              <td>
                                {{ \Carbon\Carbon::parse($mensagem->created_at)->format('H:s') }}
                              </td>
                            </tr>
                          @endforeach

                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            
            </div>
            <!-- Fim Matriculas -->
        </div>


        </div>
    </div>
</div>

<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModal" aria-hidden="true">
    <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form  id="form">  
        <div class="modal-header">
            <h4 class="modal-title" id="myExtraLargeModal">Compra de Saldo</h4>
            <button class="btn-close py-0" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body dark-modal">

            @csrf

            <div class="row">
                

                <div class="col-4">
                    <label class="form-label" for="passwordwizard">Tipo<span class="txt-danger">*</span></label>
                    <select class="form-select"  name="tipo">
                            <option value="SISTEMA">SISTEMA</option>
                            <option value="SMS">SMS</option>
                    </select>
                </div>
                <div class="col-4">
                    <label class="form-label" for="passwordwizard">Digite Valor<span class="txt-danger">*</span></label>
                    <input class="form-control" type="text" name="valor" placeholder="valor Mpesa" >
                </div>

                <div class="col-4">
                    <label class="form-label" for="passwordwizard">Telefone Mpesa<span class="txt-danger">*</span></label>
                    <input class="form-control" type="text" name="telefone" placeholder="Numero Mpesa" >
                </div>


            </div>

        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cancelar</button>
            <button class="btn btn-primary" id="botao_salvar" type="submit" >
                <span id="botao_texto">{{__('Comprar Saldo')}}</span>
                <i id="icon_enviar" class="ri-arrow-right-line label-icon align-middle fs-16 ms-2"></i>
            </button>
        </div>
      </form>  
    </div>
    </div>
</div>

@endsection

@push('js')

<script src="https://cdn.jsdelivr.net/npm/echarts/dist/echarts.min.js"></script>

<script>
    var chartDom = document.getElementById('graficoCredito');
    var myChart = echarts.init(chartDom);

    var option = {
        title: {
            text: 'Saldo SMS por Mês ',
            left: 'center'
        },
        tooltip: {
            trigger: 'axis',
            formatter: '{b}: {c} MT'
        },
        grid: {
            left: '8%',
            right: '4%',
            bottom: '10%',
            containLabel: true
        },
        xAxis: {
            type: 'category',
            data: @json($labels1),
            axisLabel: {
                rotate: 40   // evita sobreposição
            }
        },
        yAxis: {
            type: 'value',
            name: 'Saldo (MT)'
        },
        series: [
            {
                name: 'Crédito',
                type: 'bar',
                data: @json($valores1),
                barMaxWidth: 500
            }
        ]
    };

    myChart.setOption(option);

    // 🔥 MUITO IMPORTANTE
    window.addEventListener('resize', function () {
        myChart.resize();
    });
</script>


<script>
    var chartDom = document.getElementById('graficoLeituraCredito');
    var myChart = echarts.init(chartDom);

    var option = {
        title: {
            text: 'Saldo Sistema por Mês ',
            left: 'center'
        },
        tooltip: {
            trigger: 'axis',
            formatter: '{b}: {c} MT'
        },
        grid: {
            left: '8%',
            right: '4%',
            bottom: '10%',
            containLabel: true
        },
        xAxis: {
            type: 'category',
            data: @json($labels),
            axisLabel: {
                rotate: 40
            }
        },
        yAxis: {
            type: 'value',
            name: 'Saldo (MT)'
        },
        series: [
            {
                name: 'Crédito',
                type: 'bar',
                data: @json($valores),
                barMaxWidth: 40
            }
        ]
    };

    myChart.setOption(option);

    // 🔄 Ajuste automático
    window.addEventListener('resize', function () {
        myChart.resize();
    });
</script>

  <script>

$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $("#form").validate({
        // Adicionar regras para cada campo
        rules: {
            valor: {
                required: true,
                min: 100,
                max: 10000
            },
            telefone: {
                required: true,
                minlength: 9,
                maxlength: 9,
            }
        },
        submitHandler: function(form) {
            $.ajax({
                type: "POST",
                url: "{{route('mensagem.storeCompraSMS1')}}",
                data: $(form).serialize(), // Corrigido para usar `form` em vez de `this`

                beforeSend: function () {
                    // Desabilita o botão de envio e altera o ícone para mostrar que está autenticando
                    $('#botao_salvar').attr('disabled', true);
                    $('#icon_enviar').removeClass('ri-arrow-right-line').addClass('spinner-border ri-loader-2-line');
                    $('#botao_texto').text('Comprando Credito...');
                },

                success: function(response) {
                    // Habilita o botão e retorna o ícone original
                    $('#botao_salvar').attr('disabled', false);
                    $('#icon_enviar').removeClass('spinner-border ri-loader-2-line').addClass('ri-arrow-right-line');
                    $('#botao_texto').text('Comprar Credito');

                    // Redireciona ou exibe uma mensagem de erro com base na resposta
                    if(response.status == 1) {

                      Swal.fire({
                            icon: 'success',
                            title: 'Sucesso!',
                            text: response.message,
                      });

                      window.location.reload();

                    } else if(response.status == 0) {

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
                    $('#botao_texto').text('Comprar Credito');

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
