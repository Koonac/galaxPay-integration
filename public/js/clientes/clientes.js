$(function(){
    // SALVANDO LISTA DE CLIENTES ORIGINAL
    $('#listClientes').data('listClientesOriginal', $('#listClientes').html());

    // BOTA PARA PESQUISAR CLIENTES
    $('#btnPesquisarCliente').on('click', function(){
        url = "/clientes/pesquisa/" + $('#inputPesquisaCliente').val();
        if(!$('#inputPesquisaCliente').val() == ''){
            $.ajax({
                url: url,
                method: 'GET',
                success:function(data)
                {
                    $('#listClientes').html(data);
                }
            })
        }else{
            // REDEFININDO LISTA DE CLIENTE
            $('#listClientes').html($('#listClientes').data('listClientesOriginal'));
        }
    });
    
    // EVENTO DE KEY UP PARA PESQUSIAR CLIENTES AUTOMATICAMENTE
    $('#inputPesquisaCliente').on('keyup', function(){
        if(!$('#inputPesquisaCliente').val() == ''){
            $.ajax({
                url: "/clientes/pesquisa/" + $('#inputPesquisaCliente').val(),
                method: 'GET',
                success:function(data)
                {
                    $('#listClientes').html(data);
                }
            })
        }else{
            // REDEFININDO LISTA DE CLIENTE
            $('#listClientes').html($('#listClientes').data('listClientesOriginal'));
        }
    });
})