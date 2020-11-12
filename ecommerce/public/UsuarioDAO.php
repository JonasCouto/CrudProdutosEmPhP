<?php

require_once("Usuario.php");

class UsuarioDAO{

    var $pdo;

    function UsuarioDAO(){
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

    function inserir(Usuario $usuario){
        try{
            $recebe = $this-> pdo -> prepare("INSERT INTO usuario (nome, login,senha)
            VALUES (:nome, :login, :senha)");
            $recebe->bindValue(':nome', $usuario->getNome());
            $recebe->bindValue(':login', $usuario->getLogin());
            $recebe->bindValue(':senha', $usuario->getSenha());
            $recebe-> execute();
        }
        catch(PDOException $e)
        {
            echo "Statement failed: " . $e->getMessage();
        }
    }

    function listar(){
        $listaUsuario = array();
        try{
            $recebe = $this->pdo->prepare('SELECT * FROM usuario');
            $recebe -> execute();
            $listaUsuario = $recebe->fetchAll( PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'Usuario', [0,'','','']);
            return $listaUsuario;
        }
        catch(PDOException $e)
        {
            echo "Statement failed: " . $e->getMessage();
        }
    }

    function buscarPorid($id){
        try{
            $recebe = $this->pdo->prepare("SELECT * FROM Usuario WHERE id=:id");
            $recebe-> bindParam(':id', $id);
            $recebe-> execute();
            $recebe->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'Usuario', [0, '','','']);
            $obj = $recebe->fetch();
                return $obj;
        }
        catch(PDOException $e)
        {
            echo "Statement failed: " . $e->getMessage();
        }
    }

    function buscarPorLogin($login){
        try{
            $recebe = $this->pdo->prepare("SELECT * FROM Usuario WHERE login=:login");
            $recebe-> bindParam(':login', $login);
            $recebe-> execute();
            $recebe->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'Usuario', [0, '','','']);
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
            $recebe = $this->pdo->prepare("DELETE FROM usuario WHERE id=:id");
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
    
    
    function atualizar(Usuario $usuarioAlterado){
        try{                
            $recebe = $this-> pdo-> prepare("UPDATE usuario SET nome=:nome, login=:login, senha=:senha  WHERE id=:id");
            $recebe->bindValue(":nome",$usuarioAlterado->getNome());
            $recebe->bindValue(":login",$usuarioAlterado->getLogin());
            $recebe->bindValue(":senha",$usuarioAlterado->getSenha());

            $recebe->execute();       
        }
        catch(PDOException $e)
        {
            echo "Statement failed: " . $e->getMessage();
        }

    }
}
?>