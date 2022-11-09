$(function () {
    $('#contasTable').dataTable(
        {
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.12.1/i18n/pt-BR.json'
            },
            "pageLength": 25
        }
    );
});