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
    $('.telefoneMask').mask(verificaTelefone);
    $('.telefoneMask2').mask('(00) 00000-0000');
    $('.cepMask').mask('00000-000');

});