<div class="sidebar-wrapper" data-sidebar-layout="stroke-svg">
    <div>
    <div class="logo-wrapper"><a href="#">
        <img class="img-fluid" src="{{ URL('/images/logotipo.png')}}" width="100" height="100" alt=""></a>
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


                <li class="sidebar-list"><i class="fa fa-thumb-tack"></i>
                    <label class="badge badge-light-primary">1</label><a class="sidebar-link sidebar-title" href="#">
                        <svg class="stroke-icon">
                        <use href="{{ URL('/assets/svg/icon-sprite.svg#stroke-learning') }}"></use>
                        </svg><span class="lan-3">Home</span></a>
                </li>

                <li class="sidebar-list"><i class="fa fa-thumb-tack"></i>
                    <label class="badge badge-light-primary">1</label><a class="sidebar-link sidebar-title" href="{{route('cliente.index')}}">
                        <svg class="stroke-icon">
                        <use href="{{ URL('/assets/svg/icon-sprite.svg#stroke-form') }}"></use>
                        </svg><span class="lan-3">Clientes</span></a>
                </li>

                <li class="sidebar-list"><i class="fa fa-thumb-tack"></i>
                    <label class="badge badge-light-primary">1</label><a class="sidebar-link sidebar-title" href="{{route('leituras.index')}}">
                        <svg class="stroke-icon">
                        <use href="{{ URL('/assets/svg/icon-sprite.svg#stroke-form') }}"></use>
                        </svg><span class="lan-3">Leituras</span></a>
                </li>

                <li class="sidebar-list"><i class="fa fa-thumb-tack"></i>
                    <label class="badge badge-light-primary">1</label><a class="sidebar-link sidebar-title" href="{{route('pagamento.index')}}">
                        <svg class="stroke-icon">
                        <use href="{{ URL('/assets/svg/icon-sprite.svg#stroke-form') }}"></use>
                        </svg><span class="lan-3">Pagamentos</span></a>
                </li>


                <li class="sidebar-list"><i class="fa fa-thumb-tack"></i>
                    <label class="badge badge-light-primary">1</label><a class="sidebar-link sidebar-title" href="{{route('mensagem.index')}}">
                        <svg class="stroke-icon">
                        <use href="{{ URL('/assets/svg/icon-sprite.svg#stroke-form') }}"></use>
                        </svg><span class="lan-3">Credito</span></a>
                </li>


                <li class="sidebar-list"><i class="fa fa-thumb-tack"></i>
                    <label class="badge badge-light-primary">7</label><a class="sidebar-link sidebar-title" href="#">
                        <svg class="stroke-icon">
                        <use href="{{ URL('/assets/svg/icon-sprite.svg#stroke-learning') }}"></use>
                        </svg><span class="lan-3">Parametrização</span></a>
                    <ul class="sidebar-submenu">
                        <li><a class="lan-1" href="{{route('furo.index')}}">Furos</a></li>
                        <li><a class="lan-2" href="{{route('contrato.index')}}">T. Contractos</a></li>
                    </ul>
                </li>
                

                <li class="sidebar-list"><i class="fa fa-thumb-tack"></i>
                    <label class="badge badge-light-primary">2</label><a class="sidebar-link sidebar-title" href="#">
                        <svg class="stroke-icon">
                        <use href="{{ URL('/assets/svg/icon-sprite.svg#stroke-learning') }}"></use>
                        </svg><span class="lan-3">Administração</span></a>
                    <ul class="sidebar-submenu">
                        <li><a class="lan-1" href="{{route('userFuro.index')}}">Usuarios</a></li>
                        <li><a class="lan-2" href="{{route('nivel.index')}}">Roles</a></li>
                    </ul>
                </li>


        </ul>
        </div>
        <div class="right-arrow" id="right-arrow"><i data-feather="arrow-right"></i></div>
    </nav>
    </div>
</div>