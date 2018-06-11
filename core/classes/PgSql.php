<?php
/**
 * Created by PhpStorm.
 * User: milico
 * Date: 11/06/18
 * Time: 19:20
 */
/**
 * Classe para gerenciamento do banco de dados PostgreSQL (pgsql)
 *
 */
class PgSql extends BancoDados {

    public function __construct() {
        $this->_Tipo = 'pgsql';
    }

    protected function SetNumRows() {
        $this->_numrows = ($this->_dataset!==false ? pg_num_rows($this->_dataset) : 0);
    }

    public function Conectar() {
        $_strcon = "host={$this->_Servidor} ";
        $_strcon.= "dbname={$this->_Banco} ";
        $_strcon.= "user={$this->_Usuario} ";
        if($this->_Senha!=NULL&&$this->_Senha!="") {
            $_strcon.= "password={$this->_Senha} ";
        }
        if(is_int($this->_Porta)) {
            $_strcon.= "port={$this->_Porta}";
        }
        if(($this->_conn = @pg_connect($_strcon))===false) {
            $_e = error_get_last();
            $this->_ultimoerro = "Erro na Conexão com o Banco de Dados : {$_e['message']}";
        }
        return $this->_conn;
    }

    public function executaSQL($_sql) {
        if($this->_conn!==false) {
            if(($_res=@pg_query($this->_conn,$_sql))===false) {
                $this->_ultimoerro = "Erro ao Executar o comando {$_sql} : " . pg_last_error();
            } else {
                $this->isSELECT($_sql,$_res);
            }
            return $_res;
        } else {
            return false;
        }
    }

    protected function navegainterno($_pos) {
        if(pg_result_seek($this->_dataset,$_pos)!==false) {
            $this->_tupla = pg_fetch_assoc($this->_dataset);
        }
    }

    protected function proximointerno() {
        $this->_tupla = pg_fetch_assoc($this->_dataset);
    }

    public function escapeString($_str) {
        return pg_escape_string($this->_conn,$_str);
    }

    public function getLimit($_arr) {
        return " {$_arr['LIMIT']} OFFSET {$_arr['OFFSET']}";
    }

    public function setAffectedRows() {
        $this->_numrows = ($this->_dataset!==false ? pg_affected_rows($this->_dataset) : 0);
    }

}