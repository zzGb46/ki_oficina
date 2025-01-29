<?php

class Cliente extends Model
{


    public function buscarCliente($email)
    {

        $sql = "SELECT * FROM tbl_gabrielm_cliente WHERE email_cliente = :email AND status_cliente = 'Ativo'";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':email', $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getContarCliente()
    {

        $sql = "SELECT COUNT(*) AS total_clientes FROM tbl_gabrielm_cliente";
        $stmt = $this->db->query($sql);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getTodosClientes()
    {

        $sql = "SELECT
        cli.id_cliente,
        cli.nome_cliente,
        cli.telefone_cliente,
        cli.email_cliente,
        cli.foto_cliente
    FROM
        tbl_gabrielm_cliente AS cli
    WHERE
        cli.status_cliente = 'ativo'";

$stmt = $this->db->query($sql);
return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


}