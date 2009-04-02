<?php
//
// function to show user input info
// included on register or editing info of asistente
//

// dummy way to check if this file is loaded by the system
if (empty($CFG)) {
    die;
}

// build data table
$table_data = array();

// only for admin
if (Context == 'admin') {
    // prop owner login
    $input_data = do_get_output('do_input', array('S_login', 'text', $login, 'size="15"'));

    $table_data[] = array(
        __('Usuario ponente:') . ' *',
        $input_data,
        );
}

// prop name
$input_data = do_get_output('do_input', array('S_nombreponencia', 'text', $proposal->nombre, 'size="50" maxlength="150"'));

$table_data[] = array(
    __('Nombre de Ponencia:') . ' *',
    $input_data,
    );

// orientacion
$options = get_records('orientacion');

$input_data = do_get_output('do_input_select', array('I_id_orientacion', $options, $proposal->id_orientacion));

$table_data[] = array(
    __('Orientación:') . ' *',
    $input_data,
    );

// nivel
$options = get_records('prop_nivel');

$input_data = do_get_output('do_input_select', array('I_id_nivel', $options, $proposal->id_nivel));

$table_data[] = array(
    __('Nivel:') . ' *',
    $input_data,
    );

// tipo propuesta
if (Context == 'admin') {
    $options = get_records('prop_tipo');
} else {
    $options = get_records_select('prop_tipo', 'id < 100');
}

$input_data = do_get_output('do_input_select', array('I_id_tipo', $options, $proposal->id_prop_tipo));

$table_data[] = array(
    __('Tipo de Propuesta:') . ' *',
    $input_data,
    );

// duracion
$input_data = do_get_output('do_input_number_select', array('I_duracion', 1, 4, $proposal->duracion, true, '', 0, true));

$table_data[] = array(
    __('Duración:') . ' *',
    $input_data,
    );

// resumen 
$input_data = <<< END
<textarea name="S_resumen" cols="60" rows="15">{$proposal->resumen}</textarea>
END;

$table_data[] = array(
    __('Resumen:') . ' *',
    $input_data,
    );

// requisitos tecnicos
$input_name = '' . 
__('Requisitos técnicos de la ponencia:') .
'&nbsp;<br /><small>' .
__('(Estos son los requisitos necesarios para impartir la ponencia)') . '</small>';

$input_data = <<< END
<textarea name="S_reqtecnicos" cols="60" rows="5">{$proposal->reqtecnicos}</textarea>
END;

$table_data[] = array(
    $input_name,
    $input_data,
    );

// requisitos para el asistente
$input_data = <<< END
<textarea name="S_reqasistente" cols="60" rows="5">{$proposal->reqasistente}</textarea>
END;

$table_data[] = array(
    __('Prerequisitos para el asistente:') . ' &nbsp;',
    $input_data,
    );

//TODO: make frontend to attach files to proposals
// archivo presentacion o paper
/*$input_data = do_get_output('do_input', array('fichero', 'file', '', 'size="40" id="fichero"'));

$table_data[] = array(
    'Enviar Archivo: &nbsp;',
    $input_data
    );
 */

// show tdata
do_table_input($table_data);
?>
