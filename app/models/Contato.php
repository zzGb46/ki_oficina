<?php

class Contato extends Model
{

    //Salvar o email na base de dados
    public function salvarEmail($assunto, $nome, $email, $tel, $msg)
    {

        $sql = "INSERT INTO tb_contato(assunto_contato, nome_contato, email_contato, tel_contato, mensagem_contato)
       VALUE (:assunto_contato, :nome_contato, :email_contato, :tel_contato, :mensagem_contato)";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':assunto_contato', $assunto);
        $stmt->bindValue(':nome_contato', $nome);
        $stmt->bindValue(':email_contato', $email);
        $stmt->bindValue(':tel_contato', $tel);
        $stmt->bindValue(':mensagem_contato', $msg);

        return $stmt->execute();



    }




}

