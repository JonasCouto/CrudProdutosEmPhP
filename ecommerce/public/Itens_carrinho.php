<?php
require_once("Carrinho.php");
require_once("Produto.php");

class Itens_carrinho{
    public $produto, $quantidade;

    public function __construct($produto, $quantidade){

        $this-> produto = $produto;
        $this-> quantidade = $quantidade;
    }

    function calculaTotal(){
        return $this-> quantidade * $this->produto->getValor();
    }

    function getProduto(){
        return $this-> produto;
    }

    function getQuantidade(){
        return $this-> quantidade;
    }

    function setProduto($produto){
        $this-> produto = $produto;
    }

    function setQuantidade($quantidade){
        $this-> quantidade = $quantidade;
    }

}
?>