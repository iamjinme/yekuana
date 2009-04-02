<?php
// dummy way to check if this file is loaded by the system
if (empty($CFG)) {
    die;
}

// build data table
$table_data = array();

//file title
$input_data = do_get_output('do_input', array('S_title', 'text', $file->title, 'size="20" maxlength="50"'));

$table_data[] = array(
    __('Título:') . ' *',
    $input_data
    );

//description
$input_data = <<< END
<textarea name="S_descr" cols="50" rows="2">{$file->descr}</textarea>
END;

$table_data[] = array(
    __('Descripción:') . ' *',
    $input_data
    );

// public?
$input_data = do_get_output('do_input_yes_no', array('I_public', $file->public));

$table_data[] = array(
    __('Público:') . ' *',
    $input_data
    );

// file!
if (Action == 'editfile') {
    $input_data = $file->name;
} else {
    $input_data = do_get_output('do_input', array('S_filename', 'file', '', 'id=\'S_filename\''));

    $input_data .= '<br /><span class="littleinfo">' . __('El tamaño máximo de subida es de <strong>2 Mb</strong>, si tu archivo supera el límite<br /> por favor envíalo a') . ' <a href="mailto:' . $CFG->adminmail . '">' . $CFG->adminmail . '</a>';
}

$table_data[] = array(
    __('Archivo:') . ' *',
    $input_data
    );

// show tdata
do_table_input($table_data, 'narrow-form');
?>
