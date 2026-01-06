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
                <h3 class="f-w-600">Total Empresas</h3>
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
                <h2>{{$totalempresas}}</h2>
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
                <h2>{{$totalclientes}}</h2>
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
                <h3 class="f-w-600">Credito Disponivel</h3>
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
                <h2>{{$saldoDispinivel}}</h2>
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
                <h3 class="f-w-600">CoWorks</h3>
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
                <h2>{{$totalCowork}}</h2>
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
                <div class="col-xl-3 order-xl-v col-xl-50 col-md-4 col-sm-6 order-sm-i">
                    <div class="row"> 
                      <div class="col-12"> 
                        <div class="card widget-1"> 
                          <div class="card-body common-space order-card"> 
                            <div> <span class="f-w-500 f-light">Comissões Coworks </span>
                              <h2>{{$comissaoCowork}}MT</h2>
                              <div class="order-content"><span class="f-light f-12 f-w-500">Dezembro </span>
                                <div><span class="txt-danger f-12 f-w-600"><i class="stroke-danger reverse-icon" data-feather="trending-up"></i>-10%</span></div>
                              </div>
                            </div>
                            <div class="delivery-image"><img class="img-fluid" src="../assets/images/dashboard-2/orders/1.png" alt="order"></div>
                          </div>
                        </div>
                      </div>
                      <div class="col-12"> 
                        <div class="card widget-1"> 
                          <div class="card-body common-space order-card"> 
                            <div class="customer-month"><span class="f-w-500 f-light">Comissões Donos</span>
                              <h2>{{$comissaoDono}}MT</h2>
                              <div class="order-content"><span class="f-light f-12 f-w-500">Dezembro </span>
                                <div><span class="txt-success f-12 f-w-600"><i class="stroke-success" data-feather="trending-up"></i>-27%</span></div>
                              </div>
                            </div>
                            <div class="delivery-image"><img class="img-fluid" src="../assets/images/dashboard-2/orders/2.png" alt="order"></div>
                          </div>
                        </div>
                      </div>
                      <div class="col-12"> 
                          <div class="card widget-1"> 
                          <div class="card-body common-space order-card"> 
                            <div class="customer-month"><span class="f-w-500 f-light">Comissões Manutencao </span>
                              <h2>{{$comissaoManutencao}}</h2>
                              <div class="order-content"><span class="f-light f-12 f-w-500">Dezembro </span>
                                <div><span class="txt-success f-12 f-w-600"><i class="stroke-success" data-feather="trending-up"></i>-27%</span></div>
                              </div>
                            </div>
                            <div class="delivery-image"><img class="img-fluid" src="../assets/images/dashboard-2/orders/2.png" alt="order"></div>
                          </div>
                        </div>
                      </div>
                      <div class="col-12"> 
                          <div class="card widget-1"> 
                          <div class="card-body common-space order-card"> 
                            <div class="customer-month"><span class="f-w-500 f-light">Compra Credito</span>
                              <h2>{{$compraCredito}}</h2>
                              <div class="order-content"><span class="f-light f-12 f-w-500">Dezembro </span>
                                <div><span class="txt-success f-12 f-w-600"><i class="stroke-success" data-feather="trending-up"></i>-27%</span></div>
                              </div>
                            </div>
                            <div class="delivery-image"><img class="img-fluid" src="../assets/images/dashboard-2/orders/2.png" alt="order"></div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-xl-6 col-xl-100 order-xl-ii col-md-6">
                    <div class="card height-equal title-line">
                      <div class="card-header card-no-border">
                        <div class="header-top">
                          <h2>Ultimas Empresas</h2>
                          <div class="card-header-right-icon recent-order-header">
                            <div class="dropdown">
                              <button class="btn dropdown-toggle" id="orderButtons" type="button" data-bs-toggle="dropdown" aria-expanded="false">Todas Empresas</button>
                              <div class="dropdown-menu dropdown-menu-start" aria-labelledby="orderButtons">
                            </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="card-body project-datatable p-0 order-tables custom-scrollbar">
                        <table class="table" id="order-status">
                          <thead> 
                            <tr> 
                              <th> <span class="f-light f-w-600">Codigo</span></th>
                              <th> <span class="f-light f-w-600">Telefone</span></th>
                              <th> <span class="f-light f-w-600">Valor/Cliente</span></th>
                              <th> <span class="f-light f-w-600">Distrito</span></th>
                              <th> <span class="f-light f-w-600">Data</span></th>
                            </tr>
                          </thead>
                          <tbody> 

                          @foreach($empresas as $empresa)
                            <tr> 
                              <td> <a class="f-w-500" href="#!">#{{$empresa->codigo}}</a></td>
                              <td>
                                <span class="f-light">{{$empresa->telefone}}</span>
                              </td>
                              <td> <span class="txt-success f-w-500">{{$empresa->valor_por_cliente}}MT</span></td>
                              <td> <span class="f-light">{{$empresa->distrito->nome}}</span></td>
                              <td><span class="badge badge-light-primary"> {{$empresa->created_at->format('d-M-Y')}}   </span></td>
                            </tr>
                          @endforeach
                            
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>

                  <div class="col-xl-3 col-md-3">
                    <div class="card height-equal title-line delivery-card">
                      <div class="card-header card-no-border">
                        <div class="header-top">
                          <h2>Mensagens<span class="f-12 f-w-500 f-light d-block">Ultimas Mensagens</span></h2>
                          <div class="card-header-right-icon">
                            <div class="dropdown">
                              <button class="btn dropdown-toggle" id="OrderDetailsButtons1" type="button" data-bs-toggle="dropdown" aria-expanded="false">Todos Mensagens</button>
                              <div class="dropdown-menu dropdown-menu-start" aria-labelledby="OrderDetailsButtons1"></div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="card-body delivery-content pt-0">
                        <ul class="order-list mb-0 custom-scrollbar">
                         @foreach($mensagens as $mensagem)
                          <li>
                            <div class="light-card"> <img class="img-fluid" src="../assets/images/dashboard/product/2.png" alt="T-shirt"></div>
                            <div class="order-content">
                              <div> <a href="product-page.html"> 
                                  <h6 class="mb-1">{{$mensagem->empresa->nome}}</h6></a><span> <span class="f-light f-w-500 f-12">To : {{$mensagem->telefone}}</span></span></div>
                              <div class="text-end"> 
                                <div class="dropdown icon-dropdown">
                                  <button class="btn dropdown-toggle mb-1" id="order-2" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    {{$mensagem->updated_at->format('d-M-Y')}} 
                                  </button>
                                  <div class="dropdown-menu dropdown-menu-end" aria-labelledby="order-2">Add to cart</div>
                                </div><span class="badge badge-light-primary f-12">{{$mensagem->tipo}}({{$mensagem->credito}})</span>
                              </div>
                            </div>
                           </li>
                         @endforeach
                          
                        </ul>
                      </div>
                    </div>
                  </div>
</div>



@endsection


@push('js')



@endpush