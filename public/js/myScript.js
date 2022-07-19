$(function(){

    var verificaMaskCpfCnpj = function(val) {
            return val.lenght > 11 ? '00.000.000/0000-00' : '000.000.000-00';
        }
    // MASCARAS PARA INPUT
    $('.cnpjMask').mask(verificaMaskCpfCnpj);
    $('.telefoneMask').mask('(00) 00000-0000');
    $('.cepMask').mask('00000-000',);
    
    // CAPTURANDO PATHNAME DA ROTA ATUAL
    let nomeRota = $(location).attr('pathname').replace('/', '');

    // SETANDO CLASS ACTIVE NO NAV COM ID DO PATHNAME
    $('#'+nomeRota).addClass('active')


});