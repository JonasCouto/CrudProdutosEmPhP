<?php

require_once("Cliente.php");
require_once("Produto.php");
require_once("Itens_carrinho.php");

class Carrinho{

    public $id, $cliente, $data, $compra_finalizada;
    public $lista_itens = array();

    public function __construct($id, $cliente, $data, $compra_finalizada){

        $this-> id = $id;
        $this-> cliente = $cliente;
        $this-> data = $data;
        $this-> compra_finalizada = $compra_finalizada;
    }

    function imprimi(){
        echo 'Cliente :' . $this->cliente->getNome() . "\n";
        echo 'Data :' . $this-> data . "\n";
        foreach ($this->lista_itens as $iten){
            echo 'Produto :' .  $iten->getProduto()->getNome() . "\n";
            echo 'Valor :' . $iten->getProduto()->getValor() . "\n";
            echo 'Quantidade :' . $iten->getQuantidade() . "\n";
            echo 'Total: R$ ' . $iten->calculaTotal() . "\n";
        }
        echo 'TOTAL: R$ ' . $this->totalCarinho() . "\n";
    }

    function finalizarCompra(){
        if (count($this->lista_itens) > 0){
            $this-> data = date('Y-m-d H:i:s');
            $this-> compra_finalizada = TRUE;
            $this-> imprimi();
            echo "COMPRA FINALIZADA. \n";
        }
        else {
            echo "Carrinho esta vazio. \n";
        }
    }

    function totalCarinho(){
        $totalCompra = 0;
        foreach ($this->lista_itens as $iten){
            $totalCompra = $totalCompra + $iten->calculaTotal();
        }
        return $totalCompra;
    }

    function remove_itens(Produto $produto, $qtd_produto){
        foreach ($this->lista_itens as $iten){
            if ($iten->getProduto()->getId() == $produto->getId()){
                if ($iten->getQuantidade() == $qtd_produto){
                    unset($iten);
                }
                else {
                    $newQtd = $iten->getQuandidade() - $qtd_produto;
                    $iten->setQuantidade($newQtd);
                }

            }
        }       
    }

    function adiciona_itens(Itens_carrinho $item){
        $this->lista_itens[] = $item;
    }

    function getId(){
        return $this-> id;
    }

    function getCliente(){
        return $this-> cliente;
    }

    function getData(){
        return $this-> data;
    }

    function getCompra_finalizada(){
        return $this-> compra_finalizada;
    }

    function setData($data){
        $this-> data = $data;
    }

    function setCompra_finaliada($compra_finalizada){
        $this-> compra_finalizada = $compra_finalizada;
    }

}

?>