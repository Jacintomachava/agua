@extends('layouts.app')

@push('css')
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
@endpush

@section('conteudo')

<div class="col-xl-12 order-md-iii">
    <div class="card title-line overflow-hidden member-wrapper">
        <div class="card-header card-no-border">
            <div class="header-top">
                <h2>
                    <img class="img-40 img-fluid m-r-20" src="{{ URL('/public/assets/images/job-search/2.jpg')}}" alt="">
                    Roles
                </h2>
                <div class="card-header-right-icon">
                    <a href="{{route('userFuro.create')}}">
                        <button class="btn btn-pill btn-primary btn-sm">Cadastrar Utilizador</button>
                    </a>
                </div>
            </div>
        </div>
    </div> <!-- Fechamento da div "card title-line overflow-hidden member-wrapper" -->
</div> <!-- Fechamento da div "col-xl-12 order-md-iii" -->

<div class="col-sm-12" style="margin-top: -2%">
    <div class="card">
        <div class="card-header pb-0 card-no-border">
        </div>
        <div class="card-body">

            <div class="table-responsive custom-scrollbar">
                <table class="display" id="basic-1">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Nome</th>
                            <th>Enderenco</th>
                            <th>Provincia</th>
                            <th>Distrito</th>
                            <th>Estado</th>
                            <th>Funcoes</th>
                            <th>Acção</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach($users as $user)
                            <tr>
                                <td></td>
                                <td>{{$user->nome}}</td>
                                <td>{{$user->telefone }}</td>
                                <td>{{$user->distrito->provincia->nome}}</td>
                                <td>{{$user->distrito->nome}}</td>
                                <td>
                                   @if($user->estado==1)
                                        <a class="btn btn-success btn-xs activate-btn" >
                                            Activo
                                        </a>
                                    @else
                                        <a class="btn btn-info btn-xs activate-btn" >
                                            Desativo
                                        </a>
                                    @endif
                                </td>
                                <td>
                                    @foreach($user->roles as $role)
                                        {{ $role->name }},
                                    @endforeach
                                </td>

                                <td>
                                    <a class="btn btn-warning btn-xs activate-btn" href="" >
                                        Editar
                                    </a>
                                     <a class="btn btn-danger btn-xs activate-btn"  >
                                        Repor Senha
                                    </a>
                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

@endsection

@push('js')

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const activateButtons = document.querySelectorAll('.activate-btn');

        activateButtons.forEach(button => {
            button.addEventListener('click', function () {
                const id = this.getAttribute('data-id');

                Swal.fire({
                    title: 'Tem certeza?',
                    text: "Já não estara mais na escola? ",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sim, Retira!',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Enviar requisição AJAX
                        fetch(`/retirar/funcionario/${id}`, {
                            method: 'get',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Content-Type': 'application/json'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.status === 'success') {
                                Swal.fire(
                                    'Retirado!',
                                    data.message,
                                    'success'
                                );
                                // Recarregar a página para atualizar o estado
                                location.reload();
                            } else {
                                Swal.fire(
                                    'Erro!',
                                    data.message,
                                    'error'
                                );
                            }
                        })
                        .catch(() => {
                            Swal.fire(
                                'Erro!',
                                'Ocorreu um erro ao processar sua solicitação.',
                                'error'
                            );
                        });
                    }
                });
            });
        });
    });
</script>

@endpush
