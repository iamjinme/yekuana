<?php
// dummy way to check if this file is loaded by the system
if (empty($CFG)) {
    die;
}

$table_data = array();

//comment body
$input_data = <<< END
<textarea name="S_c_body" cols="50" rows="2">{$c->body}</textarea>
END;

$table_data[] = array(
    __('Comentario:') . ' *',
    $input_data
    );

// show tdata
do_table_input($table_data, 'narrow-form');
?>
