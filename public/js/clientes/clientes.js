$(function(){
    // SALVANDO LISTA DE CLIENTES ORIGINAL
    $('#listClientes').data('listClientesOriginal', $('#listClientes').html());

    // DEFININDO MODAL DE LOADING
    const modalLoading = new bootstrap.Modal('#modalLoading', {
        keyboard: false,
        backdrop: "static",
    });

    var pesquisaCliente = $('#inputPesquisaCliente').val().replace(/\.|\-/g, '');

    // BOTAO PARA PESQUISAR CLIENTES
    $('#btnPesquisarCliente').on('click', function(){
        $('#listClientes').html('<div class="container-fluid mt-3"><div class="row bg-light shadow border rounded p-4"><div class="col-md-12 d-flex justify-content-center"><div class="spinner-border text-info" role="status"></div><strong class="ms-2 fw-bold">Pesquisando cliente...</strong></div></div></div>');

        // INICIALIZANDO VARIAVEIS
        url = "/galaxPay/pesquisaCliente/" + $('#searchOption').val() + "/" + pesquisaCliente;
        
        // ANALISANDO CAMPO DE PESQUISA
        if(!$('#inputPesquisaCliente').val() == ''){
            // INICIALIZANDO MODAL DE LOADING
            $.ajax({
                url: url,
                method: 'GET',
                success:function(data)
                {
                    $('#listClientes').html(data)
                },
                error:function(){
                    $('#listClientes').html('<div class="alert alert-danger shadow mt-2">Erro: [ Ocorreu um erro interno. ]</div>')
                }
            })
        }else{
            // REDEFININDO LISTA DE CLIENTE
            $('#listClientes').html($('#listClientes').data('listClientesOriginal'));
        }
    });
    
    // AO PRESSIOANR ENTER
    $('#inputPesquisaCliente').on('keypress', function(e){
        if(e.keyCode == 13){
            $('#listClientes').html('<div class="container-fluid mt-3"><div class="row bg-light shadow border rounded p-4"><div class="col-md-12 d-flex justify-content-center"><div class="spinner-border text-info" role="status"></div><strong class="ms-2 fw-bold">Pesquisando cliente...</strong></div></div></div>');
    
            // INICIALIZANDO VARIAVEIS
            url = "/galaxPay/pesquisaCliente/" + $('#searchOption').val() + "/" + $('#inputPesquisaCliente').val();
            
            // ANALISANDO CAMPO DE PESQUISA
            if(!$('#inputPesquisaCliente').val() == ''){
                // INICIALIZANDO MODAL DE LOADING
                $.ajax({
                    url: url,
                    method: 'GET',
                    success:function(data)
                    {
                        $('#listClientes').html(data)
                    },
                    error:function(){
                        $('#listClientes').html('<div class="alert alert-danger shadow mt-2">Erro: [ Ocorreu um erro interno. ]</div>')
                    }
                })
            }else{
                // REDEFININDO LISTA DE CLIENTE
                $('#listClientes').html($('#listClientes').data('listClientesOriginal'));
            }
        }
    });
    
})