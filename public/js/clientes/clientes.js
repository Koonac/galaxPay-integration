$(function () {
    $('#btnAdicionaNovoDependente').on('click', function () {

        // ADICIONANDO NOVO DIV DE DEPENDENTES
        if ($('.linhaDependente').length < 7) {
            $('#divDependentes').append('<div class="row py-1 linhaDependente" id="linhaDependente"><div class="col-md-4"><label class="fw-bold" for="nomeDependente">Nome dependente</label><input class="form-control" name="nomeDependente[]" id="nomeDependente" value=""></div><div class="col-md-4"><label class="fw-bold" for="cpfDependente">CPF dependente</label><input class="form-control cpfMask" name="cpfDependente[]" id="cpfDependente" value=""></div><div class="col-md-3"><label class="fw-bold" for="nascimentoDependente">Data de nascimento</label><input class="form-control dataBrasileiraDDMMYYYY" name="nascimentoDependente[]" id="nascimentoDependente" value=""></div><div class="col-md-1 d-flex  align-items-end"></div></div>')
        }

        // DEFININDO NOVAS MASK PARA A NOVA DIV
        $('.cpfMask').mask('000.000.000-00');
        $('.dataBrasileiraDDMMYYYY').mask('00/00/0000');

    });


});

