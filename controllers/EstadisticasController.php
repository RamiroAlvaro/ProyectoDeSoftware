<?php

include_once 'models/estadisticas.php';
include_once 'models/detalle.php';
include_once 'vendors/fpdf/tablasPDF.php';
include_once 'vendors/fpdf/fpdf.php';

class EstadisticasController extends Controller {

    public function index() {
        $this->redireccionar('index');
    }

    public function ver() {
        Session::tienePermiso('estadistica');
        $this->view->setEncabezado("Listados y Estadísticas");
        $this->view->setTitulo("Listados y Estadísticas");
        $this->view->renderizar("formulario");
    }

    public function graficarBarras() {
        Session::tienePermiso('estadistica');
        if (isset($_GET['desde']) && (isset($_GET['hasta']))) {
            //$fechaInicio = date("Y-d-m", strtotime($_GET['desde'])); 
            $fechaInicio = DateTime::createFromFormat('d/m/Y', $_GET['desde'])->format('Y-m-d');
            //$fechaFin = date("Y-d-m", strtotime($_GET['hasta']));
            $fechaFin = DateTime::createFromFormat('d/m/Y', $_GET['hasta'])->format('Y-m-d');
            $estadisticas = Estadisticas::nuevo();
            $datos = $estadisticas->pesoDeTodosLosAlimentosEntre($fechaInicio, $fechaFin);
            $result = Array();
            foreach ($datos as $dato) {
                $result['fecha'][] = $dato['fecha'];
                $result['kgs'][] = (integer) $dato['kgs'];
            }
            print json_encode($result);
        }
    }

    public function graficarTorta() {
        Session::tienePermiso('estadistica');
        if (isset($_GET['desde2']) && (isset($_GET['hasta2']))) {
            //$fechaInicio = date("Y-d-m", strtotime($_GET['desde2'])); 
            //$fechaFin = date("Y-d-m", strtotime($_GET['hasta2']));
            $fechaInicio = DateTime::createFromFormat('d/m/Y', $_GET['desde2'])->format('Y-m-d');
            $fechaFin = DateTime::createFromFormat('d/m/Y', $_GET['hasta2'])->format('Y-m-d');
            $estadisticas = Estadisticas::nuevo();
            $datos = $estadisticas->pesoPorEntidadEntre($fechaInicio, $fechaFin);
            $result = Array();
            $fila = Array();
            foreach ($datos as $dato) {
                $fila[0] = $dato['entidad'];
                $fila[1] = (integer) $dato['kgs'];
                array_push($result, $fila);
            }
            print json_encode($result);
        }
    }

    public function listarTotalAlimentos() {
        Session::tienePermiso('estadistica');
        if (isset($_GET['desde']) && (isset($_GET['hasta']))) {
            $fechaInicio = DateTime::createFromFormat('d/m/Y', $_GET['desde'])->format('Y-m-d');
            $fechaFin = DateTime::createFromFormat('d/m/Y', $_GET['hasta'])->format('Y-m-d');
            $estadisticas = Estadisticas::nuevo();
            $datos = $estadisticas->pesoDeTodosLosAlimentosEntre($fechaInicio, $fechaFin);
            print json_encode($datos);
        }
    }

    public function listarAlimentosPorEntidad() {
        Session::tienePermiso('estadistica');
        if (isset($_GET['desde2']) && (isset($_GET['hasta2']))) {
            $fechaInicio = DateTime::createFromFormat('d/m/Y', $_GET['desde2'])->format('Y-m-d');
            $fechaFin = DateTime::createFromFormat('d/m/Y', $_GET['hasta2'])->format('Y-m-d');
            $estadisticas = Estadisticas::nuevo();
            $datos = $estadisticas->pesoPorEntidadEntre($fechaInicio, $fechaFin);
            print json_encode($datos);
        }
    }

    public function alimentosVencidosSinEntregar() {
        $detalle = new Detalle();
        $alimentosVencidos = $detalle->obtenerVencidos();
        print json_encode(array("data" => $alimentosVencidos));
    }

    public function generarPDFtotalAlimentos() {
        Session::tienePermiso('estadistica');
        if (isset($_POST['fechaInicio']) && (isset($_POST['fechaFin']))) {
            $fechaInicio = DateTime::createFromFormat('d/m/Y', $_POST['fechaInicio'])->format('Y-m-d');
            $fechaFin = DateTime::createFromFormat('d/m/Y', $_POST['fechaFin'])->format('Y-m-d');
            $pdf = new PDF();
            // Titulos de las columnas
            $header = array('Fecha', 'Kgs entregados');
            $estadisticas = Estadisticas::nuevo();
            $datos = $estadisticas->pesoDeTodosLosAlimentosEntre($fechaInicio, $fechaFin);
            $pdf->setTitulo('Kgs totales de pedidos entregados');
            $pdf->setFont('Arial', '', 14);
            $pdf->AddPage();
            $pdf->BasicTable($header, $datos);
            $pdf->Output();
        }
    }

    public function generarPDFporEntidad() {
        Session::tienePermiso('estadistica');
        if (isset($_POST['fechaInicio2']) && (isset($_POST['fechaFin2']))) {
            $fechaInicio = DateTime::createFromFormat('d/m/Y', $_POST['fechaInicio2'])->format('Y-m-d');
            $fechaFin = DateTime::createFromFormat('d/m/Y', $_POST['fechaFin2'])->format('Y-m-d');
            $pdf = new PDF();
            // Titulos de las columnas
            $header = array('Entidad', 'Kgs entregados');
            $estadisticas = Estadisticas::nuevo();
            $datos = $estadisticas->pesoPorEntidadEntre($fechaInicio, $fechaFin);
            $pdf->setTitulo('Kgs entregados a cada Entidad Receptora');
            $pdf->setFont('Arial', '', 14);
            $pdf->AddPage();
            $pdf->BasicTable($header, $datos);
            $pdf->Output();
        }
    }

}
