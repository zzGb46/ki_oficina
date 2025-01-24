<?php
class Especialidade extends Model{
 
    public function getTodasEspecialidades(){
 
        $sql = " SELECT * FROM tbl_gabrielm_especialidade ORDER BY nome_especialidade ASC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}