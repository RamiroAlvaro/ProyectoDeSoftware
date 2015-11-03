<?php

class Bootstrap
{
    public static function run(Request $peticion)
    {
        $controlador = ucwords($peticion->getControlador() . 'Controller');
        $rutaControlador = ROOT . 'controllers' . DS . $controlador . '.php';
        $metodo = ucfirst($peticion->getMetodo());
        $args = $peticion->getArgs();
        
        if(is_readable($rutaControlador)){
            require_once $rutaControlador;
            $controller = new $controlador;
            
            if(is_callable(array($controller, $metodo))){
                $metodo = $peticion->getMetodo();
            }
            else{
                $metodo = 'index';
            }
            
            if(sizeof($args) > 0){
                call_user_func_array(array($controller, $metodo), $args);
            }
            else{
                call_user_func(array($controller, $metodo));
            }
            
        } else {
           header('Location: /?uri=error/access/404');
        }
    }
}

?>