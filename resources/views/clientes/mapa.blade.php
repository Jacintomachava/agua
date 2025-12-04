@extends('layouts.app')

@push('css')

@endpush



@section('conteudo')


<div class="row">
   <div class="col-4">Geolocalizao de Clientes <img  src="{{ URL('/images/water-tap.png')}}" width="40" height="40" alt="looginpage"></div>
   <div class="col-4">Tubo Geral <b style="color: red; font-size: 20pt">________________</b></div>
   <div id="map" style="height: 600px; width: 100%;"></div>
</div>

@endsection


@push('js')

<script>
const clientes  = @json($clientes);
const tubagens = @json($tubagens);

let map;
let polyline;

function initMap() {

    map = new google.maps.Map(document.getElementById("map"), {
        zoom: 14,
        center: tubagens.length > 0
            ? { lat: parseFloat(tubagens[0].latitude), lng: parseFloat(tubagens[0].longitude) }
            : { lat: -25.965, lng: 32.583 }
    });

    // ---------------------------
    // 1️⃣ CARREGAR A TUBAGEM
    // ---------------------------
    const path = tubagens.map(t => ({
        lat: parseFloat(t.latitude),
        lng: parseFloat(t.longitude)
    }));

    polyline = new google.maps.Polyline({
        path: path,
        geodesic: true,
        strokeColor: "#ff0000",
        strokeOpacity: 1,
        strokeWeight: 6
    });

    polyline.setMap(map);

    // ---------------------------
    // 2️⃣ COLOCAR MARCADORES DOS CLIENTES
    // ---------------------------
    clientes.forEach(cliente => {

        const iconOptions = {
            url: "/images/water-tap.png",
            scaledSize: new google.maps.Size(50, 50)
        };

        const marker = new google.maps.Marker({
            position: {
                lat: parseFloat(cliente.latitude),
                lng: parseFloat(cliente.longitude)
            },
            map,
            icon: iconOptions,
            title: cliente.nome
        });

        const info = new google.maps.InfoWindow({
            content: `
                <strong>${cliente.nome}</strong><br>
                Telefone: ${cliente.telefone ?? ''}<br>
                Tipo: ${cliente.ligacao_activa == 1 ? "Empresa" : "Residência"}
            `
        });

        marker.addListener("click", () => info.open(map, marker));
    });
}
</script>



<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyARv7Trm55IaqfCzkh-eL2baNxlWJc0qgk&callback=initMap" async defer></script>


@endpush