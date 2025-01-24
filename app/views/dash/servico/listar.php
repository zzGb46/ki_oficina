<table class="table table-hover">
    <thead>
        <tr>
            <th scope="col">Foto</th>
            <th scope="col">Nome</th>
            <th scope="col">Descrição</th>
            <th scope="col">Preço</th>
            <th scope="col">Tempo</th>
            <th scope="col">Especialidade</th>
            <th scope="col">Editar</th>
            <th scope="col">Desativar</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($listaServico as $linha): ?>

            <tr>
                <td>
                    <?php
                    // Caminho da foto
                    $caminhoBase = "http://localhost/kioficina/public/uploads/";
                    $caminhoFoto = $_SERVER['DOCUMENT_ROOT'] . "/kioficina/public/uploads/" . $linha['foto_galeria'];
                    $urlFoto = $linha['foto_galeria'] != "" && file_exists($caminhoFoto)
                        ? $caminhoBase . htmlspecialchars($linha['foto_galeria'], ENT_QUOTES, 'UTF-8')
                        : $caminhoBase . "sem-foto-servico.png";
                    ?>
                    <img src="<?php echo $urlFoto; ?>" alt="Foto do Serviço" style="width: 100px; height: auto;">
                </td>
                <td><?php echo $linha['nome_servico'] ?></td>
                <td><?php echo $linha['descricao_servico'] ?></td>
                <td><?php echo number_format($linha['preco_base_servico'], 2, ',', '.') ?></td>
                <td><?php echo $linha['tempo_estimado_servico'] ?></td>
                <td><?php echo $linha['nome_especialidade'] ?></td>
                <td>
                    <a href="http://localhost/kioficina/public/servicos/listar/" title="Editar">
                        <img src="http://localhost/kioficina/public/uploads/pencil.png" alt="Editar" style="width: 20px; height: auto;">
                    </a>
                </td>
                <td>
                    <a href="http://localhost/kioficina/public/servicos/desativar/" title="Desativar" onclick="return confirm('Tem certeza que deseja desativar este serviço?');">
                        <img src="http://localhost/kioficina/public/uploads/trash.png" alt="Desativar" style="width: 20px; height: auto;">
                    </a>
                </td>
            </tr>

        <?php endforeach; ?>

    </tbody>
</table>