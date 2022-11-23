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

    // FUNÇÃO APRA ENVIAR PARA PAGINA DE IMPRESSAO
    $('#btnImprimirCard').on('click', function () {

        // INICIALIZANDO VARIAVEL COM ARRAY
        let codigosImpressao = {
            "codigoClientesGalaxpay": codigoClientesGalaxpay,
            "codigoDependentesClienteGalaxpay": codigoDependentesClienteGalaxpay
        };
        // CODIFICANDO CODIGOS DE IMPRESSAO
        codigosImpressao = btoa(JSON.stringify(codigosImpressao)).toString('base64');
        var url = "/clientes/gerarCartaoJs?codigosImpressao=" + codigosImpressao;

        // ABRINDO NOVA ABA COM URL
        window.open(url)

    })
});
var contCheckbox = 0;
var codigoClientesGalaxpay = [];
var codigoDependentesClienteGalaxpay = [];

function campoExtraImprimir(campo) {
    // INICIALIZANDO VARIAVEL DO CANVAS
    const offcanvasImpressao = new bootstrap.Offcanvas('#offcanvasBottom')

    // ABRINDO OFFCANVAS
    offcanvasImpressao.show();

    // VERIFICANDO CLIENTE PRINCIPAL
    if (campo.name == 'checkboxCliente') {
        if (campo.checked) {
            // ADICIONANDO ID AO ARRAY
            codigoClientesGalaxpay.push(campo.value);
            contCheckbox++
        } else {
            // REMOVENDO ID DO ARRAY
            indexRemove = codigoClientesGalaxpay.indexOf(campo.value)
            if (indexRemove !== -1) {
                codigoClientesGalaxpay.splice(indexRemove, 1);
                contCheckbox--
            }
        }
    } else if (campo.name == 'checkboxClienteDependente') {
        if (campo.checked) {
            // ADICIONANDO ID AO ARRAY
            codigoDependentesClienteGalaxpay.push(campo.value);
            contCheckbox++
        } else {
            // REMOVENDO ID DO ARRAY
            indexRemove = codigoDependentesClienteGalaxpay.indexOf(campo.value)
            if (indexRemove !== -1) {
                codigoDependentesClienteGalaxpay.splice(indexRemove, 1);
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