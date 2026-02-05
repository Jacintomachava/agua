@extends('layouts.app')

@push('css')

@endpush



@section('conteudo')

<div class="row">
  
    <div class="col-xl-3 col-md-4 col-sm-6">
        <div class="card project-widget widget-1 title-line">
            <div class="card-header card-no-border pb-0"> 
            <div class="header-top">
                <div> 
                <h3 class="f-w-600">Mês Actual</h3>
                </div>
                <div class="card-header-right-icon">
                <div class="dropdown icon-dropdown d-xxl-none1">
                    <button class="btn dropdown-toggle" id="active-project" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <svg>
                        <use href="{{ URL('/assets/svg/icon-sprite.svg#more-horizontal') }}"></use>
                    </svg>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="active-project">
                    </div>
                </div>
                </div>
            </div>
            <div class="widget-middle-content">
                <div class="d-flex align-items-center"> 
                <h2>{{$pagamentoMes}}</h2>
                    <span class="f-w-500 txt-warning f-12"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trending-up me-1"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline><polyline points="17 6 23 6 23 12"></polyline></svg><span>Ano 2026</span></span>
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
                <h3 class="f-w-600">Dividas</h3>
                </div>
                <div class="card-header-right-icon">
                <div class="dropdown icon-dropdown d-xxl-none1">
                    <button class="btn dropdown-toggle" id="active-project" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <svg>
                        <use href="{{ URL('/assets/svg/icon-sprite.svg#more-horizontal') }}"></use>
                    </svg>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="active-project">
                    </div>
                </div>
                </div>
            </div>
            <div class="widget-middle-content">
                <div class="d-flex align-items-center"> 
                <h2>{{$clientesDivida}}</h2>
                    <span class="f-w-500 txt-warning f-12"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trending-up me-1"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline><polyline points="17 6 23 6 23 12"></polyline></svg><span>+100%</span></span>
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
                <h3 class="f-w-600">Saldo</h3>
                </div>
                <div class="card-header-right-icon">
                <div class="dropdown icon-dropdown d-xxl-none1">
                    <button class="btn dropdown-toggle" id="active-project" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <svg>
                        <use href="{{ URL('/assets/svg/icon-sprite.svg#more-horizontal') }}"></use>
                    </svg>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="active-project">
                    </div>
                </div>
                </div>
            </div>
            <div class="widget-middle-content">
                <div class="d-flex align-items-center"> 
                <h2>{{$valorContractos}}</h2>
                    <span class="f-w-500 txt-warning f-12"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trending-up me-1"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline><polyline points="17 6 23 6 23 12"></polyline></svg><span>+100%</span></span>
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
       <a href="{{route('mensagem.index')}}">  
        <div class="card project-widget widget-1 title-line">
            <div class="card-header card-no-border pb-0"> 
            <div class="header-top">
                <div> 
                <h3 class="f-w-600">Despesas</h3>
                </div>
                <div class="card-header-right-icon">
                <div class="dropdown icon-dropdown d-xxl-none1">
                    <button class="btn dropdown-toggle" id="active-project" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <svg>
                        <use href="{{ URL('/assets/svg/icon-sprite.svg#more-horizontal') }}"></use>
                    </svg>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="active-project">
                    </div>
                </div>
                </div>
            </div>
            <div class="widget-middle-content">
                <div class="d-flex align-items-center"> 
                <h2>{{$valorDespesa}}</h2>
                    <span class="f-w-500 txt-warning f-12"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trending-up me-1"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline><polyline points="17 6 23 6 23 12"></polyline></svg><span>00</span></span>
                </div>
            </div>
            </div>
            <div class="card-body widget-bottom-content">
            <div class="progress" style="height: 5px;">
                <div class="progress-bar bg-success" role="progressbar" style="width: 56%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
            </div>
        </div>
      </a>  
    </div>

</div>

<div class="row">

    <div class="col-xxl-12" > 
    <div class="card title-line">
        <div class="card-body"> 
        <ul class="nav nav-tabs border-tab mb-0" id="bottom-tab" role="tablist">
            <li class="nav-item" role="presentation"><a class="nav-link nav-border txt-info tab-info active" id="bottom-inbox-tab" data-bs-toggle="tab" href="#bottom-inbox" role="tab" aria-controls="bottom-inbox" aria-selected="true"><i class="icofont icofont-cart-alt"></i>Pagamentos</a></li>
            <li class="nav-item" role="presentation"><a class="nav-link nav-border txt-info tab-info" id="bottom-home-leitura" data-bs-toggle="tab" href="#bottom-leitura" role="tab" aria-controls="bottom-leitura" aria-selected="false" tabindex="-1"><i class="icofont icofont-lamp-light"> </i>Despesas</a></li>
            <li class="nav-item" role="presentation" hidden><a class="nav-link nav-border txt-info tab-info" id="bottom-home-mensagem" data-bs-toggle="tab" href="#bottom-mensagem" role="tab" aria-controls="bottom-mensagem" aria-selected="false" tabindex="-1"><i class="icofont icofont-ui-message"> </i>Mensagem</a></li>
            <li class="nav-item" role="presentation"><a class="nav-link nav-border txt-info tab-info" id="bottom-home-encarregado" data-bs-toggle="tab" href="#bottom-encarregado" role="tab" aria-controls="bottom-encarregado" aria-selected="false" tabindex="-1"><i class="icofont icofont-growth"> </i>Estatistica</a></li>
        </ul>
        <!-- Inscricoes -->
        <div class="tab-content" id="bottom-tabContent">
           
            <!-- Estastica -->
            <div class="tab-pane fade" id="bottom-encarregado" role="tabpanel" aria-labelledby="bottom-encarregado">

                <div class="row">
                    <div class="col-md-6">
                        <div id="graficoDespesaCategoria" style="width:600px; height:400px;"></div>
                    </div>
                    <div class="col-md-6">
                        <div id="graficoFinanceiro" style="width:600px; height:400px;"></div>
                    </div>
                    <div class="col-md-6">
                        <div id="graficoLeituraMes" style="width:600px; height:400px;"></div>
                    </div>
                    <div class="col-md-6">
                        <div id="graficoBanco" style="width:600px; height:400px;"></div>
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
                                <th scope="col">Recibo</th>
                                <th scope="col">Cliente</th>
                                <th scope="col">Valor</th>
                                <th scope="col">Estado</th>
                                <th scope="col">Tipo</th>
                                <th scope="col">Forma</th>
                                <th scope="col">Caixa</th>
                                <th scope="col">Data</th>
                                <th scope="col">Hora</th>
                              </tr>
                            </thead>
                            <tbody>

                                @foreach($recibos as $recibo)
                                    <tr>
                                        <td>{{$recibo->pagamento->numero_recibo}}</td>
                                        <td>{{$recibo->cliente->nome}}</td>
                                        <td>{{$recibo->valor}}</td>
                                        <td>{{$recibo->pagamento->estado}}</td>
                                        <td>{{$recibo->pagamento->tipo->nome}}</td>
                                        <td>{{$recibo->pagamento->banco->nome}}</td>
                                        <td>{{$recibo->pagamento->user->nome}}</td>
                                        <td>{{ \Carbon\Carbon::parse($recibo->updated_at)->format('d-M-Y') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($recibo->updated_at)->format('H:s') }}</td>
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
                                <th scope="col">Categoria</th>
                                <th scope="col">Valor Despesa</th>
                                <th scope="col">Valor Pago</th>
                                <th scope="col">Saldo</th>
                                <th scope="col">Data</th>
                                <th scope="col">Hora</th>
                              </tr>
                            </thead>
                            <tbody>

                                @foreach($despesas as $despesa)
                                    <tr>
                                        <td>{{$despesa->categoria->nome}}</td>
                                        <td>{{$despesa->valor_despesa}}</td>
                                        <td>{{$despesa->valor_pago}}</td>
                                        <td>{{$despesa->valor_despesa-$despesa->valor_pago}}</td>
                                        <td>{{ \Carbon\Carbon::parse($despesa->updated_at)->format('d-M-Y') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($despesa->updated_at)->format('H:s') }}</td>
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

            
            </div>
            <!-- Fim Matriculas -->
        </div>


        </div>
    </div>
</div>

</div>

@endsection


@push('js')

<script src="https://cdn.jsdelivr.net/npm/echarts/dist/echarts.min.js"></script>

<script>
var chartDom = document.getElementById('graficoDespesaCategoria');
var myChart = echarts.init(chartDom);

var option = {
    title: {
        text: 'Despesas por Categoria',
        left: 'center'
    },
    tooltip: {
        trigger: 'item',
        formatter: '{b}: {c} MT ({d}%)'
    },
    series: [
        {
            type: 'pie',
            radius: '60%',
            data: @json($pieData)
        }
    ]
};

myChart.setOption(option);
</script>

<script>
var chartDom = document.getElementById('graficoFinanceiro');
var myChart = echarts.init(chartDom);

var option = {
    title: {
        text: 'Financeiro Mensal',
        left: 'center'
    },
    tooltip: {
        trigger: 'axis'
    },
    legend: {
        bottom: 0
    },
    xAxis: {
        type: 'category',
        data: @json($labels)
    },
    yAxis: {
        type: 'value'
    },
    series: [
        {
            name: 'Receitas',
            type: 'line',
            smooth: true,
            data: @json($receitaData)
        },
        {
            name: 'Despesas',
            type: 'line',
            smooth: true,
            data: @json($despesaData)
        },
        {
            name: 'Lucro',
            type: 'line',
            smooth: true,
            data: @json($lucroData)
        }
    ]
};

myChart.setOption(option);
</script>

<script>

var chartDom = document.getElementById('graficoBanco');
var myChart = echarts.init(chartDom);

var option = {
    title: {
        text: 'Pagamentos por Banco',
        left: 'center'
    },
    tooltip: {
        trigger: 'item',
        formatter: '{b}: {c} MT ({d}%)'
    },
    legend: {
        bottom: 0
    },
    series: [
        {
            type: 'pie',
            radius: '60%',
            data: @json($pieData1)
        }
    ]
};

myChart.setOption(option);

</script>

<script>

var chartDom = document.getElementById('graficoLeituraMes');
var myChart = echarts.init(chartDom);

var option = {
    title: {
        text: 'Leituras Pagas por Mês',
        left: 'center'
    },
    tooltip: {
        trigger: 'axis'
    },
    xAxis: {
        type: 'category',
        data: @json($labels1)
    },
    yAxis: {
        type: 'value'
    },
    series: [
        {
            name: 'Valor Pago',
            type: 'bar',
            data: @json($valores1)
        }
    ]
};

myChart.setOption(option);

</script>

@endpush