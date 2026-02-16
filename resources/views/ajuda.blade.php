@extends('layouts.app')

@push('css')
@endpush

@section('conteudo')

<div class="container">
    <div class="card">
        <div class="card-header">
            <h3>📺 Ajuda - Sistema de Fornecimento de Água</h3>
            <h6>Assista aos vídeos abaixo para aprender a usar o sistema.</h6>
        </div>

        <div class="card-body text-center">

            <div class="row">
                <iframe 
                    width="100%" 
                    height="600"
                    src="https://www.youtube.com/embed/videoseries?list=PL70aKYEuu4H4S3TvqkUx9KXOo6MWZTXFq"
                    title="Playlist Ajuda Sistema Água"
                    frameborder="0"
                    allowfullscreen>
                </iframe>
            </div>

        </div>
    </div>
</div>

@endsection

@push('js')
@endpush
