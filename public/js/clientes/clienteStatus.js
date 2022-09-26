$(function () {
    // SALVANDO LISTA DE CLIENTES ORIGINAL
    $('#clienteStatus').data('clienteStatusOriginal', $('#clienteStatus').html());

    // BOTAO PARA PESQUISAR CLIENTES
    $('#btnPesquisarCliente').on('click', function () {

        var pesquisaCliente = $('#inputPesquisaCliente').val().replace(/\.|\-/g, '');

        $('#clienteStatus').html('<div class="container-fluid mt-3"><div class="row bg-light shadow border rounded p-4"><div class="col-md-12 d-flex justify-content-center"><div class="spinner-border text-info" role="status"></div><strong class="ms-2 fw-bold">Pesquisando cliente...</strong></div></div></div>');

        // INICIALIZANDO VARIAVEIS
        url = "/galaxPay/pesquisaCliente/" + $('#searchOption').val() + "/" + pesquisaCliente;

        // ANALISANDO CAMPO DE PESQUISA
        if (!$('#inputPesquisaCliente').val() == '') {
            $.ajax({
                url: url,
                method: 'GET',
                success: function (data) {
                    $('#clienteStatus').html(data)
                },
                error: function () {
                    $('#clienteStatus').html('<div class="alert alert-danger shadow mt-2">Erro: [ Ocorreu um erro interno. ]</div>')
                }
            })
        } else {
            // REDEFININDO LISTA DE CLIENTE
            $('#clienteStatus').html($('#clienteStatus').data('clienteStatusOriginal'));
        }
    });

    // AO PRESSIOANR ENTER
    $('#inputPesquisaCliente').on('keypress', function (e) {
        var pesquisaCliente = $('#inputPesquisaCliente').val().replace(/\.|\-/g, '');
        if (e.keyCode == 13) {
            $('#clienteStatus').html('<div class="container-fluid mt-3"><div class="row bg-light shadow border rounded p-4"><div class="col-md-12 d-flex justify-content-center"><div class="spinner-border text-info" role="status"></div><strong class="ms-2 fw-bold">Pesquisando cliente...</strong></div></div></div>');

            // INICIALIZANDO VARIAVEIS
            url = "/galaxPay/pesquisaCliente/" + $('#searchOption').val() + "/" + pesquisaCliente;

            // ANALISANDO CAMPO DE PESQUISA
            if (!$('#inputPesquisaCliente').val() == '') {
                // INICIALIZANDO MODAL DE LOADING
                $.ajax({
                    url: url,
                    method: 'GET',
                    success: function (data) {
                        $('#clienteStatus').html(data)
                    },
                    error: function (data) {
                        console.log(data)
                        $('#clienteStatus').html('<div class="alert alert-danger shadow mt-2">Erro: [ Ocorreu um erro interno. ]</div>')
                    }
                })
            } else {
                // REDEFININDO LISTA DE CLIENTE
                $('#clienteStatus').html($('#clienteStatus').data('clienteStatusOriginal'));
            }
        }
    });

})