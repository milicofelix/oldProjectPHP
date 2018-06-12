<?php
/**
 * Created by PhpStorm.
 * User: milico
 * Date: 04/06/18
 * Time: 22:58
 */

/** Classe básica para acesso a Banco de dados
*   @astract
*/
abstract class BancoDados {
    protected $tipo = null;	// Definido por cada banco de dados
    protected $servidor = 'localhost';
    protected $porta = 0;
    protected $usuario;
    protected $senha;
    protected $banco;
    protected $conn = false;
    protected $dataset = false;
    protected $numrows = -1;
    protected $tupla = false;
    protected $posatual = -1;
    protected $ultimoerro = "";
    protected $ind_case = CASE_UPPER;

    abstract public function conectar();
    abstract public function executaSQL($_sql);
    abstract protected function setNumRows();
    abstract protected function navegaInterno($_pos);
    abstract protected function proximoInterno();
    abstract public function escapeString($_str);
    abstract public function getLimit($_arr);
    abstract protected function setAffectedRows();

    public function setServidor($servidor) {
        $this->servidor = $servidor;
    }

    public function setPorta($porta) {
        $this->porta = $porta;
    }

    public function setUsuario($usuario) {
        $this->usuario = $usuario;
    }

    public function setSenha($senha) {
        $this->senha = $senha;
    }

    public function setBanco($banco) {
        $this->banco = $banco;
    }

    public function startTransaction() {
        $this->executaSQL('START TRANSACTION');
    }

    public function commit() {
        $this->executaSQL('COMMIT');
    }

    public function rollBack() {
        $this->executaSQL('ROLLBACK');
    }

    protected function isSelect($_sql,$_res) {
        $this->dataset = $_res;
        if(substr(trim(strtolower($_sql)),0,6)=='select') {
            $this->setNumRows();
        } else {
            $this->setAffectedRows();
        }
    }

    public function setCase($_case=CASE_UPPER) {
        $this->ind_case = in_array($_case,Array(CASE_LOWER,CASE_NATURAL,CASE_UPPER)) ? $_case : CASE_UPPER;
    }

    public function getNumRows() {
        return $this->numrows;
    }

    public function transforma() {
        $this->tupla = $this->tupla!==false&&$this->tupla!==null ? array_change_key_case($this->tupla,$this->ind_case) : $this->tupla;
    }


    public function navega($_pos=0) {
        $this->tupla = false;
        $this->navegaInterno($_pos);
        $this->posatual = $_pos;
        $this->transforma();
        return $this->tupla;
    }

    public function primeiro() {
        return $this->navega();
    }

    public function proximo() {
        $this->tupla = false;
        $this->proximoInterno();
        $this->posatual++;
        $this->transforma();
        return $this->tupla;
    }

    public function anterior() {
        return $this->navega($this->posatual-1);
    }

    public function ultimo() {
        return $this->navega($this->numrows-1);
    }

    public function getDadosAtual() {
        return $this->tupla;
    }

    public function getUltimoErro() {

        return $this->ultimoerro;
    }

    public function converteDataToBD($_data) {
        // $_data Sempre no formato dd-mm-YYYY
        if(ereg("^([0-9]{1,2})-([0-9]{1,2})-([0-9]{4}).*$",$_data,$_arrdt)!==false) {
            return sprintf("%04.4d-%02.2d-%02.2d",$_arrdt[3],$_arrdt[2],$_arrdt[1]);
        }
        return false;
    }

    public function converteDataHoraToBD($_dtime) {
        // $_dtime Sempre no formato dd-mm-YYYY HH:MM:SS
        if(ereg("^([0-9]{1,2})-([0-9]{1,2})-([0-9]{4})[[:space:]]([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2}).*$",$_dtime,$_arrdt)!==false) {
            return sprintf("%04.4d-%02.2d-%02.2d %02.2d:%02.2d:%02.2d",$_arrdt[3],$_arrdt[2],$_arrdt[1],$_arrdt[4],$_arrdt[5],$_arrdt[6]);
        }
        return false;
    }

    public function converteDataToHTML($_data) {
        // $_data Sempre no formato YYYY-MM-DD
        if(ereg("^([0-9]{4})-([0-9]{1,2})-([0-9]{1,2}).*$",$_data,$_arrdt)!==false) {
            return sprintf("%02.2d-%02.2d-%04.4d",$_arrdt[3],$_arrdt[2],$_arrdt[1]);
        }
        return false;
    }

    public function convereteDataHoraToHTML($_dtime) {
        // $_dtime Sempre no formato YYYY-MM-DD HH:MM:SS
        if(ereg("^([0-9]{4})-([0-9]{1,2})-([0-9]{1,2})[[:space:]]([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2}).*$",$_dtime,$_arrdt)!==false) {
            return sprintf("%02.2d-%02.2d-%04.4d %02.2d:%02.2d:%02.2d",$_arrdt[3],$_arrdt[2],$_arrdt[1],$_arrdt[4],$_arrdt[5],$_arrdt[6]);
        }
        return false;
    }

}
