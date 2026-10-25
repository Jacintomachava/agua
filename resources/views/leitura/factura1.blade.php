<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="utf-8">
    <title>Factura Nº {{ $leitura->numero_factura }}</title>

    <style>
        @page {
            size: A4 portrait;
            margin: 8mm 10mm;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 9px;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 100%;
        }

        /*
        |--------------------------------------------------------------------------
        | FACTURA
        |--------------------------------------------------------------------------
        */

        .factura {
            width: 100%;
            height: 135mm;
            page-break-inside: avoid;
        }

        /*
        |--------------------------------------------------------------------------
        | TABELAS
        |--------------------------------------------------------------------------
        */

        .header-table,
        .data-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .data-table td,
        .data-table th {
            border: 1px solid #000;
            padding: 4px;
            font-size: 9px;
            vertical-align: middle;
        }

        .bold {
            font-weight: bold;
        }

        .title {
            font-size: 15px;
            text-align: center;
            margin-bottom: 5px;
        }

        .logo {
            width: 90px;
            height: 65px;
        }

        /*
        |--------------------------------------------------------------------------
        | ALINHAMENTOS
        |--------------------------------------------------------------------------
        */

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .text-left {
            text-align: left;
        }

        .note {
            font-size: 8px;
            line-height: 1.4;
        }

        .total {
            font-weight: bold;
            font-size: 11px;
        }

        /*
        |--------------------------------------------------------------------------
        | ASSINATURA E CARIMBO
        |--------------------------------------------------------------------------
        */

        .assinatura {
            text-align: center;
            margin-top: 8px;
        }

        .assinatura img {
            width: 140px;
            height: 65px;
        }

        /*
        |--------------------------------------------------------------------------
        | LINHA DE CORTE
        |--------------------------------------------------------------------------
        */

        .cut-center {
            border: none;
            border-top: 1px dashed #000;
            margin: 0;
            padding: 0;
            height: 0;
        }

    </style>

</head>

<body>

<div class="container">

    @php

        /*
        |--------------------------------------------------------------------------
        | CÁLCULO DOS VALORES
        |--------------------------------------------------------------------------
        */

        $valorAPagar = (float) ($leitura->valor_a_pagar ?? 0);

        $divida = (float) (
            $leitura->furoClienteContrato->divida ?? 0
        );

        $saldo = (float) (
            $leitura->furoClienteContrato->saldo ?? 0
        );

        $multa = (float) ($leitura->multa ?? 0);

        /*
        |--------------------------------------------------------------------------
        | CÁLCULO DA MULTA
        |--------------------------------------------------------------------------
        |
        | Mantém a mesma fórmula da Blade de todas as facturas.
        |
        */

        $valorMulta = $valorAPagar * ($multa / 100);

        /*
        |--------------------------------------------------------------------------
        | TOTAL A PAGAR
        |--------------------------------------------------------------------------
        */

        $totalPagar = max(
            0,
            $valorAPagar + $divida - $saldo + $valorMulta
        );

    @endphp


    <div class="factura">

        <table class="data-table">

            <!-- ================================================= -->
            <!-- CABEÇALHO -->
            <!-- ================================================= -->

            <tr>

                <td colspan="2" class="text-center">

                    @if(Auth::user()->empresa->logotipo == null)

                        <img
                            src="{{ public_path('images/logotipo.png') }}"
                            width="90"
                            height="70"
                        >

                    @else

                        <img
                            src="{{ public_path('/logotipo/' . Auth::user()->empresa->logotipo) }}"
                            width="90"
                            height="70"
                        >

                    @endif

                </td>

                <td colspan="3" class="text-center">

                    <div class="bold">
                        {{ strtoupper($empresa->nome) }}
                    </div>

                    <div>
                        {{ $empresa->endereco }}
                    </div>

                    <div>
                        NUIT: {{ $empresa->nuit }}
                    </div>

                </td>

                <td colspan="2" class="text-right">

                    <div class="bold">
                        FACTURA Nº: {{ $leitura->numero_factura }}
                    </div>

                    <div>
                        Mês {{ $leitura->mes->nome }}
                    </div>

                </td>

            </tr>


            <!-- ================================================= -->
            <!-- NOME DO CLIENTE -->
            <!-- ================================================= -->

            <tr>

                <td>
                    Nome
                </td>

                <th colspan="6" class="text-center">

                    {{
                        strtoupper(
                            $leitura->furoClienteContrato->cliente->nome
                        )
                    }}

                </th>

            </tr>


            <!-- ================================================= -->
            <!-- MORADA -->
            <!-- ================================================= -->

            <tr>

                <td>
                    Morada
                </td>

                <td>
                    Quarteirão
                </td>

                <td>
                    {{ $leitura->furoClienteContrato->cliente->quarteirao }}
                </td>

                <td>
                    Casa
                </td>

                <td>
                    {{ $leitura->furoClienteContrato->cliente->casa }}
                </td>

                <td>
                    Cliente
                </td>

                <td>
                    {{ $leitura->furoClienteContrato->codigo }}
                </td>

            </tr>


            <!-- ================================================= -->
            <!-- MÊS E PRAZO DE PAGAMENTO -->
            <!-- ================================================= -->

            <tr>

                <td>
                    Mês
                </td>

                <td colspan="2" class="text-center">
                    {{ $leitura->mes->nome }}
                </td>

                <td colspan="2" class="text-center">
                    Prazo Pag.
                </td>

                <td colspan="2" class="text-center">

                    {{
                        \Carbon\Carbon::parse(
                            $leitura->prazo_pagamento
                        )->format('d-m-Y')
                    }}

                </td>

            </tr>


            <!-- ================================================= -->
            <!-- LEITURA ANTERIOR E CONSUMO -->
            <!-- ================================================= -->

            <tr>

                <td>
                    L. Anterior
                </td>

                <td colspan="2" class="text-center">

                    {{
                        $leitura->valor_leitura - $leitura->consumo
                    }} m&sup3;

                </td>

                <td colspan="2" class="text-center">
                    Consumo
                </td>

                <td colspan="2" class="text-center">
                    {{ $leitura->consumo }} m&sup3;
                </td>

            </tr>


            <!-- ================================================= -->
            <!-- LEITURA ACTUAL E DÍVIDA -->
            <!-- ================================================= -->

            <tr>

                <td>
                    L. Actual
                </td>

                <td colspan="2" class="text-center">
                    {{ $leitura->valor_leitura }} m&sup3;
                </td>

                <td colspan="2" class="text-center">
                    Dívida
                </td>

                <td colspan="2" class="text-center">

                    {{
                        number_format(
                            $divida,
                            2,
                            ',',
                            '.'
                        )
                    }} MT

                </td>

            </tr>


            <!-- ================================================= -->
            <!-- VALOR POR METRO CÚBICO -->
            <!-- ================================================= -->

            <tr>

                <td>
                    Valor/m&sup3;
                </td>

                <td colspan="2" class="text-center">

                    {{
                        number_format(
                            $leitura->furoClienteContrato->contrato->valor,
                            2,
                            ',',
                            '.'
                        )
                    }} MT

                </td>

                <td colspan="2" class="text-center">
                    Mês Corrente
                </td>

                <td colspan="2" class="text-center">

                    {{
                        number_format(
                            $valorAPagar,
                            2,
                            ',',
                            '.'
                        )
                    }} MT

                </td>

            </tr>


            <!-- ================================================= -->
            <!-- SALDO E MULTA -->
            <!-- ================================================= -->

            <tr>

                <td>
                    Saldo
                </td>

                <td colspan="2" class="text-center">

                    {{
                        number_format(
                            $saldo,
                            2,
                            ',',
                            '.'
                        )
                    }} MT

                </td>

                <td colspan="2" class="text-center">

                    Multa ({{ number_format($multa, 0) * 20 }} dias)

                </td>

                <td colspan="2" class="text-center">

                    {{
                        number_format(
                            $valorMulta,
                            2,
                            ',',
                            '.'
                        )
                    }} MT

                </td>

            </tr>


            <!-- ================================================= -->
            <!-- DATA DA LEITURA E TOTAL -->
            <!-- ================================================= -->

            <tr>

                <td>
                    Data Leitura
                </td>

                <td colspan="2" class="text-center">

                    {{
                        \Carbon\Carbon::parse(
                            $leitura->data_leitura
                        )->format('d-m-Y')
                    }}

                </td>

                <th colspan="2" class="text-center">
                    Total a Pagar
                </th>

                <th colspan="2" class="text-center total">

                    {{
                        number_format(
                            $totalPagar,
                            2,
                            ',',
                            '.'
                        )
                    }} MT

                </th>

            </tr>


            <!-- ================================================= -->
            <!-- NOTA -->
            <!-- ================================================= -->

            <tr>

                <td colspan="7" class="note">

                    Nota: O cliente é sujeito a uma MULTA
                    de 20 MT por dia após o último dia do pagamento
                    (dia 10 de cada mês) e o fornecedor poderá
                    interromper o fornecimento de água sem aviso prévio.

                </td>

            </tr>


            <!-- ================================================= -->
            <!-- MODALIDADES DE PAGAMENTO -->
            <!-- ================================================= -->

            <tr>

                <th colspan="7" class="text-center">

                    Modalidades de pagamento:

                </th>

            </tr>


            <!-- ================================================= -->
            <!-- ASSINATURA E CARIMBO -->
            <!-- ================================================= -->

            <tr>

                <td colspan="7" class="text-center">

                    <div class="assinatura">

                        <img
                            src="{{ public_path('logotipo/assinatura.png') }}"
                            alt="Assinatura e Carimbo"
                        >

                    </div>

                </td>

            </tr>

        </table>

    </div>

    <!-- LINHA DE CORTE -->
    <hr class="cut-center">

</div>

</body>
</html>