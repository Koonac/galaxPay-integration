$(function () {

    // $('.verificaMaskCpfCnpj').mask('000.000.000-00', options);

    // CAPTURANDO URL DO HOST
    var urlLocal = window.location.host;
    $('#inputEmpresaParceira').val(urlLocal + '/empresasParceiras/login')

    // ANALISANDO CLICK NO BOTÃO
    $('#btnCopyEmpresaParceira').on('click', function () {
        copyToClipboard('#inputEmpresaParceira')
    });

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
