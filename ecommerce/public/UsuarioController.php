<?php
    use \Firebase\JWT\JWT;
   /* use Slim\Psr7\Response;*/
    use Psr\Http\Message\ResponseInterface as Response;
    use Psr\Http\Message\ServerRequestInterface as Request;

    include_once 'Usuario.php';
    include_once 'UsuarioDAO.php';

    class UsuarioController{
        private $secretKey = "PPI2";

        public function inserir(Request $request, Response $response, $args)
        {
            $var = $request->getParsedBody();
            $usuario = new Usuario(0, $var['nome'], $var['login'], $var['senha']);
            $dao = new UsuarioDAO;    
            $dao->inserir($usuario);
            $payload = json_encode($usuario);
            $response->getBody()->write($payload);
            return $response-> withHeader ( 'Content-Type' , 'application/json' );
            /*return $response->withJson($usuario,201);*/
        }

        public function autenticar(Request $request, Response $response, $args)
        {
            $user = $request->getParsedBody();
            
            $dao= new UsuarioDAO;    
            $usuario = $dao->buscarPorLogin($user['login']);
            if($usuario->senha == $user['senha'])
            {
                $token = array(
                    'user' => strval($usuario->id),
                    'nome' => $usuario->nome
                );
                $jwt = JWT::encode($token, $this->secretKey);
                /*return $response->withJson(["token" => $jwt], 201)->withHeader('Content-type', 'application/json');*/   
                $payload = json_encode(["token" => $jwt]);
                $response->getBody()->write($payload);
                return $response-> withHeader ( 'Content-Type' , 'application/json' );
            }
            else
                return $response->withStatus(401);
        }

        public function validarToken($request, $handler)
        {
            $response = new Slim\Psr7\Response();
            $token = $request->getHeader('Authorization');
            
            if($token && $token[0])
            {
                try {
                    $decoded = JWT::decode($token[0], $this->secretKey, array('HS256'));

                    if($decoded){
                        $response = $handler->handle($request);
                        return($response);
                    }
                } catch(Exception $error) {

                    return $response->withStatus(401);
                }
            }
            
            return $response->withStatus(401);
        }
    }

?>