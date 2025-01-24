<?php



class Depoimento extends Model
{
    public function getTodosDepoimentos()
    {

        $sql = "SELECT nome_cliente, cidade_cliente, descricao_depoimento, nota_depoimento, foto_cliente, alt_foto_cliente FROM tbl_depoimento d
        INNER JOIN tbl_gabrielm_cliente c ON d.id_cliente = c.id_cliente
        INNER JOIN tbl_gabrielm_estado e ON c.id_uf = e.id_uf
        WHERE status_depoimento = 'Aprovado'
        ORDER BY nota_depoimento DESC, nome_cliente ASC;";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getDepoimentoPositivo()
    {
        $sql = "SELECT COUNT(id_depoimento) AS qtde_depoimento FROM tbl_depoimento WHERE nota_depoimento >= 4";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}
