<?php

require_once("Cliente.php");
require_once("ClienteDAO.php");
require_once("ProdutoDAO.php");
require_once("Carrinho.php");
require_once("CarrinhoDAO.php");
require_once("Itens_carrinhoDAO.php");

$dao_cliente = new ClienteDAO();
$c1 = new Cliente(1, 'Jonas', 34, 'jonas-couto@hotmail.com', '33333333', 91520-120, ' marista 555');
$dao_cliente->inserir($c1);
$dao_cliente->listar();
var_dump($dao_cliente->buscarPornome('Jonas'));
var_dump($dao_cliente->buscarPorid(5));
$dao_cliente->deletar(4);

$dao_produto = new ProdutoDAO();
$p1 = new Produto( 1,"Tv", 200);
$dao_produto->inserir($p1);
$dao_produto->listar();
var_dump($dao_produto->listar());
die();

$dao_carrinho = new CarrinhoDAO(); 
$dao_item = new Itens_carrinhoDAO();
$carrinho = new Carrinho(1, $c1, date("Y-m-d H:i:s"), false);
$dao_carrinho->inserir($carrinho);
var_dump($dao_carrinho->buscarPorid(7));
echo 'BUSCA POR ID CLIENTE';
var_dump($dao_carrinho->buscarPoridcliente(2));
$dao_carrinho->deletar(6);

$item = new Itens_carrinho($p1,2);
$carrinho->adiciona_itens($item);
$dao_item ->inserir($item, $carrinho->getId());
$item->setQuantidade(5);
$dao_item->atualizar($item,$carrinho->getId());
var_dump($dao_item->buscarPorid(1, 1));
/*$dao_item->deletar($carrinho->getId(),$p1->getId());*/

$carrinho->finalizarCompra();
$dao_item->buscarPorcarrinho(1);
var_dump($dao_item->buscarPorcarrinho(1));
?>