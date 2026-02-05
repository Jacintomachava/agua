<div class="sidebar-wrapper" data-sidebar-layout="stroke-svg">
    <div>
    <div class="logo-wrapper"><a href="#">
        @if(Auth::user()->empresa->logotipo==null)
            <img class="img-fluid" src="{{ URL('/images/logotipo.png')}}" width="100" height="100" alt=""></a>
        @else
            <img class="img-fluid" src="{{ URL('/logotipo/'.Auth::user()->empresa->logotipo)}}" width="100" height="100" alt=""></a>
        @endif
        <div class="back-btn"><i class="fa fa-angle-left"></i></div>
        <div class="toggle-sidebar"><i class="status_toggle middle sidebar-toggle" data-feather="grid"> </i></div>
    </div>
    <div class="logo-icon-wrapper"><a href="#">
        <img class="img-fluid" src="{{ URL('/images/logotipo.png')}}"  width="100" height="100" alt=""></a></div>
    <div class="profile-section sidebar-search"> 
        <div class="profile-wrapper">
        <div class="active-profile"> <img class="img-fluid"  src="{{ URL('/avatar/'.Auth::user()->avatar) }}" alt="user">
            <div class="status bg-success"> </div>
        </div>
            <div> 
                <h4>{{ Auth::user()->telefone }}</h4>
                <span>Utilizador</span>
            </div>
        </div>
        <div>
            <svg>
                <use href="{{ URL('/assets/svg/icon-sprite.svg#profile-setting') }}"></use>
            </svg>
        </div>
    </div>
    <div class="sidebar-search"> 
        <div class="input-group">
          Agua Mati
        </div>
    </div>
    <nav class="sidebar-main">
        <div class="left-arrow" id="left-arrow"><i data-feather="arrow-left"></i></div>
        <div id="sidebar-menu">
        <ul class="sidebar-links" id="simple-bar">
            <li class="back-btn">
                <a href="#">
                  <img class="img-fluid" src="{{ URL('/avatar/'.Auth::user()->avatar) }}" alt="">
                </a>
            </li>

            @if(Auth::user()->empresa->subscricao==1 && Auth::user()->tipo=='Normal')
                <li class="sidebar-list"><i class="fa fa-thumb-tack"></i>
                    <label class="badge badge-light-primary">1</label><a class="sidebar-link sidebar-title" href="{{route('dashbord.indexHome')}}">
                        <svg class="stroke-icon">
                        <use href="{{ URL('/assets/svg/icon-sprite.svg#stroke-home') }}"></use>
                        </svg><span class="lan-3">Home</span></a>
                </li>

                <li class="sidebar-list"><i class="fa fa-thumb-tack"></i>
                    <label class="badge badge-light-primary">1</label><a class="sidebar-link sidebar-title" href="{{route('cliente.index')}}">
                        <span class="lan-3"><i style="font-size: 15pt; padding-right: 5pt;" class="icofont icofont-users-social"></i>Clientes</span></a>
                </li>

                <li class="sidebar-list"><i class="fa fa-thumb-tack"></i>
                    <label class="badge badge-light-primary">1</label><a class="sidebar-link sidebar-title" href="{{route('leituras.index')}}">
                        <span class="lan-3"><i style="font-size: 15pt; padding-right: 5pt;" class="icofont icofont-lamp-light"></i>Leituras</span></a>
                </li>

                <li class="sidebar-list"><i class="fa fa-thumb-tack"></i>
                    <label class="badge badge-light-primary">1</label><a class="sidebar-link sidebar-title" href="{{route('pagamento.index')}}">
                        <span class="lan-3"><i style="font-size: 15pt; padding-right: 5pt;" class="icofont icofont-cur-dollar-true"></i>Pagamentos</span></a>
                </li>

                <li class="sidebar-list"><i class="fa fa-thumb-tack"></i>
                    <label class="badge badge-light-primary">1</label><a class="sidebar-link sidebar-title" href="{{route('financas.index')}}">
                        <span class="lan-3"><i style="font-size: 15pt; padding-right: 5pt;" class="icofont icofont-chart-histogram"></i>Finanças</span></a>
                </li>


                <li class="sidebar-list"><i class="fa fa-thumb-tack"></i>
                    <label class="badge badge-light-primary">1</label><a class="sidebar-link sidebar-title" href="{{route('mensagem.index')}}">
                        <svg class="stroke-icon">
                        <use href="{{ URL('/assets/svg/icon-sprite.svg#stroke-chat') }}"></use>
                        </svg><span class="lan-3">Credito & SMS</span></a>
                </li>


                <li class="sidebar-list"><i class="fa fa-thumb-tack"></i>
                    <label class="badge badge-light-primary">7</label><a class="sidebar-link sidebar-title" href="#">
                        <svg class="stroke-icon">
                        <use href="{{ URL('/assets/svg/icon-sprite.svg#settings') }}"></use>
                        </svg><span class="lan-3">Parametrização</span></a>
                    <ul class="sidebar-submenu">
                        <li><a class="lan-1" href="{{route('furo.index')}}">Furos</a></li>
                        <li><a class="lan-2" href="{{route('contrato.index')}}">T. Contractos</a></li>
                        <li><a class="lan-2" href="{{route('mapa.index')}}">Tubo Geral</a></li>
                        <li><a class="lan-2" href="{{route('credencial.index')}}">Credenciais</a></li>
                    </ul>
                </li>
                

                <li class="sidebar-list"><i class="fa fa-thumb-tack"></i>
                    <label class="badge badge-light-primary">2</label><a class="sidebar-link sidebar-title" href="#">
                        <span class="lan-3"><i style="font-size: 15pt; padding-right: 5pt;" class="icofont icofont-users"></i>Administração</span></a>
                    <ul class="sidebar-submenu">
                        <li><a class="lan-1" href="{{route('userFuro.index')}}">Usuarios</a></li>
                        <li><a class="lan-2" href="{{route('nivel.index')}}">Roles</a></li>
                    </ul>
                </li>
                <li class="sidebar-list"><i class="fa fa-thumb-tack"></i>     
                    <label class="badge badge-light-primary">1</label><a class="sidebar-link sidebar-title" href="{{route('subscricao.index')}}">
                        <span class="lan-3"><i style="font-size: 19pt; padding-right: 5pt;" class="icofont icofont-cart-alt"></i> Recarregar </span></a>
                </li>

            @endif

            @if(Auth::user()->tipo=='CoWork')

                <li class="sidebar-list"><i class="fa fa-thumb-tack"></i>
                    <label class="badge badge-light-primary">1</label><a class="sidebar-link sidebar-title" href="{{route('cowork.index')}}">
                        <span class="lan-3"><i style="font-size: 19pt; padding-right: 5pt;" class="icofont icofont-pie-chart"></i>Comissões</span></a>
                </li>
            @endif

             @if(Auth::user()->tipo=='Dono')

                <li class="sidebar-list"><i class="fa fa-thumb-tack"></i>
                    <label class="badge badge-light-primary">1</label><a class="sidebar-link sidebar-title" href="{{route('sasDashbord.index')}}">
                        <span class="lan-3"><i style="font-size: 19pt; padding-right: 5pt;" class="icofont icofont-home"></i>Home</span></a>
                </li>
                <li class="sidebar-list"><i class="fa fa-thumb-tack"></i>
                    <label class="badge badge-light-primary">1</label><a class="sidebar-link sidebar-title" href="{{route('listarEmpresas.index')}}">
                        <span class="lan-3"><i style="font-size: 19pt; padding-right: 5pt;" class="icofont icofont-building"></i>Empresas</span></a>
                </li>

                <li class="sidebar-list"><i class="fa fa-thumb-tack"></i>
                    <label class="badge badge-light-primary">1</label><a class="sidebar-link sidebar-title" href="{{route('listarCoworks.index')}}">
                        <span class="lan-3"><i style="font-size: 19pt; padding-right: 5pt;" class="icofont icofont-users"></i>Coworks</span></a>
                </li>
                <li class="sidebar-list"><i class="fa fa-thumb-tack"></i>     
                    <label class="badge badge-light-primary">1</label><a class="sidebar-link sidebar-title" href="{{route('subscricao.index')}}">
                        <span class="lan-3"><i style="font-size: 19pt; padding-right: 5pt;" class="icofont icofont-cart-alt"></i>Subscrição </span></a>
                </li>

                <li class="sidebar-list"><i class="fa fa-thumb-tack"></i>
                    <label class="badge badge-light-primary">1</label><a class="sidebar-link sidebar-title" href="{{route('cowork.index')}}">
                        <span class="lan-3"><i style="font-size: 19pt; padding-right: 5pt;" class="icofont icofont-pie-chart"></i>Comissões</span></a>
                </li>
            @endif
                


        </ul>
        </div>
        <div class="right-arrow" id="right-arrow"><i data-feather="arrow-right"></i></div>
    </nav>
    </div>
</div>