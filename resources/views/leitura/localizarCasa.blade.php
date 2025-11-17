@extends('layouts.app')

@push('css')
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<style>
.leaflet-routing-alt  {
    background-color: white !important; /* fundo branco */
    border-radius: 8px;
    padding: 10px;
    box-shadow: 0 3px 10px rgba(0,0,0,0.2);
    font-size: 14px;
    color: #000; /* texto preto */
}
</style>
@endpush

@section('conteudo')

<div class="col-xl-12 order-md-iii">
    <div class="card title-line overflow-hidden member-wrapper">
        <div class="card-header card-no-border">
            <div class="header-top">
                <h2>
                    <img class="img-40 img-fluid m-r-20" src="{{ URL('/assets/images/job-search/2.jpg')}}" alt="">
                    Localizar Casa Cliente
                </h2>
                <div class="card-header-right-icon">
                    <a href="{{route('cliente.meuClientes')}}">
                        <button class="btn btn-pill btn-info btn-sm">Clientes</button>
                    </a>
                    <a href="{{route('cliente.create')}}">
                        <button class="btn btn-pill btn-primary btn-sm">Geolocalizacao(Mapa)</button>
                    </a>
                </div>
            </div>
        </div>
    </div> <!-- Fechamento da div "card title-line overflow-hidden member-wrapper" -->
</div> <!-- Fechamento da div "col-xl-12 order-md-iii" -->

<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header">
               <h2>Localização da Casa de {{ $cliente->nome }}</h2>
            </div>
            <div class="card-body">
                <div id="map" style="height: 550px; border-radius: 10px;"></div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('js')

<!-- Leaflet e plugin de rotas -->
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<!-- Plugin para traçar rotas -->
<script src="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.min.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const casaLat = {{ $cliente->latitude ?? 'null' }};
    const casaLng = {{ $cliente->longitude ?? 'null' }};

    if (!casaLat || !casaLng) {
        alert("⚠️ Este cliente ainda não tem coordenadas guardadas.");
        return;
    }

    // Inicializar o mapa
    const map = L.map('map').setView([casaLat, casaLng], 15);

    // Adicionar mapa base (OpenStreetMap)
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    // Adicionar marcador da casa do cliente
    const casaMarker = L.marker([casaLat, casaLng]).addTo(map)
        .bindPopup("<b>Casa de {{ $cliente->nome }}</b><br>Localização registada.")
        .openPopup();

    // Função para pegar tua localização
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            pos => {
                const myLat = pos.coords.latitude;
                const myLng = pos.coords.longitude;

                // Marcador da tua posição
                const myMarker = L.marker([myLat, myLng], {
                    icon: L.icon({
                        iconUrl: "https://cdn-icons-png.flaticon.com/512/64/64113.png",
                        iconSize: [35, 35],
                        iconAnchor: [17, 34],
                        popupAnchor: [0, -30]
                    })
                }).addTo(map)
                .bindPopup("📍 A tua posição atual")
                .openPopup();

                // Traçar rota entre ti e a casa
                L.Routing.control({
                    waypoints: [
                        L.latLng(myLat, myLng),
                        L.latLng(casaLat, casaLng)
                    ],
                    routeWhileDragging: false,
                    createMarker: function() { return null; } // evita duplicar marcadores
                }).addTo(map);
            },
            err => {
                console.error(err);
                alert("Não foi possível obter a tua localização.");
            },
            { enableHighAccuracy: true, timeout: 10000 }
        );
    } else {
        alert("O teu navegador não suporta geolocalização.");
    }
});
</script>

@endpush
