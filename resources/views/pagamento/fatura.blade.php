@extends('layouts.app')

@push('css')
<style>
/* === CONFIGURAÇÕES DE IMPRESSÃO === */
@media print {
  @page {
    size: 58mm auto; /* use 48mm se quiser mais compacto */
    margin: 0;
  }

  body {
    margin: 0;
    padding: 0;
    font-family: 'Courier New', monospace;
    font-size: 11px;
    line-height: 1.3;
    background: white;
    color: #000;
  }
  * {
    -webkit-print-color-adjust: exact;
     print-color-adjust: exact;
  }

  .ticket {
    width: 48mm;
    padding: 2mm;
    background: #fff;
  }

  .center { text-align: center; }
  .right { text-align: right; }
  .line {
    border-top: 1px dashed #000;
    margin: 4px 0;
  }

  table {
    width: 100%;
    border-collapse: collapse;
  }
  td {
    vertical-align: top;
  }
}

/* === VISUALIZAÇÃO NA TELA === */
body {
  font-family: 'Courier New', monospace;
  background: #f4f4f4;
  display: flex;
  justify-content: center;
  padding: 20px;
}
.ticket {
  width: 48mm;
  background: #fff;
  padding: 2mm;
  border: 1px solid #ccc;
}
button {
  margin-top: 10px;
  padding: 6px 12px;
  background: #007bff;
  color: white;
  border: none;
  border-radius: 5px;
  cursor: pointer;
}
button:hover {
  background: #0056b3;
}
</style>
@endpush

@section('conteudo')

<div class="ticket">
  <div class="center">
    <strong>ÁGUA MUNICIPAL</strong><br>
    Av. Principal, nº 123'<br>
    Tel:  '84 000 0000'
  </div>

  <div class="line"></div>

  <strong>Cliente:</strong> Jacinto Alexandre Machava<br>
  <strong>Contador Nº:</strong> 6337373<br>
  <strong>Mês:</strong> 22-05-2025<br>

  <div class="line"></div>

  <table>
    <tr><td>Água</td><td class="right">124422 MT</td></tr>
    <tr><td>Dívida</td><td class="right">366633 MT</td></tr>
    <tr><td>Multa</td><td class="right">83883 MT</td></tr>
    <tr><td class="line" colspan="2"></td></tr>
    <tr>
      <td><strong>Total a Pagar</strong></td>
      <td class="right"><strong>83883 MT</strong></td>
    </tr>
  </table>

  <div class="line"></div>
  <div class="center">Obrigado pela preferência!<br>Emitido em </div>
</div>

<div class="center">
  <button onclick="window.print()">🧾 Imprimir Fatura</button>
</div>

@endsection

@push('js')
<script>
function imprimir() {
  window.print();
}
</script>
@endpush
