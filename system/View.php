<?php

class View
{   
    private $controlador;
    private $twig;
    private $error;
    private $mensaje;
    private $titulo;
    private $encabezado;
    private $session;
    
    
    public function __construct(Request $peticion) {
        
        $this->controlador = strtolower($peticion->getControlador());
        Twig_AutoLoader::register();
        $loader = new Twig_Loader_Filesystem(VIE_PATH);
        $this->twig = new Twig_Environment($loader, array('debug' => true));
        $this->twig->addExtension(new Twig_Extension_Debug());
        $this->session=new Session();
    }
    
    public function renderizar($vista, $args = array(), $item = false)
    {
        $args['session'] = $this->session;
        $args['mensaje'] = $this->mensaje;
        $args['error'] = $this->error;
        $args['encabezado'] = $this->encabezado;
        $args['titulo'] = $this->titulo;
        $args['host'] = BASE_URL;
        
        $rutaView = $this->controlador . DS . $vista . '.html.twig';
        echo $this->twig->render($rutaView, $args);
    }
    public function getError() {
        return $this->error;
    }

    public function getMensaje() {
        return $this->mensaje;
    }

    public function getTitulo() {
        return $this->titulo;
    }

    public function setError($error) {
        $this->error = $error;
    }

    public function setMensaje($mensaje) {
        $this->mensaje = $mensaje;
    }

    public function setTitulo($titulo) {
        $this->titulo = $titulo;
    }

    public function setEncabezado($encabezado) {
        $this->encabezado = $encabezado;
    }

    public function getControlador() {
        return $this->controlador;
    }

    public function setControlador($controlador) {
        $this->controlador = $controlador;
    }



}

?>