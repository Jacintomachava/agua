

<div class="header">
   <center> 
    <img alt="{{Auth::user()->empresa->nome}}" 
        src="{{ public_path('/logotipo/'.Auth::user()->empresa->logotipo) }}" 
        width="100" height="90"><br>
    <h3>{{ strtoupper(Auth::user()->empresa->nome) }}</h3>
    <h3>CONTRACTO DE FORNECIMENTO DE ÁGUA</h3>
  </center> 
</div>


{!! $conteudo !!}