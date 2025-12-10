@extends('layouts.app')

@push('css')

@endpush

@section('conteudo')

<div class="col-sm-12">
    <div class="card title-line">
        <div class="card-header bg-dark">
          <h5 class="text-white">{{$cliente->cliente->nome}}</h5>
          <div class="card-header-right dark-color-header">
              <h5 class="text-white">
                {{$cliente->codigo}} 
              </h5>
          </div>
        </div>
        <div class="card-body bg-dark">
        <div class="d-flex align-items-center gap-3 pills-blogger"> 
            <div class="blog-wrapper col-md-3"> 
               <div class="customers d-inline-block avatar-group">
                <ul>
                <li class="d-inline-block">
                  @if($cliente->empresa->logotipo==null)
                    <img class="img-80 b-r-30" src="{{ URL('/images/logotipo.png')}}" alt="user profile">
                  @else
                   <img class="img-80 b-r-30" src="{{ URL('/logotipo/'.$cliente->empresa->logotipo)}}" alt="user profile">
                  @endif
                </li>
                 <li class="d-inline-block">
                     
                    <img class="img-100 b-r-8" src="{{ URL('/avatar/avatar.png') }}" alt="user profile">

                </li>
                </ul>
              </div>
            </div>

            <div class="col-md-4"> 
              <b>Nome de Aluno:</b><span style="padding-left: 1%"> {{$cliente->cliente->nome}}</span><br>
              <b>Tipo Documento:</b><span style="padding-left: 1%"> {{$cliente->cliente->tipo_documento}}</span><br>
              <b>Distrito:</b><span style="padding-left: 1%"> {{$cliente->distrito->nome}}</span><br>
              <b>Bairro:</b><span style="padding-left: 1%"> {{$cliente->bairro}}</span><br>
              <b>Telefone:</b><span style="padding-left: 1%"> {{$cliente->telefone_notificar}}</span><br>
              <b>Saldo:</b><span style="padding-left: 1%"> {{number_format($cliente->saldo, 2, ',', '.') }} MT</span><br>

            </div>

            <div class="col-md-5"> 
              <b>Data Registo:</b><span style="padding-left: 1%"> {{ \Carbon\Carbon::parse($cliente->created_at)->format('d-M-Y') }}</span><br>
              <b>Numero de Documento:</b><span style="padding-left: 1%">{{$cliente->cliente->numero_documento}}</span><br>
              <b>Quarteirao:</b><span style="padding-left: 1%">{{$cliente->cliente->quarteirao}}</span><br>
              <b>Casa:</b><span style="padding-left: 1%">{{ $cliente->cliente->casa }}</span><br>
              <b>Contracto:</b><span style="padding-left: 1%"> @if($cliente->ligacao_activa==1) Activa @else Cortada @endif</span><br>
              <b>Divida:</b><span style="padding-left: 1%">{{number_format($cliente->divida, 2, ',', '.')  }} MT</span><br>
            </div>

        </div>
        </div>
        <div class="card-footer bg-dark">
          <h6 class="mb-0 txt-light"> 
              <a class="btn btn-warning btn-xs activate-btn" target="_blank" href="{{route('extracto.cliente',['codigo'=>$cliente->codigo])}}" >
                  Imprimir Extracto
              </a>
              <a class="btn btn-info btn-xs activate-btn" target="_blank" href="{{route('contrato.cliente',['codigo'=>$cliente->codigo])}}" >
                  Imprimir Contracto
              </a>
              <a class="btn btn-secondary btn-xs activate-btn" href="{{route('cliente.edit',['codigo'=>$cliente->codigo])}}" >
                  Editar Cliente
              </a>
          </h6>
        </div>
    </div>
</div>

<div class="col-xxl-12" style="margin-top: -2%"> 
    <div class="card title-line">
        <div class="card-body"> 
        <ul class="nav nav-tabs border-tab mb-0" id="bottom-tab" role="tablist">
            <li class="nav-item" role="presentation"><a class="nav-link nav-border txt-info tab-info active" id="bottom-inbox-tab" data-bs-toggle="tab" href="#bottom-inbox" role="tab" aria-controls="bottom-inbox" aria-selected="true"><i class="icofont icofont-hat"></i>Leituras</a></li>
            <li class="nav-item" role="presentation"><a class="nav-link nav-border txt-info tab-info" id="bottom-home-encarregado" data-bs-toggle="tab" href="#bottom-encarregado" role="tab" aria-controls="bottom-encarregado" aria-selected="false" tabindex="-1"><i class="icofont icofont-medical-sign"> </i>Pagamentos</a></li>
            <li class="nav-item" role="presentation"><a class="nav-link nav-border txt-info tab-info" id="bottom-contact-tab" data-bs-toggle="tab" href="#bottom-contact" role="tab" aria-controls="bottom-contact" aria-selected="false" tabindex="-1"><i class="icofont icofont-man-in-glasses"></i>Mensagem</a></li>
            <li class="nav-item" role="presentation"><a class="nav-link nav-border txt-info tab-info" id="bottom-home-tab" data-bs-toggle="tab" href="#bottom-home" role="tab" aria-controls="bottom-home" aria-selected="false" tabindex="-1" hidden><i class="icofont icofont-headphone-alt-2"> </i>Plano Estudo</a></li>
            <li class="nav-item" role="presentation"><a class="nav-link nav-border txt-info tab-info" id="bottom-home-tab-club" data-bs-toggle="tab" href="#bottom-register" role="tab" aria-controls="bottom-home" aria-selected="false" tabindex="-1"><i class="icofont icofont-medical-sign"> </i>Contracto</a></li>
        </ul>
        <!-- Inscricoes -->
        <div class="tab-content" id="bottom-tabContent">
          <div class="tab-pane fade" id="bottom-register" role="tabpanel" aria-labelledby="bottom-home-tab">
              
              <br>
              <div class="d-flex align-items-center gap-3 pills-blogger" style="border: 5px solid black; box-shadow: 5px 5px 10px gray; padding: 10px" >
                    <div class="col-md-3"> 
                        <br>
                        <b><center>{{$cliente->contrato->nome}}</center></b>
                        <center>@if($cliente->ligacao_activa==1) Activa @else Cortada @endif </center><br>
                    </div>
                    <div class="col-md-4"> 
                      <b>Valor de Contracto: </b><span style="padding-left: 3%">{{number_format($cliente->contrato->valor_contrato, 2, ',', '.') }} MT</span><br>
                      <b>Consumo Minimo:</b><span style="padding-left: 3%"> {{$cliente->contrato->consumo_minimo}}/m&sup3;</span><br>
                    </div>

                    <div class="col-md-5"> 
                      <b>Valor Consumo: </b><span style="padding-left: 3%"> {{number_format($cliente->contrato->valor, 2, ',', '.') }}MT/m&sup3; </span><br>
                      <b>valor Consumo Minimo: </b><span style="padding-left: 3%"> {{number_format($cliente->contrato->valor*$cliente->contrato->consumo_minimo, 2, ',', '.') }}MT </span><br>
                   </div>
              </div>

          </div>  
          
            <!-- Encarregados -->
            <div class="tab-pane fade" id="bottom-encarregado" role="tabpanel" aria-labelledby="bottom-encarregado">

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
                                  <th scope="col">Factura</th>
                                  <th scope="col">Tipo</th>
                                  <th scope="col">Forma</th>
                                  <th scope="col">Canal</th>
                                  <th scope="col">Valor</th>
                                  <th scope="col">Data</th>
                                </tr>
                              </thead>
                              <tbody>

                              @foreach($recibos as $recibo)
                                <tr>
                                  <td>{{$recibo->numero_recibo}}</td>
                                  <td>{{$recibo->numero_factura}}</td>
                                  <td>{{$recibo->tipoPagamento->nome}}</td>
                                  <td>{{ $recibo->pagamento->forma->nome }}</td>
                                  <td>{{$recibo->pagamento->banco->nome}}</td>
                                  <td>{{number_format($recibo->pagamento->valor_a_pagar, 2, ',', '.')}}</td>
                                  <td>{{$recibo->created_at->format('d-M-Y')}}</td>
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
            <!-- Fim Encarregados -->  
        </div>
        <!-- Fim Inscricoes -->

        
        <div class="tab-content" id="bottom-tabContent">

            <!-- Contactos Urgentes -->
            <div class="tab-pane fade" id="bottom-home" role="tabpanel" aria-labelledby="bottom-home-tab">
              <br>
            </div>
            <!-- Fim Contactos Urgentes -->


             <!-- Matriculas Leituras-->
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
                              <th scope="col">Leitura</th>
                              <th scope="col">Consumo</th>
                              <th scope="col">Factura</th>
                              <th scope="col">Valor</th>
                              <th scope="col">Pagamento</th>
                              <th scope="col">Lido Por:</th>
                              <th scope="col">Data</th>
                            </tr>
                          </thead>
                          <tbody>

                          @foreach($leituras as $leitura)
                            <tr>
                              <td>{{$leitura->valor_leitura}}m&sup3;</td>
                              <td>{{$leitura->consumo}}m&sup3;</td>
                              <td>{{$leitura->numero_factura}}</td>
                              <td>{{ number_format($leitura->valor_a_pagar, 2, ',', '.') }}</td>
                              <td>{{$leitura->estado_pagamento}}</td>
                              <td><i>{{$leitura->LeituraFeitoPor->nome}}</i></td>
                              <td>{{$leitura->data_leitura->format('d-M-Y')}}</td>
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

            <!-- Recolher Crianca -->
            <div class="tab-pane fade" id="bottom-contact" role="tabpanel" aria-labelledby="bottom-contact-tab">
                 <br>
                 <div class="col-sm-12"> 
                    <div class="card title-line">
                      <div class="card-block row">
                        <div class="col-sm-12 col-lg-12 col-xl-12">
                          <div class="table-responsive custom-scrollbar">
                            <table class="table">
                              <thead class="table-dark">
                                <tr>
                                  <th scope="col">Contacto</th>
                                  <th scope="col">Canal</th>
                                  <th scope="col">QTD</th>
                                  <th scope="col">Credito</th>
                                  <th scope="col">Descricao</th>
                                  <th scope="col">Estado</th>
                                  <th scope="col">Data</th>
                                  <th scope="col">Hora</th>
                                </tr>
                              </thead>
                              <tbody>

                                @foreach($mensagens as $mensagem)
                                  <tr>
                                    <td>{{$mensagem->telefone}}</td>
                                    <td>{{$mensagem->canal}}</td>
                                    <td>{{$mensagem->qtd}}</td>
                                    <td>{{$mensagem->credito }}</td>
                                    <td >
                                      <i data-container="body" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="{{$mensagem->descricao}}">{{ Str::limit($mensagem->descricao, 40) }}</i>
                                    </td>
                                    <td>{{$mensagem->tipo}}</td>
                                    <td>{{$mensagem->updated_at->format('d-M-Y')}}</td>
                                    <td>{{$mensagem->updated_at->format('H:s')}}</td>
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
            <!-- Fim Recolher Crianca -->
            
        </div>
        </div>
    </div>
</div>

@endsection

@push('js')

@endpush
