<?php
/**
 * Created by PhpStorm.
 * User: milico
 * Date: 04/06/18
 * Time: 23:32
 */

include_once "BancoDados.php";

class Mysql extends BancoDados {

    public function __construct() {
        $this->tipo = 'mysql';
    }

    protected function setNumRows() {
        $this->numrows = ($this->dataset!==false ? $this->conn->num_rows : 0);
    }

    public function conectar() {
        if(($this->conn = new mysqli($this->servidor,$this->usuario,$this->senha,$this->banco,$this->porta))===false) {
            $this->ultimoerro = "Erro na Conexão com o Banco de Dados : " . mysqliconnect_error();
        }
        return $this->conn;
    }

    public function executaSQL($_sql) {
        if($this->conn!==false) {
            if(($_res=@$this->conn->query($_sql))===false) {
                $this->ultimoerro = "Erro ao Executar o comando {$_sql} : " . $this->conn->error;
            }
            $this->isSelect($_sql,$_res);
            return $_res;
        } else {
            return false;
        }
    }

    protected function navegaInterno($_pos) {
        if($this->dataset!==false) {
            if($this->dataset->data_seek($_pos)!==false) {
                $this->tupla = $this->dataset->fetch_assoc();
            }
        }
    }

    protected function proximoInterno() {
        if($this->dataset!==false) {
            $this->tupla = $this->dataset->fetch_assoc();
        }
    }

    public function escapeString($_str) {
        return $this->conn->real_escape_string($_str);
    }

    public function getLimit($_arr) {
        return " {$_arr['OFFSET']},{$_arr['LIMIT']}";
    }

    public function setAffectedRows() {
        $this->numrows = ($this->dataset!==false ? $this->conn->affected_rows : 0);
    }

}