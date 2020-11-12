<?php

require_once("Produto.php");

class ProdutoDAO{

    private $pdo;

    public function ProdutoDAO(){
        $servername = "localhost";
        $username = "root";
        $password = "";
        $databasename = "ecommerce";
        
        try{
            $this->pdo = new PDO("mysql:host=$servername;dbname=$databasename;port=3306", $username, $password);
            // set the PDO error mode to exception
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $this->pdo->setAttribute(PDO::ATTR_STRINGIFY_FETCHES,false);
            $this->pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);        
        }
        catch(PDOException $e)
        {
            echo "Connection failed: " . $e->getMessage();
        }        
    }

    function inserir(Produto $produto){
        try{
            $recebe = $this-> pdo -> prepare("INSERT INTO produtos (nome, valor) VALUE (:nome, :valor)");
            $recebe ->bindValue(':nome', $produto->getNome());
            $recebe ->bindValue(':valor', $produto->getValor());
            $recebe ->execute();
        }
        catch(PDOException $e)
        {
            echo "Connection failed: " . $e->getMessage();
        }
    }

    function listar(){
        $listaProduto = array();
        try{
            $recebe = $this->pdo->prepare("SELECT * FROM produtos");
            $recebe-> execute();
            $listaProduto = $recebe->fetchAll( PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'Produto', [0,'', 0]);
            return $listaProduto;
        }
        catch(PDOException $e)
        {
            echo "Statement failed: " . $e->getMessage();
        }
    }

    function buscarPorid($id){
        try{
            $recebe = $this->pdo->prepare('SELECT * FROM produtos WHERE id=:id');
            $recebe -> bindParam(':id', $id);
            $recebe -> execute();
            $recebe -> setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE,'Produto', [0,'',0]);
            $obj = $recebe->fetch();
            return $obj;
        }
        catch(PDOException $e)
        {
            echo "Statement failed: " . $e->getMessage();
        }
    }

    function buscarPornome($nome){
        $listaProduto = array();
        try{
            $recebe = $this->pdo->prepare('SELECT * FROM produtos WHERE nome=:nome');
            $recebe -> bindParam(':nome', $nome);
            $recebe -> execute();
            $listaProduto = $recebe -> fetchAll( PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'Produto', [0,'', 0]);
            return $listaProduto;
        }
        catch(PDOException $e)
        {
            echo "Statement failed: " . $e->getMessage();
        }
    }

    function deletar($id){
        try{
            $recebe = $this->pdo->prepare('DELETE FROM produtos WHERE id=:id');
            $recebe -> bindParam(':id', $id);
            $recebe -> execute();
            return true;
        }
        catch(PDOException $e)
        {
            echo "Statement failed: " . $e->getMessage();
            return FALSE;
        }
    }

    function atualizar(Produto $produtoalterado){
        try{
            $recebe = $this->pdo->prepare('UPDATE produtos SET nome=:nome, valor=:valor WHERE id=:id');
            $recebe -> bindValue(':nome', $produtoalterado->getNome());
            $recebe -> bindValue(':valor', $produtoalterado->getValor());
            $recebe -> bindValue(':id', $produtoalterado->getId());
            $recebe -> execute();
        }
        catch(PDOException $e)
        {
            echo "Statement failed: " . $e->getMessage();
        }       
    }
}
?>