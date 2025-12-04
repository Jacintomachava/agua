@extends('layouts.app')

@push('css')

@endpush

@section('conteudo')

<div class="row">
    <div class="col-md-8 col-sm-8">
        <h3><b>Comissões de {{Auth::user()->nome}}</b></h3>
    </div>
    <div class="col-md-4 col-sm-4">
        
    </div>
</div>

<br>

<div class="row">
    
     <div class="col-md-3 col-sm-3" >
       <a href="{{route('minhas.empresas')}}">
        <div class="widget-stat card bg-primary card-shadow" style="background-color: green; color: white">
            <div class="card-body p-4">
                <div class="media">
                    <div class="media-body text-white" >
                        <p class="mb-1">Total das Empresas</p>
                        <h4 class="text-white">{{$nrEmpresa}}</h4>
                        <div class="progress mb-2 bg-secondary">
                            <div class="progress-bar progress-animated bg-white" style="width: 100%;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
      </a>  
    </div>

    <div class="col-md-3 col-sm-3">
      <a href="{{route('minhas.mensalidades')}}">
        <div class="widget-stat card bg-danger card-shadow" style="background-color: red; color: white" >
            <div class="card-body p-4">
                <div class="media">
                    <div class="media-body text-white" >
                        <p class="mb-1">Subscrição </p>
                        <h4 class="text-white">{{$totalSubscricao}} MT</h4>
                        <div class="progress mb-2 bg-primary">
                            <div class="progress-bar progress-animated bg-white" style="width: 100%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
      </a>
    </div>

    <div class="col-md-3 col-sm-3">
      <a href="{{route('credito.mensagem')}}">
        <div class="widget-stat card bg-warning card-shadow" style="background-color: red; color: white">
            <div class="card-body p-4">
                <div class="media">
                    <div class="media-body text-white" >
                        <p class="mb-1">Crédito </p>
                        <h4 class="text-white">{{ number_format($totalReceberGeral, 2, ',', '.') }} MT </h4>
                        <div class="progress mb-2 bg-primary">
                            <div class="progress-bar progress-animated bg-white" style="width: 100%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
      </a>
    </div>

    <div class="col-md-3 col-sm-3">
      <a href="{{route('levantamento')}}">
        <div class="widget-stat card bg-success card-shadow" >
            <div class="card-body p-4">
                <div class="media">
                    <div class="media-body text-white" >
                        <p class="mb-1">Saldo</p>
                        <h4 class="text-white">{{$saldo->saldo}} MT</h4>
                        <div class="progress mb-2 bg-primary">
                            <div class="progress-bar progress-animated bg-white" style="width: 100%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
     </a>
    </div>

</div>

<div class="row">

    <div class="col-md-6 col-sm-6" style="background-color: white; padding: 10px">
        <!-- Container do ECharts -->
        <div id="graficoLucro" style="width: 600px; height: 400px;"></div>
    </div>

    <div class="col-md-6 col-sm-6" style="background-color: white; padding: 10px">
        <!-- Container do ECharts -->
        <div id="graficoLucroUser" style="width: 600px; height: 400px;"></div>
    </div>

</div>

@endsection

@push('js')

<!-- ECharts CDN -->
<script src="https://cdn.jsdelivr.net/npm/echarts/dist/echarts.min.js"></script>
<script>
    // Inicializa o gráfico
    var chartDom = document.getElementById('graficoLucro');
    var myChart = echarts.init(chartDom);

    // Configuração do gráfico
    var option = {
        title: {
            text: 'Lucro por Mês Credito',
            left: 'center'
        },
        tooltip: {
            trigger: 'item',
            formatter: '{b}: R$ {c} ({d}%)'
        },
        legend: {
            orient: 'vertical',
            left: 'left',
            data: @json($meses)
        },
        series: [
            {
                name: 'Lucro',
                type: 'pie',
                radius: '50%',
                data: @json($dadosGrafico),
                emphasis: {
                    itemStyle: {
                        shadowBlur: 10,
                        shadowOffsetX: 0,
                        shadowColor: 'rgba(0, 0, 0, 0.5)'
                    }
                }
            }
        ]
    };

    // Renderiza o gráfico
    myChart.setOption(option);
</script>

<script>
    var chartDom = document.getElementById('graficoLucroUser');
    var myChart = echarts.init(chartDom);

    var option = {
        title: {
            text: 'Lucro por Mês Subscrição',
            left: 'center'
        },
        tooltip: {
            trigger: 'item',
            formatter: '{b}: R$ {c} ({d}%)'
        },
        legend: {
            orient: 'vertical',
            left: 'right',
            data: @json($meses)
        },
        series: [
            {
                name: 'Lucro',
                type: 'pie',
                radius: '50%',
                data: @json($dadosGrafico2),
                emphasis: {
                    itemStyle: {
                        shadowBlur: 10,
                        shadowOffsetX: 0,
                        shadowColor: 'rgba(0, 0, 0, 0.5)'
                    }
                }
            }
        ]
    };

    myChart.setOption(option);
</script>

@endpush
