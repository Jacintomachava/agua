<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Yuri admin is super flexible, powerful, clean &amp; modern responsive bootstrap 5 admin template with unlimited possibilities.">
    <meta name="keywords" content="admin template, Yuri admin template, dashboard template, flat admin template, responsive admin template, web app">
    <meta name="author" content="pixelstrap">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="icon" href="{{ URL('/public/assets/images/logo/logotipo.jpg')}}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ URL('/public/assets/images/logo/logotipo.jpg')}}" type="image/x-icon">
    <title>Login</title>
    <!-- Google font-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&amp;family=Nunito+Sans:ital,wght@0,300;0,400;0,700;0,800;0,900;1,700&amp;display=swap" rel="stylesheet">
    <!-- Font Awesome-->
    <link rel="stylesheet" type="text/css" href="{{ URL('/assets/css/font-awesome.css')}}">
    <!-- ico-font-->
    <link rel="stylesheet" type="text/css" href="{{ URL('/assets/css/vendors/icofont.css')}}">
    <!-- Themify icon-->
    <link rel="stylesheet" type="text/css" href="{{ URL('/assets/css/vendors/themify.css')}}">
    <!-- Flag icon-->
    <link rel="stylesheet" type="text/css" href="{{ URL('/assets/css/vendors/flag-icon.css')}}">
    <!-- Feather icon-->
    <link rel="stylesheet" type="text/css" href="{{ URL('/assets/css/vendors/feather-icon.css')}}">
    <!-- Plugins css start-->
    <!-- Plugins css Ends-->
    <!-- Bootstrap css-->
    <link rel="stylesheet" type="text/css" href="{{ URL('/assets/css/vendors/bootstrap.css')}}">
    <!-- App css-->
    <link rel="stylesheet" type="text/css" href="{{ URL('/assets/css/style.css')}}">
    <!-- Responsive css-->
    <link rel="stylesheet" type="text/css" href="{{ URL('/assets/css/responsive.css')}}">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
      .error {
          color: red;
          margin: 0;
      }
    </style>


  </head>
  <body>
    <!-- login page start-->
    <div class="container-fluid">
      <div class="row">
        <div class="col-xl-12 p-0">
          <div class="login-card login-dark">
            <div>
              <div class="login-main" style="width: 60%"> 

                <form class="theme-form" id="formulario" enctype="multipart/form-data">

                  <center>
                    <img src="{{ URL('/images/logotipo.png')}}"  width="180" height="180" alt="looginpage">
                  </center>

                  @csrf

                  <div class="row">
                      <div class="col-12">
                          <div class="offer-banner">
                              <div class="offer-content">

                                  @if($coworkCode)
                                      <h4 style="color:white">
                                          Preço Especial: <strong>15,00 MT</strong> por cliente (cobrado apenas no acto da leitura) <br>
                                          Válido por <strong>1 ano</strong> (</i>preço normal 30,00Mt por cliente</i>).
                                      </h4>
                                  @else
                                      <h4 style="color:white">
                                          Preço do Sistema: <strong>30,00 MT</strong> por cliente  <br>
                                          Valor cobrado apenas no acto da leitura.
                                      </h4>

                                  @endif

                                  <h4 style="margin-top:10px; color:white">
                                      🎁 Bónus de Registo <strong>{{ number_format(3000,2,',','.') }} MT</strong> para usar nas leituras.
                                  </h4>

                              </div>

                              <img class="img-fluid"
                                  src="{{ URL('/assets/images/dashboard/1.png') }}"
                                  alt="promoção" />
                          </div>
                      </div>
                  </div>
                  <br>


                  <div class="row">

                    <h2>Dados da Empresa</h2>

                    <div class="col-6">
                      <label class="form-label">Nome Empresa <span style="color: red">*</span></label>
                      <input class="form-control" name="nome_empresa" type="text" placeholder="Nome Empresa">
                    </div>
                    <div class="col-6">
                      <label class="form-label">Logotipo da Empresa <span style="color: red">*</span></label>
                      <input class="form-control" name="logotipo" type="file" placeholder="logotipo Empresa">
                    </div>

                    <div class="col-6">
                      <label class="form-label">NUIT <span style="color: red">*</span></label>
                      <input class="form-control" name="nuit" type="text" placeholder="NUIT">
                    </div>
                    <div class="col-6">
                      <label class="form-label">Provincia <span style="color: red">*</span></label>
                      <select class="form-select"  name="provincia" id="provincia" onchange="getDistritos()" >
                              <option value="">Selecione a Provincia</option>
                              @foreach($provincias as $provincia)
                                <option value="{{$provincia->id}}">{{$provincia->nome}}</option>
                              @endforeach
                      </select>
                    </div>

                    <div class="col-6">
                      <label class="form-label">Distrito <span style="color: red">*</span></label>
                      <select class="form-select" name="distrito" id="distrito">
                          <option value="">Selecione o Distrito</option>
                      </select>
                    </div>

                    <div class="col-6">
                      <label class="form-label">Bairro <span style="color: red">*</span></label>
                      <div class="form-label">
                        <input class="form-control" type="text" name="bairro" placeholder="Bairro">
                      </div>
                    </div>
                    

                  </div>

                  @if($coworkCode)
                      <!-- VEIO DO LINK DO COWORK → CAMPO INVISÍVEL -->
                      <input type="hidden" name="cowork_codigo" value="{{ $coworkCode }}">
                  @else
                      <!-- REGISTO NORMAL → CAMPO VISÍVEL -->
                      <div class="col-12">
                          <label class="form-label">Código Promocional (opcional)</label>
                          <input class="form-control" name="cowork_codigo"  type="text" placeholder="Código Promocional">
                      </div>
                  @endif

                    <br>
                  <div class="row">

                    <h2>Dados de Utilizador</h2>
                    <div class="col-6">
                      <label class="form-label">Nome <span style="color: red">*</span></label>
                      <input class="form-control" name="nome_user" type="text" placeholder="Nome">
                    </div>
                    <div class="col-6">
                      <label class="form-label">Telefone <span style="color: red">*</span></label>
                      <div class="form-label">
                        <input class="form-control" type="text" name="telefone_user" placeholder="Telefone">
                      </div>
                    </div>

                    <div class="col-12">
                        <div class="error" id="error" style="color: red; text-align: center">
                        </div>
                    </div>

                  </div>

                  <div class="form-group mb-0">
                    <div class="checkbox p-0">
                      <input id="checkbox1" type="checkbox" name="termos" checked>
                      <label class="text-muted" for="checkbox1">Aceitar Termos</label>
                    </div><a class="link" href="{{route('login')}}">Voltar</a>
                    <div class="text-end mt-3">
                        <button id="botao_salvar" type="submit" class="btn btn-success w-100">
                            <span id="botao_texto">{{__('Criar Conta')}}</span>
                            <i id="icon_enviar" class="ri-arrow-right-line label-icon align-middle fs-16 ms-2"></i>
                        </button>
                    </div>
                  </div>
                  <br>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- latest jquery-->
      <script src="{{ URL('/assets/js/jquery.min.js')}}"></script>

      <!-- js Validate -->
      <script src="{{URL('/assets/js/jquery.validate.js')}}"></script>
      <!-- Bootstrap js-->
      <script src="{{ URL('/assets/js/bootstrap/bootstrap.bundle.min.js')}}"></script>
      <!-- feather icon js-->
      <script src="{{ URL('/assets/js/icons/feather-icon/feather.min.js')}}"></script>
      <script src="{{ URL('/assets/js/icons/feather-icon/feather-icon.js')}}"></script>
      <!-- scrollbar js-->
      <!-- Sidebar jquery-->
      <script src="{{ URL('/assets/js/config.js')}}"></script>
      <!-- Plugins JS start-->
      <!-- Plugins JS Ends-->
      <!-- Theme js-->
      <script src="{{ URL('/assets/js/script.js')}}"></script>

      <script>

        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $("#formulario").validate({
                // Adicionar regras para cada campo
                rules: {
                    nome_empresa: {
                        required: true,
                        minlength: 2
                    },
                    nuit: {
                        required: true,
                        minlength: 9,
                        maxlength: 9,
                    },
                    telefone_user: {
                        required: true,
                        minlength: 9,
                        maxlength: 9,
                    },
                    provincia: {
                        required: true,

                    },
                    distrito: {
                        required: true,
                    },
                    nome_user: {
                        required: true,
                        minlength: 2,
                    },
                    bairro: {
                        required: true,
                        minlength: 2,
                    },
                    termos: {
                      required: true
                    }
                },
                submitHandler: function(form) {

                    let formData = new FormData(form); // captura todo o formulário inclusive arquivo

                    $.ajax({
                        type: "POST",
                        url: "{{ route('empresa.store') }}",
                        data: formData, // Corrigido para usar `form` em vez de `this`
                        contentType: false,
                        processData: false,
                        cache: false,

                        beforeSend: function () {
                            // Desabilita o botão de envio e altera o ícone para mostrar que está autenticando
                            $('#botao_salvar').attr('disabled', true);
                            $('#icon_enviar').removeClass('ri-arrow-right-line').addClass('spinner-border ri-loader-2-line');
                            $('#botao_texto').text('Criando Conta...');
                        },

                        success: function(response) {
                            // Habilita o botão e retorna o ícone original
                            $('#botao_salvar').attr('disabled', false);
                            $('#icon_enviar').removeClass('spinner-border ri-loader-2-line').addClass('ri-arrow-right-line');
                            $('#botao_texto').text('Criar Conta');

                            // Redireciona ou exibe uma mensagem de erro com base na resposta
                            if(response.status == 1) {

                                Swal.fire({
                                    icon: 'success',
                                    title: 'Sucesso!',
                                    text: response.message,
                                    confirmButtonText: 'OK'
                                }).then((result) => {

                                    if (result.isConfirmed) {
                                        window.location.href = '/';
                                    }

                                });
                                
                                
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
                            $('#botao_texto').text('Criar Conta');


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

      <script>

        function getDistritos() {
            let provinciaID = document.getElementById('provincia').value;
            let distritoSelect = document.getElementById('distrito');
            
            // Limpa o select de distritos
            distritoSelect.innerHTML = '<option value="">Carregando...</option>';

            if (provinciaID) {
                fetch(`/api/distritos/${provinciaID}`)
                    .then(response => response.json())
                    .then(data => {
                        distritoSelect.innerHTML = '<option value="">Selecione o Distrito</option>';
                        data.forEach(distrito => {
                            distritoSelect.innerHTML += `<option value="${distrito.id}">${distrito.nome}</option>`;
                        });
                    })
                    .catch(error => {
                        distritoSelect.innerHTML = '<option value="">Erro ao carregar</option>';
                        console.error('Erro:', error);
                    });
            } else {
                distritoSelect.innerHTML = '<option value="">Selecione a Província primeiro</option>';
            }
        }

        </script>



    </div>
  </body>
</html>