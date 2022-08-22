$(function () {
    var verificaMaskCpfCnpj = function (val) {
        return val.lenght > 11 ? '00.000.000/0000-00' : '000.000.000-00';
    }
    // MASCARAS PARA INPUT
    $('.cnpjMask').mask(verificaMaskCpfCnpj);
    $('.telefoneMask').mask('(00) 00000-0000');
    $('.cepMask').mask('00000-000',);

    // FUNÇÃO APRA ENVIAR PARA PAGINA DE IMPRESSAO
    $('#btnImprimirCard').on('click', function () {

        // INICIALIZANDO VARIAVEL COM ARRAY
        let codigosImpressao = {
            "codigoClienteGalaxpay": clientePrincipal,
            "dependentesCliente": dependentesCliente
        };
        // CODIFICANDO CODIGOS DE IMPRESSAO
        codigosImpressao = btoa(JSON.stringify(codigosImpressao)).toString('base64');
        var url = "/clientes/gerarCartao?codigosImpressao=" + codigosImpressao;

        // ABRINDO NOVA ABA COM URL
        window.open(url)

    })
});
var contCheckbox = 0;
var clientePrincipal = '';
var dependentesCliente = [];

function campoExtraImprimir(campo) {
    // INICIALIZANDO VARIAVEL DO CANVAS
    const offcanvasImpressao = new bootstrap.Offcanvas('#offcanvasBottom')

    // ABRINDO OFFCANVAS
    offcanvasImpressao.show();

    // VERIFICANDO CLIENTE PRINCIPAL
    if (campo.name == 'checkboxCliente') {
        if (campo.checked) {
            // ADICIONANDO ID AO ARRAY
            clientePrincipal = campo.value;
            contCheckbox++
        } else {
            // REMOVENDO ID DO ARRAY
            clientePrincipal = '';
            contCheckbox--
        }
    } else {
        var idCampoReferencia = campo.value;
        var inputsCamposExtras = $('#divDependente' + idCampoReferencia).find(':input#' + idCampoReferencia);
        var idsCamposExtras = [];

        for (let i = 0; i < inputsCamposExtras.length; i++) {
            idsCamposExtras.push(inputsCamposExtras[i].value);
        }

        if (campo.checked) {
            // ADICIONANDO ID AO ARRAY
            dependentesCliente.push({
                "nomeDependente": idsCamposExtras[0],
                "cpfDependente": idsCamposExtras[1],
                "nascimentoDependente": idsCamposExtras[2],
            })
            contCheckbox++
        } else {
            var nomeDependente;
            nomeDependente = dependentesCliente.map(d => d['nomeDependente']);

            // REMOVENDO ID DO ARRAY
            indexRemove = nomeDependente.indexOf(idCampoReferencia)
            if (indexRemove !== -1) {
                dependentesCliente.splice(indexRemove, 1);
                contCheckbox--
            }
        }
    }

    // ADICIONANDO MENSAGEM AO OFFCANVAS DE CAMPOS SELECIONADOS
    $('#textOffcanvas').html('<p>Selecionados ' + contCheckbox + ' para impressão</p>')

    // CASO NAO TENHA CHECKBOX SELECIONADO FECHAMOS O OFFCANVAS
    if (contCheckbox <= 0) {
        offcanvasImpressao.hide();
    }

}