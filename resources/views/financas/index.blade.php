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
                <h3 class="f-w-600">Total Hoje</h3>
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
                <h2>{{$totalPagoHoje}}</h2>
                    <span class="f-w-500 txt-warning f-12"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trending-up me-1"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline><polyline points="17 6 23 6 23 12"></polyline></svg><span>Ano 2025</span></span>
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
                <h3 class="f-w-600">Total Mes anterior</h3>
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
                <h2>{{$totalMesAnterior}}</h2>
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
                <h3 class="f-w-600">Total Pendente</h3>
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
                <h2>{{$totalPendente}}</h2>
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
                <h3 class="f-w-600">Total Contrato</h3>
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
                <h2>{{$totalContratos}}</h2>
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

<div class="col-sm-12" >
    <div class="card">
        <div class="card-header pb-0 card-no-border">
        </div>
        <div class="card-body">

            <div class="table-responsive custom-scrollbar">
                <table class="display" id="basic-1">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Valor</th>
                            <th>Estado</th>
                            <th>Tipo</th>
                            <th>Forma</th>
                            <th>Ban/Cart.</th>
                            <th>Nome</th>
                            <th>Data</th>
                            <th>Hora</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach($pagamentos as $pagamento)
                            <tr>
                                <td></td>
                                <td>{{$pagamento->valor }}</td>
                                <td>{{$pagamento->estado}}</td>
                                <td>{{$pagamento->tipo->nome}}</td>
                                <td>{{$pagamento->forma->nome}}</td>
                                <td>{{$pagamento->banco->nome}}</td>
                                <td>{{$pagamento->fatura->cliente->nome}}</td>
                                
                                <td>{{ $pagamento->created_at->format('d/m/Y') }}</td>
                                <td>{{ $pagamento->created_at->format('H:i') }}</td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

</div>

@endsection


@push('js')




@endpush