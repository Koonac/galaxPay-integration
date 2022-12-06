$(function () {


    let verificaMaskCpfCnpj = function (val) {
        return val.replace(/\D/g, '').length === 11 ? '000.000.000-00' : '00.000.000/0000-00';
    }

    let verificaTelefone = function (val) {
        return val.replace(/\D/g, '').length > 9 ? '(00) 00000-0000' : '';
    }

    // MASCARAS PARA INPUT
    $('.decimalBrasileiro2Digitos').mask('000.000.000.000.000,00', { reverse: true });
    $('.cnpjMask').mask(verificaMaskCpfCnpj);
    $('.cpfMask').mask('000.000.000-00');
    $('.dataBrasileiraDDMMYYYY').mask('00/00/0000');
    $('.telefoneMask').mask(verificaTelefone);
    $('.telefoneMask2').mask('(00) 00000-0000');
    $('.cepMask').mask('00000-000');
    $('.inteiro').mask('000000000000000000000000');

    // CAPTURANDO PATHNAME DA ROTA ATUAL
    let nomeRota = $(location).attr('pathname').replace('/', '').split('/');
    let idRotaAtiva = nomeRota[0]

    if ($('#' + nomeRota[0]).length > 0) {
        idRotaAtiva = nomeRota[0];
    } else {
        idRotaAtiva = nomeRota[1];
    }

    // SETANDO CLASS ACTIVE NO NAV COM ID DO PATHNAME
    $('#' + idRotaAtiva).addClass('active')

    // FUNÇÃO PARA COPIAR INPUT
    function copyToClipboard(elemento) {
        // set focus to hidden element and select the content
        $(elemento).focus();
        // select all the text therein  
        $(elemento).select();

        var succeed;
        try {
            succeed = document.execCommand("copy");
        } catch (e) {
            succeed = false;
        }

        return succeed;
    }

});