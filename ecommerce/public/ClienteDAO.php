<?php

require_once("Cliente.php");

class ClienteDAO{

    var $pdo;

    function ClienteDAO(){
        $servername = "localhost";
        $username = "root";
        $password = "";
        $databasename = "ecommerce";
        
        try{
            $this->pdo = new PDO("mysql:host=$servername;dbname=$databasename;port=3306", $username, $password);
            // set the PDO error mode to exception
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);        
        }
        catch(PDOException $e)
        {
            echo "Connection failed: " . $e->getMessage();
        }
    }

    function inserir(Cliente $cliente){
        try{
            $recebe = $this-> pdo -> prepare("INSERT INTO clientes (nome, idade, email, telefone, cep, endereco)
            VALUES (:nome, :idade, :email, :telefone, :cep, :endereco)");
            $recebe->bindValue(':nome', $cliente->getNome());
            $recebe->bindValue(':idade', $cliente->getIdade());
            $recebe->bindValue(':email', $cliente->getEmail());
            $recebe->bindValue(':telefone', $cliente->getTelefone());
            $recebe->bindValue(':cep', $cliente->getCep());
            $recebe->bindValue(':endereco', $cliente->getEndereco());
            $recebe-> execute();
        }
        catch(PDOException $e)
        {
            echo "Statement failed: " . $e->getMessage();
        }
    }

    function listar(){
        $listaCliente = array();
        try{
            $recebe = $this->pdo->prepare('SELECT * FROM clientes');
            $recebe -> execute();
            $listaCliente = $recebe->fetchAll( PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'Cliente', [0,'',0,'','',0,'']);
            return $listaCliente;
        }
        catch(PDOException $e)
        {
            echo "Statement failed: " . $e->getMessage();
        }
    }

    function buscarPorid($id){
        try{
            $recebe = $this->pdo->prepare("SELECT * FROM clientes WHERE id=:id");
            $recebe-> bindParam(':id', $id);
            $recebe-> execute();
            $recebe->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'Cliente', [0, '',0,'','',0,'']);
            $obj = $recebe->fetch();
                return $obj;
        }
        catch(PDOException $e)
        {
            echo "Statement failed: " . $e->getMessage();
        }
    }

    function buscarPornome($nome){
        try{
            $recebe = $this->pdo->prepare("SELECT * FROM clientes WHERE nome=:nome");
            $recebe->bindParam(':nome', $nome);
            $recebe-> execute();
            $recebe->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE,'Cliente', [0,'',0,'','',0,'']);
            $obj = $recebe->fetch();
                return $obj;
        }
        catch(PDOException $e)
        {
            echo "Statement failed: " . $e->getMessage();
        }

    }

    function deletar($id){
        try{
            $recebe = $this->pdo->prepare("DELETE FROM clientes WHERE id=:id");
            $recebe-> bindParam(':id',$id);
            $recebe-> execute();
            return true;
                       
        }
        catch(PDOException $e)
        {
            echo "Statement failed: " . $e->getMessage();
            return FALSE;
        }
    }
    
    
    function atualizar(Cliente $clienteAlterado){
        try{                
            $recebe = $this-> pdo-> prepare("UPDATE clientes SET nome=:nome, idade=:idade, email=:email, telefone=:telefone, cep=:cep, endereco=:endereco  WHERE id=:id");
            $recebe->bindValue(":nome",$clienteAlterado->getNome());
            $recebe->bindValue(":idade",$clienteAlterado->getIdade());
            $recebe->bindValue(":email",$clienteAlterado->getEmail());
            $recebe->bindValue(":telefone",$clienteAlterado->getTelefone());
            $recebe->bindValue(":cep",$clienteAlterado->getCep());
            $recebe->bindValue(":endereco",$clienteAlterado->getEndereco());
            $recebe->bindValue(":id",$clienteAlterado->getId());
            $recebe->execute();       
        }
        catch(PDOException $e)
        {
            echo "Statement failed: " . $e->getMessage();
        }

    }
}
?>