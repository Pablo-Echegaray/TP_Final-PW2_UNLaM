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