<?php
// dummy check
if (empty($q) || empty($CFG)) {
    die;
}

if (Context == 'ponente') {
    preg_match('#^speaker/proposals/(\d+)/files/(\d+)/?.*$#', $q, $matches);
    $proposal_id = (!empty($matches)) ? (int) $matches[1] : 0;
    $file_id = (!empty($matches)) ? (int) $matches[2] : 0;
    $return_url = get_url('speaker/proposals');
}

elseif (Context == 'admin') {
    preg_match('#^admin/proposals/(\d+)/files/(\d+)/?.*$#', $q, $matches);
    $proposal_id = (!empty($matches)) ? (int) $matches[1] : 0;
    $file_id = (!empty($matches)) ? (int) $matches[2] : 0;
    $return_url = get_url('admin/proposals');
}

elseif (Context == 'asistente') {
    preg_match('#^person/proposals/(\d+)/files/(\d+)/?.*$#', $q, $matches);
    $proposal_id = (!empty($matches)) ? (int) $matches[1] : 0;
    $file_id = (!empty($matches)) ? (int) $matches[2] : 0;
    $return_url = get_url('person/events');
}

elseif (Context == 'main') {
    preg_match('#^general/proposals/(\d+)/files/(\d+)/?.*$#', $q, $matches);
    $proposal_id = (!empty($matches)) ? (int) $matches[1] : 0;
    $file_id = (!empty($matches)) ? (int) $matches[2] : 0;
    $return_url = get_url('general/proposals');
}

if (Context == 'main' || Context == 'asistente') {
    $file = get_record('prop_files', 'id', $file_id, 'id_propuesta', $proposal_id, 'public', '1');
}

elseif (Context == 'ponente') {
    //check if it's private file and is owner, or public file
    $file = get_record('prop_files', 'id', $file_id, 'id_propuesta', $proposal_id);

    if (!$file->public) {
        //not public, check ownership
        $rs = get_record('propuesta', 'id', $proposal_id, 'id_ponente', $USER->id);

        //not owner clear file
        if (empty($rs)) {
            unset($file);
        }
    }
}

else {
    $file = get_record('prop_files', 'id', $file_id, 'id_propuesta', $proposal_id);
}

if (empty($file)) {
    $title = __('Archivo no encontrado');
    do_header($title);
?>

<h1><?=$title ?></h1>

<p class="error center"><?=__('El archivo que buscas no esta disponible.') ?></p>

<div class="block"></div>

<?php
    do_submit_cancel('', __('Regresar'), $return_url);

} else {

    //file record exists

    //cache setings
    if ($file->public) {
        header('Pragma: Public');
        header('Cache-Control: Public');
    } else {
        header('Cache-Control: Private');
    }

    require_once($CFG->incdir . 'filelib.php');
    $mimetype = mimeinfo('type', $file->name);

    if ($mimetype == 'application/octet-stream') {
        header('Content-Disposition: attachment; filename='.$file->name);
    } else {
        header('Content-Disposition: inline; filename='.$file->name);
    }

    //disable mod_deflate/gzip for already compressed files
    if (preg_match('#^(application.*zip|image/(png|jpeg|gif))$#', $mimetype)) {
        if (function_exists('apache_setenv')) {
            @apache_setenv('no-gzip', '1');
        }
    }

    //full path to file
    $filepath = $CFG->files . 'proposals/' . $proposal_id . '/' . $file->name;

    //read the file
    spitfile_with_mtime_check($filepath, $mimetype);

    //clean exit
    exit;
}
