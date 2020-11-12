<?php

class Usuario{
    public $id, $nome, $login, $senha; 


    functioN __construct($id, $nome, $login, $senha){

        $this-> id = $id;
        $this-> nome = $nome;
        $this-> login = $login;
        $this-> senha = $senha;        
    }

    public function getId(){
        return $this-> id;
    }

    public function getNome(){
        return $this-> nome;
    }

    public function getLogin(){
        return $this-> login;
    }

    public function getSenha(){
        return $this-> senha;
    }

    
    public function setNome($nome){
        $this-> nome = $nome;
    }

    public function setLogin($login){
        $this-> login = $login;
    }

    
    public function setSenha($senha){
        $this-> senha = $senha;
    }    

}

?>