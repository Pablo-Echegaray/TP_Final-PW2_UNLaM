<?php
require_once 'dompdf/autoload.inc.php';
use Dompdf\Dompdf;
use Dompdf\Options;

class PdfCreator{
    public function create ($html){
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        
        $dompdf->render();
        $dompdf->stream('reporte_graficos.pdf', ['Attachment' => 0]);
    }
}
/*
$options = new Options();
$options->set('isHtml5ParserEnabled', true);  // Habilitar el analizador HTML5
$options->set('isPhpEnabled', true);          // Habilitar PHP dentro de Dompdf
$options->set('isRemoteEnabled', true);       // Permitir la carga de imágenes y CSS externos
$dompdf = new Dompdf($options);


$dompdf->set_option('isHtml5ParserEnabled', true); // Habilitar el analizador HTML5
$dompdf->set_option('isPhpEnabled', true); // Habilitar PHP dentro de Dompdf
$dompdf->set_option('isRemoteEnabled', true); // Permitir la carga de imágenes y CSS externos */