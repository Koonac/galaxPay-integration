$(function () {

    // INICIALIZANDO VARIAVEIS
    var valorTotal = parseFloat($('#valorCaixaAbertura').val().replace(/,/g, ""));

    // INICIALIZANDO DATA TABLE
    $('#caixaFinanceiroTable').dataTable(
        {
            paging: false,
            searching: false,
            order: [[0, 'asc']],
            // ordering: false,
            scrollY: 400,
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.12.1/i18n/pt-BR.json'
            },
            "pageLength": 25
        }
    );

    // PERCORRENDO LAÇO DE VALORES DE RECEBIMENTO
    $('.valorRecebimento').each(function (i, valorRecebimento) {
        var valorItem = parseFloat($(valorRecebimento).val().replace(/,/g, ""));

        if (!isNaN(valorItem)) {
            valorTotal += valorItem;
        }
    })

    // PERCORRENDO LAÇO DE VALORES DE DESPESA
    $('.valorDespesa').each(function (i, valorDespesa) {
        var valorItem = parseFloat($(valorDespesa).val().replace(/,/g, ""));
        if (!isNaN(valorItem)) {
            valorTotal -= valorItem;
        }
    })

    // ATRIBUINDO NOVO VALOR DE CAIXA AO CAMPO
    $('#valorTotalCaixa').val(valorTotal);

    // ATRIBUINDO MASCARA AO CAMPO, FICA AQUI EM BAIXO POIS OS CAMPOS NA O FORAM CARREGADOS AINDA
    // $('.decimalBrasileiro2Digitos').mask("#,##0.00");
});
