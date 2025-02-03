<a href="http://localhost/kioficina/public/clientes/adicionar" class="btn btn-primary">Cadastrar Cliente</a>

<table class="table table-hover">
    <thead>
        <tr>
            <th scope="col">Foto</th>
            <th scope="col">Nome</th>
            <th scope="col">telefone</th>
            <th scope="col">Email</th>
            <th scope="col">Editar</th>
            <th scope="col">Desativar</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($listaFuncionario as $linha): ?>

            <tr>
                <td>
                    <?php
                    // Caminho da foto
                    $caminhoBase = "http://localhost/kioficina/public/uploads/";
                    $caminhoFoto = $_SERVER['DOCUMENT_ROOT'] . "/kioficina/public/uploads/" . $linha['foto_cliente'];
                    $urlFoto = $linha['foto_cliente'] != "" && file_exists($caminhoFoto)
                        ? $caminhoBase . htmlspecialchars($linha['foto_cliente'], ENT_QUOTES, 'UTF-8')
                        : $caminhoBase . "sem-foto-servico.png";
                    ?>
                    <img src="<?php echo $urlFoto; ?>" alt="Foto do Serviço" style="width: 100px; height: auto;">
                </td>
                <td><?php echo $linha['nome_funcionario'] ?></td>
                <td><?php echo $linha['telefone_funcionario'] ?></td>
                <td><?php echo $linha['email_funcionario'] ?></td>
                <td>
                    <a href="http://localhost/kioficina/public/funcionario/listar/" title="Editar">
                        <img src="http://localhost/kioficina/public/uploads/pencil.png" alt="Editar" style="width: 20px; height: auto;">
                    </a>
                </td>
                <td>
                    <a href="http://localhost/kioficina/public/funcionario/desativar/" title="Desativar" onclick="return confirm('Tem certeza que deseja desativar este serviço?');">
                        <img src="http://localhost/kioficina/public/uploads/trash.png" alt="Desativar" style="width: 20px; height: auto;">
                    </a>
                </td>
            </tr>

        <?php endforeach; ?>

    </tbody>
</table>