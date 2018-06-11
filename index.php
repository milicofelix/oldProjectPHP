<?php
/**
 * Created by PhpStorm.
 * User: milico
 * Date: 04/06/18
 * Time: 22:33
 */
include_once "core/classes/Mysql.php";

$db = new Mysql();

$db->setBanco("cfswpa");
$db->setUsuario('root');
$db->setSenha('morango');
$db->setPorta('3306');

$db->conectar();


//$sql = "CREATE TABLE tab_teste(codigo int DEFAULT 0, descricao VARCHAR(20), valor FLOAT, PRIMARY KEY(codigo))";
//
//if($db->executaSQL($sql) !== false){
//    echo "Tabela criada com sucesso! <br />";
//}

//$sql = "INSERT INTO tab_teste VALUES (1,'teste 1',1.5)";
//if($db->executaSQL($sql)!== false){
//    echo "Registro inserido com sucesso!";
//}

//$sql = "INSERT INTO tab_teste VALUES (2,'teste 2',3.5)";
//if($db->executaSQL($sql)!== false){
//    echo "2º Registro inserido com sucesso!";
//}

//$sql = "INSERT INTO tab_teste VALUES (3,'teste 3',7.12)";
//if($db->executaSQL($sql)!== false){
//    echo "3º Registro inserido com sucesso!";
//}

//$sql = "INSERT INTO tab_teste VALUES (4,'teste 4',12.12)";
//if($db->executaSQL($sql)!== false){
//    echo "3º Registro inserido com sucesso!";
//}

//    $sql = "INSERT INTO tab_teste VALUES (4,'teste 3',21.7)";
//    $db->startTransaction();
//        $db->executaSQL($sql);
//        $sql = "DELETE FROM tab_teste";
//        $db->executaSQL($sql);
//    $db->rollBack();

$sql = "SELECT * FROM tab_teste";

$db->executaSQL($sql);

echo "Número de registros retornado pelo SELECT {$db->getNumRows()}<br />";
echo "<table border='1' cellpadding='5' width='400'>
    <tr>
        <th>Cógido</th>
        <th>Descrição</th>
        <th>Valor</th>
    </tr>";
while ($d = $db->proximo()){
    echo "<tr>
            <td>{$d['CODIGO']}</td>
            <td>{$d['DESCRICAO']}</td>
            <td>{$d['VALOR']}</td>
          </tr>";
}
echo "</table>";

echo "<h1>Acesso ao banco de dados</h1>";
echo "<pre>";
print_r($db);
echo "</pre>";
