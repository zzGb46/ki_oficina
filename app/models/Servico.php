<?php



class Servico extends Model
{

    //Método para Pegar somente 3 servicos de forma aleatória
    public function getServicoAleatorio($limite = 3)
    {
        $sql = "SELECT s.*,g.foto_galeria,g.alt_foto_galeria FROM tbl_gabrielm_servico s INNER JOIN tbl_gabrielm_galeria  g ON s.id_servico = g.id_servico WHERE s.status_servico = 'Ativo' GROUP BY s.id_servico ORDER BY RAND() LIMIT :limite";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limite', (int) $limite, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    //Método Listar todos os Serviços ativos por ordem alfabetica
    public function getTodosServicos()
    {

        $sql = "SELECT s.*, g.foto_galeria, e.nome_especialidade FROM tbl_gabrielm_galeria as g
                inner join tbl_gabrielm_servico as s
                on g.id_servico = s.id_servico
                inner join tbl_gabrielm_especialidade as e
                on s.id_especialidade = e.id_especialidade
                WHERE status_galeria = 'ativo'";

        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Método para carregar o serviço pelo link
    public function getServicoPorLink($link)
    {

        $sql = "SELECT tbl_gabrielm_servico.*, tbl_gabrielm_galeria .* FROM tbl_gabrielm_servico 
                INNER JOIN tbl_gabrielm_galeria  ON tbl_gabrielm_servico .id_servico = tbl_gabrielm_galeria .id_galeria
                WHERE status_servico = 'Ativo' AND link_servico = :link";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':link', $link);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    //Método para Pegar 4 Especialidade de servicos de forma aleatória
    public function getEspecialidadeAleatorio($limite = 4)
    {

        $sql = "SELECT * FROM tbl_gabrielm_especialidade ORDER BY RAND() LIMIT :limite";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limite', (int) $limite, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    // Médoto para o DASHBOARD - Listar todos os serviços com galeria e especialidade
    public function getListarServicos()
    {

        $sql = "SELECT 
                    srv.*,
                    gal.foto_galeria,
                    esp.nome_especialidade
                FROM 
                    tbl_gabrielm_servico AS srv
                INNER JOIN 
                    tbl_gabrielm_galeria  AS gal ON srv.id_servico = gal.id_servico
                INNER JOIN 
                    tbl_gabrielm_especialidade AS esp ON srv.id_especialidade = esp.id_especialidade
                WHERE 
                    srv.status_servico = 'ativo'";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);

    }


    /** MÉTODO PARA O DASHBOARD - ADICIONAR SERVIÇO */
    public function addServico($dados)
    {
        $sql = "INSERT INTO tbl_gabrielm_servico  (
        nome_servico,
        descricao_servico,
        preco_base_servico,
        tempo_estimado_servico,
        id_especialidade,
        status_servico,
        link_servico)
         VALUES
        ( :nome_servico,
        :descricao_servico,
        :preco_base_servico,
        :tempo_estimado_servico,
        :id_especialidade,
        :status_servico,
        :link_servico)";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue('nome_servico', $dados['nome_servico']);
        $stmt->bindValue('descricao_servico', $dados['descricao_servico']);
        $stmt->bindValue('preco_base_servico', $dados['preco_base_servico']);
        $stmt->bindValue('tempo_estimado_servico', $dados['tempo_estimado_servico']);
        $stmt->bindValue('id_especialidade', $dados['id_especialidade']);
        $stmt->bindValue('status_servico', $dados['status_servico']);
        $stmt->bindValue('link_servico', $dados['link_servico']);

        $stmt->execute();

        return $this->db->lastInsetId();

    }


    //Método para buscar os dados do Serviço de acordo com o ID
    public function getServicoById($id)
    {
        $sql = "SELECT s.*, g.foto_galeria, e.nome_especialidade
from tbl_gabrielm_servico as s
left join tbl_gabrielm_galeria as g
on s.id_servico = g.id_galeria
inner join tbl_gabrielm_especialidade as e 
on s.id_servico = e.id_especialidade
 where s.id_servico= :id_servico
 limit 1";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id_servico', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function existeEsseServico($link)
    {
        $sql = "select count(*) as total from tbl_gabrielm_servico where link_servico = :link";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':link', $link);
        $stmt->execute();

        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        return $resultado['total'] > 0;
    }

    public function addFotoGaleria($id_servico, $arquivo, $nome_servico)
    {
        $sql = "INSERT INTO  tbl_gabrielm_galeria (foto_galeria, alt_galeria, status_galeria, id_servico) VALUES(:foto_galeria, :alt_galeria, :status_galeria, :id_servico)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':foto_galeria', $arquivo);
        $stmt->bindValue(':alt_galeria', $nome_servico);
        $stmt->bindValue(':status_galeria', 'Ativo');
        $stmt->bindValue(':id_servico0', $id_servico);
    }

    public function obterOuCriarEspecialidade($nome)
    {
        $sql = "INSERT INTO tbl_gabrielm_especialidade (nome_especialidade) VALUES (:nome)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':nome', $nome);
        if ($stmt->execute()) {
            return $this->db->lastInsetId();
        }
        return false;
    }



}
