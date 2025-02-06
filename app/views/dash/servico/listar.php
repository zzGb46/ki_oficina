<?php
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
 
    if (isset($_SESSION['mensagem']) && isset($_SESSION['tipo-msg'])) {
        $mensagem = $_SESSION['mensagem'];
        $tipo = $_SESSION['tipo-msg'];
 
        // Exibir a mensagem
        if ($tipo == 'sucesso') {
            echo '<div class="alert alert-success" role="alert">' . $mensagem . '</div>';
        } elseif ($tipo == 'erro') {
            echo '<div class="alert alert-danger" role="alert">' . $mensagem . '</div>';
        }
 
        // Limpar variáveis de sessão
        unset($_SESSION['mensagem']);
        unset($_SESSION['tipo-msg']);
    }
 
    ?>








<a href="http://localhost/kioficina/public/servicos/adicionar" class="btn btn-primary">Adicionar Serviço</a>

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
                    <a class="btn btn-primary"
                        href="http://localhost/kioficina/public/servicos/editar/<?php echo $linha['id_servico']; ?>"
                        title="Editar">
                        <img src="http://localhost/kioficina/public/uploads/pencil.png" alt="Editar"
                            style="width: 20px; height: auto;">
                    </a>
                </td>
                <td>
                    <a href="#" title="Desativar" class="btn btn-danger" title="Desativar"
                        onclick="abrirModelDesativar(<?php echo $linha['id_servico']; ?>)">
                        <img src="http://localhost/kioficina/public/uploads/trash.png" alt="Desativar"
                            style="width: 20px; height: auto;">
                    </a>
                </td>
            </tr>

        <?php endforeach; ?>

    </tbody>
</table>

<!-- MODAL DESATIVAR SERVIÇO -->
<div class="modal" tabindex="-1" id="modalDesativar">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Desativar Serviços</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Tem certeza de que deseja desativar esse serviço?</p>
                <input type="hidden" id="idServicoDesativar" value="">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="btnConfirmar">Desativar</button>
            </div>
        </div>
    </div>
</div>

