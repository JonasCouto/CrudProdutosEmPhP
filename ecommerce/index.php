<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;


require_once('public/Produto.php');
require_once('public/ProdutoDAO.php');
require_once('public/Cliente.php');
require_once('public/ClienteDAO.php');
require_once('public/Carrinho.php');
require_once('public/CarrinhoDAO.php');
require_once('public/Itens_carrinho.php');
require_once('public/itens_carrinhoDAO.php');
require_once('public/UsuarioController.php');
require __DIR__ . '/vendor/autoload.php';

/**
 * Instantiate App
 *
 * In order for the factory to work you need to ensure you have installed
 * a supported PSR-7 implementation of your choice e.g.: Slim PSR-7 and a supported
 * ServerRequest creator (included with Slim PSR-7)
 */
$app = AppFactory::create();
$app->setBasePath('/ecommerce');

// Add Routing Middleware
$app->addRoutingMiddleware();
$app->addBodyParsingMiddleware();

/**
 * Add Error Handling Middleware
 *
 * @param bool $displayErrorDetails -> Should be set to false in production
 * @param bool $logErrors -> Parameter is passed to the default ErrorHandler
 * @param bool $logErrorDetails -> Display error details in error log
 * which can be replaced by a callable of your choice.
 
 * Note: This middleware should be added last. It will not handle any exceptions/errors
 * for middleware added after it.
 */
$errorMiddleware = $app->addErrorMiddleware(true, true, true);

// Define app routes
$app->get('/hello/{name}', function (Request $request, Response $response, $args) {
    $name = $args['name'];
    $response->getBody()->write("Hello, $name");
    return $response;
});


$app->post('/usuarios','UsuarioController:inserir');
$app->post('/usuarios/login','UsuarioController:autenticar');



$app->group('/produto',function($app){

    $app->get('/listar', function (Request $request, Response $response, $args) {
        $dao_produto = new ProdutoDAO();
        $data = $dao_produto->listar();
        $payload = json_encode($data);
        $response->getBody()->write($payload);
        return $response-> withHeader ( 'Content-Type' , 'application/json' );
    });

    $app->post('/inserir', function (Request $request, Response $response, array $args) {
        //Adicione nome e preço no request (formato JSON)
        $data = $request->getParsedBody();
        $produto = new Produto(0,$data['nome'],$data['valor']);
        $dao = new ProdutoDAO();
        $dao->inserir($produto);
        $payload = json_encode($produto);
            
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
    });

    $app->get('/busca/{id}', function (Request $request, Response $response, array $args) {
        $id = $args['id'];
        
        $dao= new ProdutoDAO;    
        $produto = $dao->buscarPorid($id);
        
        $payload = json_encode($produto);
            
        $response->getBody()->write($payload);
        return $response ->withHeader('Content-Type', 'application/json');
    });

    $app->get('/nome/{nome}', function (Request $request, Response $response, array $args) {
        $nome = $args['nome'];
        
        $dao= new ProdutoDAO;    
        $produto = $dao->buscarPornome($nome);
        
        $payload = json_encode($produto);
            
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    });

    $app->delete('/deletar/{id}', function (Request $request, Response $response, array $args) {
        $id = $args['id'];

        $dao = new ProdutoDAO;
        $produto = $dao->deletar($id);

        $payload = json_encode($produto);
            
        $response->getBody()->write($payload);
        return $response ->withHeader('Content-Type', 'application/json');
    });


    $app->put('/produto/atualizar/{id}', function (Request $request, Response $response, array $args) {
        $id = $args['id'];
        $data = $request->getParsedBody();
        $produto = new Produto($id, $data['nome'], $data['valor']);

        $dao = new ProdutoDAO;
        $dao->atualizar($produto);

        $payload = json_encode($produto);
            
        $response->getBody()->write($payload);
        return $response
                ->withHeader('Content-Type', 'application/json');
    });

})->add('UsuarioController:validarToken');

/*--------------------------------------------------------------------------------------------------*/


$app->group('/cliente',function($app){

    $app->post('/inserir', function (Request $request, Response $response, array $args) {
        //Adicione nome e preço no request (formato JSON)
        $data = $request->getParsedBody();
        $cliente = new Cliente(0,$data['nome'],$data['idade'], $data['email'], $data['telefone'], $data['cep'], $data['endereco']);
        $dao = new ClienteDAO();
        $dao->inserir($cliente);
        $payload = json_encode($cliente);
            
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
    });

    $app->get('/listar', function (Request $request, Response $response, $args) {
        $dao_cliente = new ClienteDAO();
        $data = $dao_cliente->listar();
        $payload = json_encode($data);
        $response->getBody()->write($payload);
        return $response-> withHeader ( 'Content-Type' , 'application/json' );
    });

    $app->get('/busca/{id}', function (Request $request, Response $response, array $args) {
        $id = $args['id'];
        
        $dao= new ClienteDAO;    
        $cliente = $dao->buscarPorid($id);
        
        $payload = json_encode($cliente);
            
        $response->getBody()->write($payload);
        return $response ->withHeader('Content-Type', 'application/json');
    });

    $app->get('/nome/{nome}', function (Request $request, Response $response, array $args) {
        $nome = $args['nome'];
        
        $dao= new ClienteDAO;    
        $cliente = $dao->buscarPornome($nome);
        
        $payload = json_encode($cliente);
            
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    });


    $app->delete('/deletar/{id}', function (Request $request, Response $response, array $args) {
        $id = $args['id'];

        $dao = new ClienteDAO;
        $cliente = $dao->deletar($id);
        $payload = json_encode($cliente);    
        $response->getBody()->write($payload);
        return $response ->withHeader('Content-Type', 'application/json');
    });

    $app->put('/atualizar/{id}', function (Request $request, Response $response, array $args) {
        $id = $args['id'];
        $data = $request->getParsedBody();
        var_dump($data);
        $cliente = new Cliente($id, $data['nome'],$data['idade'], $data['email'], $data['telefone'], $data['cep'], $data['endereco']);
        var_dump($cliente);
        $dao = new ClienteDAO;
        $dao->atualizar($cliente);

        $payload = json_encode($cliente);
            
        $response->getBody()->write($payload);
        return $response ->withHeader('Content-Type', 'application/json');
    });

})->add('UsuarioController:validarToken');
/*----------------------------------------------------------------------------------------------------------------------------------------------*/

$app->post('/carrinho/inserir', function (Request $request, Response $response, array $args) {
    //Adicione nome e preço no request (formato JSON)
    $data = $request->getParsedBody();
    $carrinho = new Carrinho(0,$data['cliente'],$data['data'], $data['compra_finalizada']);
    $dao = new CarrinhoDAO();
    $dao->inserir($carrinho);
    $payload = json_encode($carrinho);
        
    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
});

$app->get('/carrinho/busca/{id}', function (Request $request, Response $response, array $args) {
    $id = $args['id'];
    
    $dao= new CarrinhoDAO;    
    $carrinho = $dao->buscarPorid($id);
    
    $payload = json_encode($carrinho);
        
    $response->getBody()->write($payload);
    return $response ->withHeader('Content-Type', 'application/json');
});

$app->get('/carrinho/nome/{nome}', function (Request $request, Response $response, array $args) {
    $nome = $args['nome'];
    
    $dao= new CarrinhoDAO;    
    $carrinho = $dao->buscarPoridcliente($nome);
    
    $payload = json_encode($carrinho);
        
    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
});


$app->delete('/carrinho/deletar/{id}', function (Request $request, Response $response, array $args) {
    $id = $args['id'];

    $dao = new CarrinhoDAO;
    $carrinho = $dao->deletar($id);

    $payload = json_encode($carrinho);
        
    $response->getBody()->write($payload);
    return $response ->withHeader('Content-Type', 'application/json');
});

/*---------------------------------------------------------------------------------------------------------------------*/

$app->post('/itens_carrinho/inserir/{id}', function (Request $request, Response $response, array $args) {
    //Adicione nome e preço no request (formato JSON)
    $data = $request->getParsedBody();
    $dao_produto = new ProdutoDAO();
    $produto = $dao_produto->buscarPorid($data['produto']);
    $item = new Itens_carrinho($produto,$data['quantidade']);
    $dao = new Itens_carrinhoDAO();
    $carrinho_id = $args['id'];
    $dao->inserir($item, $carrinho_id);
    $payload = json_encode($item);   
    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
});

$app->delete('/itens_carrinho/deletar/{carrinho_id}/{produto_id}', function (Request $request, Response $response, array $args) {
    $carrinho_id = $args['carrinho_id'];
    $produto_id = $args['produto_id'];
    $dao = new Itens_carrinhoDAO;
    $item = $dao->deletar($carrinho_id, $produto_id);
    $payload = json_encode($item);    
    $response->getBody()->write($payload);
    return $response ->withHeader('Content-Type', 'application/json');
});

$app->put('/itens_carrinho/atualizar/{carrinho_id}/{produto_id}', function (Request $request, Response $response, array $args) {
    $carrinho_id = $args['carrinho_id'];
    $produto_id = $args['produto_id'];
    $data = $request->getParsedBody();
    $dao = new Itens_carrinhoDAO;
    $item = $dao->buscarPorid($carrinho_id, $produto_id);
    $item->setQuantidade($data['quantidade']);
    $dao->atualizar($item, $carrinho_id);
    $payload = json_encode($item);
    $response->getBody()->write($payload);
    return $response ->withHeader('Content-Type', 'application/json');
});



$app->run();


/*
 corrigir verbos da url, login,git, manipular duas entidades*/


// Run app