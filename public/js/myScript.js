$(function(){
    // MASCARAS PARA INPUT
    $('#cnpjPerfil').mask('00.000.000/0000-00');
    
    // CAPTURANDO PATHNAME DA ROTA ATUAL
    let nomeRota = $(location).attr('pathname').replace('/', '');

    // SETANDO CLASS ACTIVE NO NAV COM ID DO PATHNAME
    $('#'+nomeRota).addClass('active')
})