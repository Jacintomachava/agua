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
                    <img class="img-40 img-fluid m-r-20" src="{{ URL('/assets/images/job-search/2.jpg')}}" alt="">
                    Lista de Despesas
                </h2>
                <div class="card-header-right-icon">
                    <a href="{{route('despesas.create')}}">
                        <button class="btn btn-pill btn-primary btn-sm">Cadastrar Despesa</button>
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
                            <th scope="col">Descrição</th>
                            <th scope="col">Categoria</th>
                            <th scope="col">Valor Despesa</th>
                            <th scope="col">Valor Pago</th>
                            <th scope="col">Saldo</th>
                            <th scope="col">Data</th>
                            <th scope="col">Hora</th>
                            <th scope="col">Acção</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach($despesas as $despesa)
                            <tr>
                                <td data-container="body" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="{{$despesa->descricao}}">
                                    {{ Str::limit($despesa->descricao, 10) }}
                                </td>
                                <td>{{$despesa->categoria->nome}}</td>
                                <td>{{$despesa->valor_despesa}}</td>
                                <td>{{$despesa->valor_pago}}</td>
                                <td>{{$despesa->valor_despesa-$despesa->valor_pago}}</td>
                                <td>{{ \Carbon\Carbon::parse($despesa->updated_at)->format('d-M-Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($despesa->updated_at)->format('H:s') }}</td>
                                <td>
                                    <a class="btn btn-warning btn-xs" href="{{route('despesas.edit',['id'=>$despesa->id])}}" >
                                        Editar
                                    </a>
                                     <a class="btn btn-danger btn-xs btn-delete-despesa" data-id="{{$despesa->id}}" >
                                        Apagar
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

document.querySelectorAll('.btn-delete-despesa').forEach(btn => {

    btn.addEventListener('click', function () {

        let despesaId = this.dataset.id;

        Swal.fire({
            title: 'Tem certeza?',
            text: 'Deseja realmente apagar esta Despesa?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sim, apagar',
            cancelButtonText: 'Cancelar'
        }).then(result => {

            if (!result.isConfirmed) return;

            fetch(`/despesa/apagar/${despesaId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(response => {

                Swal.fire({
                    icon: response.status ? 'success' : 'error',
                    title: response.status ? 'Sucesso' : 'Erro',
                    text: response.message
                }).then(() => location.reload());

            });

        });

    });

});

</script>


@endpush
