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
                <h3 class="f-w-600">Total Clientes</h3>
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
                <h2>{{count($clientes)}}</h2>
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
                <h3 class="f-w-600">Leituras Pendentes</h3>
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
                <h2>{{count($leituras)}}</h2>
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
                <h3 class="f-w-600">Saldo SMS</h3>
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
       <a href="{{route('mensagem.index')}}">  
        <div class="card project-widget widget-1 title-line">
            <div class="card-header card-no-border pb-0"> 
            <div class="header-top">
                <div> 
                <h3 class="f-w-600">Saldo Sistema</h3>
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
                <h2>{{$saldo->saldo_sistema}} MT</h2>
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
    <div class="col-md-6 card">
        <div id="graficoConsumo" style="width: 100%; height: 400px;"></div>
    </div>
    <div class="col-md-6 card">
        <div id="graficoClientes" style="width: 100%; height: 400px;"></div>
    </div>
    <div class="col-md-6 card">
        <div id="graficoLeituras" style="width: 100%; height: 400px;"></div>
    </div>
    <div class="col-md-6 card">
         <div id="graficoPagamentos" style="width: 100%; height: 400px;"></div>
    </div>
</div>


@endsection


@push('js')

<script src="https://cdn.jsdelivr.net/npm/echarts/dist/echarts.min.js"></script>

<script>
    var chartDom = document.getElementById('graficoConsumo');
    var myChart = echarts.init(chartDom);

    var option = {
        title: {
            text: 'Consumo de Água por Mês ',
            left: 'center'
        },
        tooltip: {
            trigger: 'axis'
        },
        xAxis: {
            type: 'category',
            data: @json($labels)
        },
        yAxis: {
            type: 'value',
            name: 'Consumo'
        },
        series: [
            {
                name: 'Consumo',
                type: 'bar',
                data: @json($valores)
            }
        ]
    };

    myChart.setOption(option);
</script>

<script>
    var chartDom = document.getElementById('graficoPagamentos');
    var myChart = echarts.init(chartDom);

    var option = {
        title: {
            text: 'Número de Pagamentos por Forma',
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
            type: 'value',
            name: 'Nº de Pagamentos'
        },
        series: [
            {
                name: 'Pagamentos',
                type: 'bar',
                data: @json($valores1)
            }
        ]
    };

    myChart.setOption(option);
</script>

<script>
    var chartDom = document.getElementById('graficoLeituras');
    var myChart = echarts.init(chartDom);

    var option = {
        title: {
            text: 'Estado das Leituras',
            left: 'center'
        },
        tooltip: {
            trigger: 'item'
        },
        legend: {
            bottom: '0%',
            left: 'center'
        },
        series: [
            {
                name: 'Leituras',
                type: 'pie',
                radius: '60%',
                data: [
                    { value: {{ $leiturasFeitas }}, name: 'Leituras Feitas' },
                    { value: {{ $leiturasPendentes }}, name: 'Leituras Pendentes' }
                ],
                emphasis: {
                    itemStyle: {
                        shadowBlur: 10,
                        shadowOffsetX: 0
                    }
                }
            }
        ]
    };

    myChart.setOption(option);
</script>

<script>
    var chartDom = document.getElementById('graficoClientes');
    var myChart = echarts.init(chartDom);

    var option = {
        title: {
            text: 'Estado dos Clientes',
            left: 'center'
        },
        tooltip: {
            trigger: 'item'
        },
        legend: {
            bottom: '0%',
            left: 'center'
        },
        series: [
            {
                name: 'Clientes',
                type: 'pie',
                radius: '60%',
                data: [
                    {
                        value: {{ $clientesAtivos }},
                        name: 'Ativos',
                        itemStyle: { color: '#28a745' } // verde
                    },
                    {
                        value: {{ $clientesInativos }},
                        name: 'Inativos',
                        itemStyle: { color: '#dc3545' } // vermelho
                    }
                ],
                label: {
                    formatter: '{b}: {d}%'
                },
                emphasis: {
                    itemStyle: {
                        shadowBlur: 10,
                        shadowOffsetX: 0
                    }
                }
            }
        ]
    };

    myChart.setOption(option);
</script>


@endpush