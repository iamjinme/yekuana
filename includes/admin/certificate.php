<?php
// dummy check
if (empty($CFG) || Context != 'admin') {
    die;
}

include($CFG->rootdir.'includes/fpdf/fpdf.php');

if (Action == 'generate') {
    preg_match('#^admin/certificate/(\d+)$#', $q, $matches);
    $user_id = (!empty($matches)) ? (int) $matches[1] : 0;
    $user = get_person($user_id);
    $cert_name = $user->apellidos.'_'.$user->nombrep.'.pdf';
    
    $cad = ucwords(strtolower($user->apellidos.' '.$user->nombrep));
    $pdf = new FPDF('L', 'mm', 'Letter');
    $pdf->AddPage();
    $pdf->SetAutoPageBreak(True, 1);
    $pdf->SetFont('Arial', 'B', 25);
    $pdf->Image($CFG->rootdir.'images/certificadoasistente.jpg', 0, 20, 280, 216);
    $pdf->Text(93, 42, $cad);
    $pdf->SetFont('Arial', 'B', 20);
    $pdf->Text(240, 30, $user->cedula);
    $pdf->Output($cert_name, 'I');

} else {
?>

<div class="block"></div>
<p class="center"><?=__('El usuario no existe.') ?></p>
<p class="center"><?=$optional_message ?></p>
<?php
}
?>