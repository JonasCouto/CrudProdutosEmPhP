<?php
    require_once("Carrinho.php");

class CarrinhoDAO{
    var $pdo;

    function CarrinhoDAO(){
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

    function inserir(Carrinho $carrinho){
        try{
            $recebe = $this->pdo->prepare("INSERT INTO Carrinho (cliente_id, data, compra_finalizada) VALUES (:cliente_id, :data, :compra_finalizada)");
            var_dump($carrinho->getCliente()->getId());
            $recebe->bindValue(':cliente_id', $carrinho->getCliente()->getId());
            $recebe->bindValue(':data', $carrinho->getData());
            $recebe->bindValue(':compra_finalizada', $carrinho->getCompra_finalizada());
            $recebe->execute();
        }
        catch(PDOException $e)
        {
            echo "Statement failed: " . $e->getMessage();
        }
    }

    function buscarPorid($id){
        try{
            $recebe = $this->pdo->prepare("SELECT * FROM carrinho WHERE id=:id");
            $recebe-> bindParam(':id', $id);
            $recebe-> execute();
            $recebe->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'Carrinho', [0, 0,'', false]);
            $obj = $recebe->fetch();
                return $obj;
        }
        catch(PDOException $e)
        {
            echo "Statement failed: " . $e->getMessage();
        }
    }

    function buscarPoridcliente($client_id){
        $lista_id_cliente = array();
        try{
            $recebe = $this->pdo->prepare("SELECT * FROM  carrinho WHERE cliente_id=:cliente_id");
            $recebe-> bindParam(':cliente_id', $client_id);
            $recebe-> execute();
            $lista_id_cliente = $recebe->fetchAll( PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'Carrinho', [0, 0, '', False]);
            return $lista_id_cliente;
        }
        catch(PDOException $e)
        {
            echo "Statement failed: " . $e->getMessage();
        }
    }

    function deletar($id){
        try{
            $recebe = $this->pdo->prepare("DELETE FROM carrinho WHERE id=:id");
            $recebe-> bindParam(':id',$id);
            $recebe-> execute();
            return true;
                       
        }
        catch(PDOException $e)
        {
            echo "Statement failed: " . $e->getMessage();
            return false;
        }
    }

}

?>