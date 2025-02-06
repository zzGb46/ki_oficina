<div class="container-fluid">

    <div class="row">
        <div class="col-3">
            <!-- IMAGEM -->

            <?php
$fotoGaleria = $servico['foto_galeria'];
$fotoPath = "http://localhost/kioficina/public/uploads/" . $fotoGaleria;
$fotoDefault = "http://localhost/kioficina/public/uploads/servico/sem-foto-servico.png";

$imagePath = (file_exists($_SERVER['DOCUMENT_ROOT'] . "/kioficina/public/uploads/" . $fotoGaleria) && !empty($fotoGaleria)) 
    ? $fotoPath 
    : $fotoDefault;
?>
       
            <!-- Input file oculto -->
            <input type="file" name="foto_galeria" id="foto_galeria" style="display: none;" accept="image/*">
        </div>
        <div class="col-9">
            <!-- FORMULÁRIO DE CADASTRO -->
            <!-- FORMULÁRIO DE CADASTRO -->
            <form method="POST" action="http://localhost/kioficina/public/servicos/adicionar"
                enctype="multipart/form-data">

                <div class="form-group mt-3">
                    <label for="nome_servico">Nome do Serviço:</label>
                    <input type="text" class="form-control" name="nome_servico" id="nome_servico"
                        value="<?php echo htmlspecialchars($servico['nome_servico']); ?>" required>
                </div>

                <div class="form-group mt-3">
                    <label for="descricao_servico" class="form-label">Descrição do Serviço:</label>
                    <textarea class="form-control" name="descricao_servico" id="descricao_servico" <?php echo htmlspecialchars($servico['descricao_servico']); ?> rows="3" required></textarea>
                </div>

                <div class="row mt-3">

                    <!-- PREÇO -->
                    <div class="col">
                        <label for="preco_base_servico" class="form-label">Preço Base:</label>
                        <input type="text" class="form-control" placeholder="R$" name="preco_base_servico"
                            id="preco_base_servico" <?php echo htmlspecialchars($servico['preco_base_servico']); ?>required>
                    </div>

                    <!-- TEMPO -->
                    <div class="col">
                        <label for="tempo_estimado_servico" class="form-label">Tempo Estimado:</label>
                        <input type="time" class="form-control" name="tempo_estimado_servico"
                            id="tempo_estimado_servico" <?php echo htmlspecialchars($servico['tempo_estimado_servico']); ?>>
                    </div>

                    <!-- STATUS -->
                    <div class="col">
                        <label for="status_servico" class="form-label">Status do Serviço:</label>
                        <select name="status_servico" id="status_servico" <?php echo htmlspecialchars($servico['status_servico']); ?> class="form-select">
                            <option value="Ativo" selected>Ativo</option>
                            <option value="Inativo">Inativo</option>
                        </select>
                    </div>

                    <!-- ESPECIALIDADES -->
                    <div class="col">
                        <label for="id_especialidade" class="form-label">Especialidade Existente:</label>
                        <select name="id_especialidade" id="id_especialidade" class="form-select">
                            <option selected>-- Selecione --</option>
                            <?php foreach ($especialidades as $linha): ?>
                                <div class="col">
                                    <label for="id_especialidade" class="form-label">Especialidade Existente:</label>
                                    <select name="id_especialidade" id="id_especialidade" class="form-select">
                                        <option selected>Selecione</option>

                                        <?php foreach ($especialidades as $linha): ?>
                                            <option value="<?php echo $linha['id_especialidade']; ?>">
                                                <?php echo $linha['nome_especialidade']; ?></option>
                                        <?php endforeach ?>
                                    </select>
                                </div>
                                <option value="<?php echo $linha['id_especialidade']; ?>">
                                    <?php echo $linha['nome_especialidade']; ?>
                                </option>
                            <?php endforeach ?>
                        </select>
                    </div>

                </div>

                <div class="form-group mt-3">
                    <label for="nova_especialidade">Nova Especialidade:</label>
                    <input type="text" class="form-control" name="nova_especialidade" id="nova_especialidade" required>
                </div>

                <div class="form-group mt-3">
                    <button type="submit" class="btn btn-success">Salvar</button>
                    <a href="http://localhost/kioficina/public/servicos/listar" type="button"
                        class="btn btn-secondary">Cancelar</a>
                </div>

            </form>

        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const visualizarImg = document.getElementById('preview-img');
        const arquivo = document.getElementById('foto_galeria');

        visualizarImg.addEventListener('click', function () {
            arquivo.click();
        })

        arquivo.addEventListener('change', function () {
            if (arquivo.files && arquivo.files[0]) {
                let render = new FileReader();
                render.onload = function (e) {
                    visualizarImg.src = e.target.result;
                }

                render.readAsDataURL(arquivo.files[0]);
            }
        });
    })
</script>