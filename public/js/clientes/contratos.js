$(function () {

    // INICIALIZANDO COM INFORAMAÇÕES DE JUROS E MULTAS ESCONDIDOS
    $('#informacoesBoletoPix').hide();

    // ANALISANDO FORMA DE PAGAMENTO
    $('#formaPagamento').on('change', function () {
        if (($('#formaPagamento').val() == 'boleto') && ($('#formaPagamento').val() != 'pix')) {
            $('#informacoesBoletoPix').show();
        } else {
            $('#informacoesBoletoPix').hide();
        }
    });

    // ANALISANDO APLICAÇÃO DE DESCONTO AO PAGAR COM ANTECEDENCIA
    $('#aplicarDesconto').on('change', function () {
        if (!($('#aplicarDesconto').is(":checked"))) {
            $('#valorDesconto').prop('disabled', true);
            $('#qtdeDiasValidadeDesconto').prop('disabled', true);
        } else {
            $('#valorDesconto').prop('disabled', false);
            $('#qtdeDiasValidadeDesconto').prop('disabled', false);
        }
    });

    // ANALISANDO APLICAÇÃO DE DESCONTO AO PAGAR COM ANTECEDENCIA
    $('#tipoDescontoPorcentagem').on('change', function () {
        if ($('#tipoDescontoPorcentagem').is(":checked")) {
            $('#valorDescontoSpan').html('%');
        } else {
            $('#valorDescontoSpan').html('R$');
        }
    });
    $('#tipoDescontoFixo').on('change', function () {
        if ($('#tipoDescontoFixo').is(":checked")) {
            $('#valorDescontoSpan').html('R$');
        } else {
            $('#valorDescontoSpan').html('%');
        }
    });


});

