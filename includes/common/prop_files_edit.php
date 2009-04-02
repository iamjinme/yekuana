<?php
// dummy check
if (empty($q) || empty($CFG)) {
    die;
}

if (Context == 'ponente') {
    preg_match('#^speaker/proposals/(\d+)/files/edit/(\d+)/?.*$#', $q, $matches);
    $proposal_id = (!empty($matches)) ? (int) $matches[1] : 0;
    $file_id = (!empty($matches)) ? (int) $matches[2] : 0;

    //Check proposal owner
    $proposal = get_proposal($proposal_id, $USER->id);

    if (!empty($proposal)) {
        //get file
        $file = get_record('prop_files', 'id', $file_id, 'id_propuesta', $proposal->id);
        $return_url = get_url('speaker/proposals/' . $proposal->id . '/files');
    } else {
        $return_url = get_url('speaker/proposals');
    }

}

$submit = optional_param('submit');
//can't delete files from accepted/canceled/deleted/programmed proposals
if (!empty($proposal) && !empty($file))  {
?>

<h1><?=__('Editar archivo') ?></h1>

<?php
    if (empty($submit)) {
        // confirm delete
?>

<form method="POST" action="";

<?php
        include($CFG->comdir . 'prop_files_input.php');
        do_submit_cancel(__('Guardar'), __('Cancelar'), $return_url);
?>

</form>

<?php
    } else {
       require($CFG->comdir . 'prop_files_optional_params.php');

       // update record
       $f = new StdClass;
       $f->id = $file->id;
       $f->title = $title;
       $f->descr = $descr;
       $f->public = $public;

       if (!$rs = update_record('prop_files', $f)) {
           $errmsg[] = __('Ocurrió un error al actualizar el archivo.');
       }

       if (empty($errmsg)) {
?> 

<p class="center"><?=__('La información del archivo fue modificada con éxito.') ?></p>

<div class="block"></div>

<?php 
       } else {
           show_error($errmsg);
       }

       do_submit_cancel('', __('Continuar'), $return_url);
    }

} else {
?>

<h1><?=__('Propuesta y/o archivo no encontrado') ?></h1>

<div class="block"></div>
<p class="center"><?=__('Registros de propuesta no encontrados. Posiblemente no existan o no tengas acceso para eliminar los archivos de la propuesta.') ?></p>

<?php
    do_submit_cancel('', __('Regresar'), $return_url);
}
?>
