@extends('layouts.app')

@push('css')

@endpush



@section('conteudo')


<div class="row">
    <h2>Desenhar / Editar Tubo Geral de Agua</h2>
    <div id="map" style="height: 600px; width: 100%;"></div>
    <div class="row">
      <div class="col-6">
        <button onclick="savePath()" class="btn btn-success w-100">Salvar Tubagem</button>
      </div>  
      <div class="col-6">
         <form  id="msform" enctype="multipart/form-data">
            @csrf

            <input class="form-control"  type="hidden" name="empresa" value="{{$empresa}}"  >

            <button id="botao_salvar" type="submit" class="btn btn-danger w-100">
                <span id="botao_texto">{{__('Apagar Tubo Geral')}} </span>
                <i id="icon_enviar" class="ri-arrow-right-line label-icon align-middle fs-16 ms-2"></i>
            </button>
         </form>
       </div>  
    </div>
</div>

@endsection


@push('js')

<script>
let map;
let polyline;

const tubagens = @json($tubagens);

function initMap() {
    map = new google.maps.Map(document.getElementById("map"), {
        zoom: 15,
        center: tubagens.length > 0
            ? { lat: parseFloat(tubagens[0].latitude), lng: parseFloat(tubagens[0].longitude) }
            : { lat: -25.965, lng: 32.583 }
    });

    // Carrega tubagem existente
    const path = tubagens.map(t => ({
        lat: parseFloat(t.latitude),
        lng: parseFloat(t.longitude)
    }));

    polyline = new google.maps.Polyline({
        path: path,
        geodesic: true,
        strokeColor: "#ff0000",
        strokeOpacity: 1,
        strokeWeight: 6,
        editable: true
    });

    polyline.setMap(map);

    // Clicar adiciona ponto
    google.maps.event.addListener(map, "click", function(event) {
        polyline.getPath().push(event.latLng);
    });

    // Botão direito remove ponto
    google.maps.event.addListener(polyline.getPath(), 'rightclick', function(event) {
        if (event.vertex != undefined) {
            polyline.getPath().removeAt(event.vertex);
        }
    });
}

// SALVAR – Apenas INSERE no backend
function savePath() {
    const raw = polyline.getPath();
    let pathArray = [];

    raw.forEach(p => {
        pathArray.push({
            lat: parseFloat(p.lat().toFixed(6)),
            lng: parseFloat(p.lng().toFixed(6))
        });
    });

    if (pathArray.length === 0) {
        Swal.fire({
            icon: "warning",
            title: "Aviso",
            text: "Nenhum ponto foi desenhado."
        });
        return;
    }

    fetch("{{ route('mapa.update') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },
        body: JSON.stringify({
            path: pathArray,
            diametro: 32
        })
    })
    .then(r => r.json())
    .then(data => {
        if (data.status === 'ok') {
            Swal.fire({
                icon: "success",
                title: "Sucesso!",
                text: "Tubagem gravada com sucesso!"
            }).then(() => location.reload());
        } else {
            Swal.fire({
                icon: "error",
                title: "Erro!",
                text: "Falha ao gravar tubagem."
            });
        }
    })
    .catch(err => {
        console.error(err);
        Swal.fire({
            icon: "error",
            title: "Erro!",
            text: "Erro de comunicação com o servidor."
        });
    });
}
</script>

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
            empresa: {
                required: true
            }
       },
        submitHandler: function(form) {
            $.ajax({
                type: "POST",
                url: "{{route('mapa.delete')}}",
                data: $(form).serialize(), // Corrigido para usar `form` em vez de `this`

                beforeSend: function () {
                    // Desabilita o botão de envio e altera o ícone para mostrar que está autenticando
                    $('#botao_salvar').attr('disabled', true);
                    $('#icon_enviar').removeClass('ri-arrow-right-line').addClass('spinner-border ri-loader-2-line');
                    $('#botao_texto').text('Apagar Tubo Geral...');
                },

                success: function(response) {
                    // Habilita o botão e retorna o ícone original
                    $('#botao_salvar').attr('disabled', false);
                    $('#icon_enviar').removeClass('spinner-border ri-loader-2-line').addClass('ri-arrow-right-line');
                    $('#botao_texto').text('Apagar Tubo Geral');

                    // Redireciona ou exibe uma mensagem de erro com base na resposta
                    if(response.status == 1) {

                          Swal.fire({
                                    icon: 'success',
                                    title: 'Sucesso!',
                                    text: response.message,
                          });

                          window.location.reload();
                          
                    }

                    if(response.status == 0) {

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
                    $('#botao_texto').text('Apagar Tubo Geral');

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

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyARv7Trm55IaqfCzkh-eL2baNxlWJc0qgk&callback=initMap" async defer></script>



@endpush