<?php
    require_once("Itens_carrinho.php");

class Itens_carrinhoDAO{

    var $pdo;

    function Itens_carrinhoDAO(){
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


    function inserir(Itens_carrinho $itens_carrinho, $carrinho_id){
        try{
            $recebe = $this->pdo->prepare("INSERT INTO itens_carrinho(carrinho_id, produto_id, quantidade) VALUES (:carrinho_id, :produto_id, :quantidade)");
            $recebe->bindValue(':carrinho_id', $carrinho_id);
            $recebe->bindValue(':produto_id', $itens_carrinho->getProduto()->getId());
            $recebe->bindValue(':quantidade', $itens_carrinho->getQuantidade());
            $recebe->execute();
        }
        catch(PDOException $e)
        {
            echo "Connection failed: " . $e->getMessage();
        }
    }

    function deletar($carrinho_id, $produto_id){
        try{
            $recebe = $this->pdo->prepare("DELETE FROM itens_carrinho WHERE carrinho_id=:carrinho_id and produto_id=:produto_id");
            $recebe->bindParam(':carrinho_id',$carrinho_id);
            $recebe->bindParam(':produto_id',$produto_id);
            $recebe-> execute();
            return true;
                       
        }
        catch(PDOException $e)
        {
            echo "Statement failed: " . $e->getMessage();
            return FALSE;
        }
    }

    function atualizar(Itens_carrinho $itemAlterado, $carrinho_id){
        try{                
            $recebe = $this-> pdo-> prepare("UPDATE itens_carrinho SET quantidade=:quantidade WHERE carrinho_id=:carrinho_id and produto_id=:produto_id");
            $recebe->bindValue(":carrinho_id",$carrinho_id);
            $recebe->bindValue(":produto_id",$itemAlterado->getProduto()->getId());
            $recebe->bindValue(":quantidade",$itemAlterado->getQuantidade());
            $recebe->execute();       
        }
        catch(PDOException $e)
        {
            echo "Statement failed: " . $e->getMessage();
        }

    }

    function buscarPorid($carrinho_id, $produto_id){
        try{
            $recebe = $this->pdo->prepare("SELECT * FROM itens_carrinho WHERE carrinho_id=:carrinho_id and produto_id=:produto_id");
            $recebe-> bindParam(':carrinho_id', $carrinho_id);
            $recebe-> bindParam(':produto_id', $produto_id);
            $recebe-> execute();
            $recebe->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'Itens_carrinho', [0, 0, 0]);
            $item = $recebe->fetch();
            
            $recebe = $this->pdo->prepare('SELECT * FROM produtos WHERE id=:id');
            $recebe -> bindParam(':id',$produto_id);
            $recebe -> execute();
            $recebe -> setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE,'Produto', [0,'',0]);
            $produto = $recebe->fetch();
            $item->setProduto($produto);
            return $item;
            
        }
        catch(PDOException $e)
        {
            echo "Statement failed: " . $e->getMessage();
        }
    }
    function buscarPorcarrinho($carrinho_id){
        $listaItem = array();
        try{
            $recebe = $this->pdo->prepare('SELECT * FROM Itens_carrinho WHERE carrinho_id=:carrinho_id');
            $recebe -> bindParam(':carrinho_id', $carrinho_id);
            $recebe -> execute();
            $listaItem = $recebe -> fetchAll( PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'Itens_carrinho', [0, 0]);
            return $listaItem;
        }
        catch(PDOException $e)
        {
            echo "Statement failed: " . $e->getMessage();
        }
    }

    /* buscar todos os itens com o Id do carrinho*/
}
?>