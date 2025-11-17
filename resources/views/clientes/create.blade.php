@extends('layouts.app')

@push('css')

<style type="text/css">

.area{
    margin: 10px auto;
    box-shadow: 0 10px 100px #ccc;
    padding: 20px;
    box-sizing: border-box;
    max-width: 500px;
}

.area video{
    width: 100%;
    height: auto;
    background-color: whitesmoke;
}

.area textarea{
    width: 100%;
    margin-top: 10px;
    height: 80px;
    box-sizing: border-box;
}

.area button{
    -webkit-appearance: none;
    width: 100%;
    box-sizing: border-box;
    padding: 10px;
    text-align: center;
    background-color: #068c84;
    color: white;
    text-transform: uppercase;
    border: 1px solid white;
    box-shadow: 0 1px 5px #666;
}

.area button:focus{
    outline: none;
    background-color: #0989b0;
}

.area img{
    max-width: 100%;
    height: 400px;
}

.area .caminho-imagem{
    padding: 5px 10px;
    border-radius: 3px;
    background-color: #068c84;
    text-align: center;
}

.area .caminho-imagem a{
    color: white;
    text-decoration: none;
}

.area .caminho-imagem a:hover{
    color: yellow;
}
</style>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />

@endpush

@section('conteudo')


    <h2>
        <div class="col-12">
          <div class="error" id="error" style="color: red; text-align: center">
          </div>
        </div>
    </h2>

    <div class="container-fluid">
        <div class="row">
          <div class="col-xl-12">
            <div class="card height-equal title-line">
              <div class="card-header">
                <h2>Dados de Cliente</h2>
              </div>
              <div class="card-body basic-wizard important-validation">
                <div class="stepper-horizontal custom-scrollbar" id="stepper1">
                  <div class="stepper-one stepper step editing active">
                    <div class="step-circle"><span>1</span></div>
                    <div class="step-title">Dados do Cliente</div>
                    <div class="step-bar-left"></div>
                    <div class="step-bar-right"></div>
                  </div>
                  <div class="stepper-two step">
                    <div class="step-circle"><span>2</span></div>
                    <div class="step-title">Dados de Ligacao</div>
                    <div class="step-bar-left"></div>
                    <div class="step-bar-right"></div>
                  </div>
                  <div class="stepper-three step">
                    <div class="step-circle"><span>3</span></div>
                    <div class="step-title">Pagamento</div>
                    <div class="step-bar-left"></div>
                    <div class="step-bar-right"></div>
                  </div>
                </div>
                <div id="msform1">
                  <form class="row g-3 needs-validation custom-input" id="msform" enctype="multipart/form-data">

                    @csrf
                    
                    <form1 class="stepper-one row g-3 needs-validation custom-input" >

                      <div class="col-4">
                        <label class="form-label" for="confirmpasswordwizard">Nome Cliente<span class="txt-danger">*</span></label>
                        <input class="form-control"  type="text" name="cliente" placeholder="Nome Cliente" >
                      </div>

                      <div class="col-4">
                        <label class="form-label" for="passwordwizard">Tipo Documento<span class="txt-danger">*</span></label>
                          <select class="form-select"  name="tipo_documento">
                                <option value="">Seleciona o tipo de Documento</option>
                                <option value="B.I">B.I</option>
                                <option value="Cédula">Cédula</option>
                                <option value="Boletim de Nascimento">Boletim de Nascimento</option>
                                <option value="Passaporte">Passaporte</option>
                          </select>
                      </div>

                      <div class="col-4">
                        <label class="form-label" for="confirmpasswordwizard">Número Documento</label>
                        <input class="form-control"  type="text" name="numero_documento" placeholder="Número Documento" >
                      </div>

                      <div class="col-4">
                        <label class="form-label" for="confirmpasswordwizard">Telefone<span class="txt-danger">*</span></label>
                        <input class="form-control"  type="text" name="telefone" placeholder="Telefone" >
                      </div>

                      <div class="col-4">
                        <label class="form-label">Provincia</label>
                        <select class="form-select"  name="provincia" id="provincia" onchange="getDistritos()" >
                                <option value="">Selecione a Provincia</option>
                                @foreach($provincias as $provincia)
                                  <option value="{{$provincia->id}}">{{$provincia->nome}}</option>
                                @endforeach
                        </select>
                      </div>

                      <div class="col-4">
                        <label class="form-label">Distrito</label>
                        <select class="form-select" name="distrito" id="distrito">
                            <option value="">Selecione o Distrito</option>
                        </select>
                      </div>


                    </form1>

                    <form1 class="stepper-two row g-3 needs-validation custom-input">

                      <div class="col-4">
                        <label class="form-label" for="confirmpasswordwizard">Numero de Contador<span class="txt-danger">*</span></label>
                        <input class="form-control"  type="text" name="numero_contador" placeholder="Numero Contador" >
                      </div>

                      <div class="col-4">
                        <label class="form-label" for="confirmpasswordwizard">Bairro<span class="txt-danger">*</span></label>
                        <input class="form-control"  type="text" name="bairro" placeholder="Bairro" >
                      </div>

                      <div class="col-4">
                        <label class="form-label" for="confirmpasswordwizard">Quarteirao<span class="txt-danger">*</span></label>
                        <input class="form-control"  type="text" name="quarteirao" placeholder="Quarteirao" >
                      </div>

                      <div class="col-4">
                        <label class="form-label" for="confirmpasswordwizard">Casa<span class="txt-danger">*</span></label>
                        <input class="form-control"  type="text" name="casa" placeholder="Casa" >
                      </div>

                      <div class="col-4">
                         <label class="form-label" for="passwordwizard">Furo<span class="txt-danger">*</span></label>
                           <select class="form-select"  name="furo">
                              @foreach($furos as $furo)
                                <option value="{{$furo->id}}">{{$furo->nome}}</option>
                              @endforeach
                           </select>
                      </div>

                      <div class="col-4">
                         <label class="form-label" for="passwordwizard">Mes a Cobrar<span class="txt-danger">*</span></label>
                           <select class="form-select"  name="mes">
                              @foreach($meses as $mese)
                                <option value="{{$mese->id}}">{{$mese->nome}}</option>
                              @endforeach
                           </select>
                      </div>

                    </form1>

                    <form1 class="stepper-three row g-3 needs-validation custom-input" >
                      
                      <div class="col-6">
                         <label class="form-label" for="passwordwizard">Contrato<span class="txt-danger">*</span></label>
                           <select class="form-select"  name="contrato">
                              @foreach($contratos as $contrato)
                                <option value="{{$contrato->id}}">{{$contrato->nome}} - {{$contrato->valor_contrato}} e {{$contrato->valor}}MT/{{$contrato->metro_cubico}}m&sup3;</option>
                              @endforeach
                           </select>
                      </div>

                      <div class="col-6">
                         <label class="form-label" for="passwordwizard">Forma Pagamento<span class="txt-danger">*</span></label>
                           <select class="form-select"  name="forma_pagamento" id="forma" onchange="getBancos()" >
                              @foreach($formasPagamentos as $formasPagamento)
                                <option value="{{$formasPagamento->id}}">{{$formasPagamento->nome}}</option>
                              @endforeach
                           </select>
                      </div>

                      <div class="col-6">
                        <label class="form-label">Banco Carteira</label>
                        <select class="form-select" name="banco_carteira" id="bancos">
                            <option value="">Selecione o Banco/Carteira</option>
                        </select>
                      </div>

                      <div class="col-6">
                        <label class="form-label" for="confirmpasswordwizard">Valor<span class="txt-danger">*</span></label>
                        <input class="form-control"  type="text" name="valor_pago" placeholder="Valor" >
                      </div>


                      <button id="botao_salvar" type="submit" class="btn btn-success w-100">
                          <span id="botao_texto">{{__('Registar Cliente')}}</span>
                          <i id="icon_enviar" class="ri-arrow-right-line label-icon align-middle fs-16 ms-2"></i>
                      </button>
                    </form1>
                    
                   </form>  
                  </div>
                  <br>
                  <div class="wizard-footer d-flex gap-2 justify-content-end">
                    <button class="btn btn-light-primary" id="backbtn" onclick="backStep()"> Voltar</button>
                    <button class="btn btn-primary" id="nextbtn" onclick="nextStep()">Próximo</button>
                  </div>
              </div>
            </div>
          </div>

          <!--  Colocar escondindo -->
          <div class="col-xl-6" hidden>
            <div class="card height-equal title-line">
              <div class="card-header">
                <h2>Student Validation Form</h2>
                <p class="f-m-light mt-1">
                    Please make sure fill all the filed before click on next button.</p>
              </div>
              <div class="card-body custom-input">
                <form class="form-wizard" >
                  <div class="tab">
                    <div class="row g-3">
                      <div class="col-sm-6">
                        <label for="name">Name</label>
                        <input class="form-control" name="name" type="text" placeholder="Enter your name" >
                      </div>
                      <div class="col-sm-6">
                        <label class="form-label" for="student-email-wizard">Email<span class="txt-danger">*</span></label>
                        <input class="form-control" name="email" type="email"  placeholder="johan@gmail.com">
                      </div>
                      <div class="col-12">
                        <label class="form-label" for="password-wizard">Password<span class="txt-danger">*</span></label>
                        <input class="form-control" id="password-wizard" type="password" placeholder="Enter password" required="">
                      </div>
                      <div class="col-12">
                        <label class="form-label" for="confirmpassowrd">Confirm Password<span class="txt-danger">*</span></label>
                        <input class="form-control" id="confirmpassowrd" type="password" placeholder="Enter confirm password" required="">
                      </div>
                    </div>
                  </div>
                  <div class="tab">
                    <div class="row g-3 avatar-upload">
                      <div class="col-12">
                        <div>
                          <div class="avatar-edit">
                            <input id="imageUpload" type="file" accept=".png, .jpg, .jpeg">
                            <label for="imageUpload"></label>
                          </div>
                          <div class="avatar-preview">
                            <div id="image"></div>
                          </div>
                        </div>
                        <h6>Add Profile</h6>
                      </div>
                      <div class="col-12">
                        <label class="form-label" for="exampleFormControlInput1">Portfolio URL</label>
                        <input class="form-control" id="exampleFormControlInput1" type="url" placeholder="https://yuri">
                      </div>
                      <div class="col-12"> 
                        <label class="form-label" for="projectDescription">Project Description</label>
                        <textarea class="form-control" id="projectDescription" rows="2"></textarea>
                      </div>
                    </div>
                  </div>
                  <div class="tab">
                    <h5 class="mb-2">Social Links </h5>
                    <div class="row g-3">
                      <div class="col-sm-6">
                        <label class="form-label" for="twitterControlInput">Twitter</label>
                        <input class="form-control" id="twitterControlInput" type="url" placeholder="https://twitter.com">
                      </div>
                      <div class="col-sm-6">
                        <label class="form-label" for="githubControlInput">Github</label>
                        <input class="form-control" id="githubControlInput" type="url" placeholder="https:/github.com">
                      </div>
                      <div class="col-12"> 
                        <div class="input-group">
                          <input class="form-control" id="inputGroupFile04" type="file" aria-describedby="inputGroupFileAddon04" aria-label="Upload">
                          <button class="btn btn-outline-secondary" id="inputGroupFileAddon04" type="button">Submit</button>
                        </div>
                      </div>
                      <div class="col-12">
                        <select class="form-select" aria-label="Default select example">
                          <option selected="">Positions</option>
                          <option value="1">Web Designer</option>
                          <option value="2">Software Engineer</option>
                          <option value="3">UI/UX Designer </option>
                          <option value="3">Web Developer</option>
                        </select>
                      </div>
                      <div class="col-12"> 
                        <label class="form-label" for="quationsTextarea">Why do you want to take this position?</label>
                        <textarea class="form-control" id="quationsTextarea" rows="2"></textarea>
                      </div>
                    </div>
                  </div>
                  <div>
                    <div class="text-end pt-3">
                      <button class="btn btn-secondary" id="prevBtn" type="button" onclick="nextPrev(-1)">Previous</button>
                      <button class="btn btn-primary" id="nextBtn" type="button" onclick="nextPrev(1)">Next</button>
                    </div>
                  </div>
                  <!-- Circles which indicates the steps of the form:-->
                  <div class="text-center"><span class="step"></span><span class="step"></span><span class="step"></span><span class="step"></span></div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>

@endsection


@push('js')


<script>

$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $("#msform").validate({
        // Adicionar regras para cada campo
        rules: {
            plano: {
                required: true
            },
            turma: {
                required: true
            },
            classe: {
                required: true
            },
            endereco: {
                required: true,
                minlength: 2,
            },
            numero_documento: {
                required: true
            },
            tipo_documento: {
                required: true
            },
            provincia: {
                required: true
            },
            data_nascimento: {
                required: true,
            },
            genero: {
                required: true
            },
            nome_casa: {
                minlength: 3,
            },
            nomealuno: {
                required: true,
                minlength: 3,
            },
            imagem: {
                required: true
            }
       
     },
        submitHandler: function(form) {
            $.ajax({
                type: "POST",
                url: "{{route('cliente.store')}}",
                data: $(form).serialize(), // Corrigido para usar `form` em vez de `this`

                beforeSend: function () {
                    // Desabilita o botão de envio e altera o ícone para mostrar que está autenticando
                    $('#botao_salvar').attr('disabled', true);
                    $('#icon_enviar').removeClass('ri-arrow-right-line').addClass('spinner-border ri-loader-2-line');
                    $('#botao_texto').text('Matriculando Aluno...');
                },

                success: function(response) {
                    // Habilita o botão e retorna o ícone original
                    $('#botao_salvar').attr('disabled', false);
                    $('#icon_enviar').removeClass('spinner-border ri-loader-2-line').addClass('ri-arrow-right-line');
                    $('#botao_texto').text('Matricular Aluno');

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
                    $('#botao_texto').text('Matricular Aluno');

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

<script>

    function getBancos() {
        let provinciaID = document.getElementById('forma').value;
        let distritoSelect = document.getElementById('bancos');
        
        // Limpa o select de distritos
        distritoSelect.innerHTML = '<option value="">Carregando...</option>';

        if (provinciaID) {
            fetch(`/api/bancos/carteiras/${provinciaID}`)
                .then(response => response.json())
                .then(data => {
                    distritoSelect.innerHTML = '<option value="">Selecione o Banco Carteira</option>';
                    data.forEach(distrito => {
                        distritoSelect.innerHTML += `<option value="${distrito.id}">${distrito.nome}</option>`;
                    });
                })
                .catch(error => {
                    distritoSelect.innerHTML = '<option value="">Erro ao carregar</option>';
                    console.error('Erro:', error);
                });
        } else {
            distritoSelect.innerHTML = '<option value="">Selecione a Forma de pagamento primeiro</option>';
        }
    }

</script>


@endpush

