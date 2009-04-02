<?php

$values = array();

// public schedule
$desc = __('Mostrar el programa de eventos publicamente');
$config = get_config('public_schedule');
$input = do_get_output('do_input_yes_no', array('public_schedule', $config->value));

$values[$desc] = $input;

// public proposals
$desc = __('Mostrar las propuestas publicamente');
$config = get_config('public_proposals');
$input = do_get_output('do_input_yes_no', array('public_proposals', $config->value));

$values[$desc] = $input;

// multiple users per mail
$desc = __('Un usuario por email');
$config = get_config('unique_mail');
$input = do_get_output('do_input_yes_no', array('unique_mail', $config->value));

$values[$desc] = $input;

// max limit to participants in workshops/tutorials
$desc = __('Número máximo de asistentes a talleres/tutoriales');
$config = get_config('limite');
$input = do_get_output('do_input', array('limite', 'text', $config->value, 'size="3"'));

$values[$desc] = $input;

// start time of the event
$desc = __('Hora de inicio');
$config = get_config('def_hora_ini');
$input = do_get_output('do_input_number_select', array('def_hora_ini', 0, 23, $config->value, false));

$values[$desc] = $input;

// end time of the event
$desc = __('Hora de fin');
$config = get_config('def_hora_fin');
$input = do_get_output('do_input_number_select', array('def_hora_fin', 0, 23, $config->value, false));

$values[$desc] = $input;


// notify by email flag
$desc = __('Enviar mensajes por email');
$config = get_config('send_mail');
$input = do_get_output('do_input_yes_no', array('send_mail', $config->value));

$values[$desc] = $input;


// smtp server host
$desc = __('Servidor SMTP');
$config = get_config('smtp');
$input = do_get_output('do_input', array('smtp', 'text', $config->value, 'size="30"'));

$values[$desc] = $input;

// clean url input select
$desc = __('Usar URLs limpios');
$config = get_config('clean_url');

// clean url test
$clean_url_test_url = str_replace('?q=', '', get_url('admin/config'));
$clean_url_test = sprintf('<br/><small><a title="%s" href="'.$clean_url_test_url.'">%s</small>', __('Necesitas soporte para mod_rewrite'), __('Prueba'));

$desc .= $clean_url_test;

//can use clean urls?
if (preg_match('#\?q=#', $_SERVER['REQUEST_URI'])) {
    $disabled = 'disabled="disabled"';
} else {
    $disabled = '';
}

$input = do_get_output('do_input_yes_no', array('clean_url', $config->value, __('Si'), __('No'), $disabled));

$values[$desc] = $input;

// external authentification
$desc = __('Autentificación');
$config = get_config('auth');

$options = array();
$auths = user_auth_available();

foreach ($auths as $auth) {
    $option = new StdClass;
    $option->id = $auth;
    $option->descr = $auth;

    $options[] = $option;
}

$input = do_get_output('do_input_select', array('auth', $options, $config->value, true, __('Interna'), '', 'style=\'width:100px;\''));

$values[$desc] = $input;

// language selection
$desc = __('Lenguaje por defecto');
$config = get_config('locale');

$options = array();
$langs = languages_available();

foreach ($langs as $lang) {
    $option = new StdClass;
    $option->id = $lang;
    $option->descr = $lang;

    $options[] = $option;
}

$input = do_get_output('do_input_select', array('locale', $options, $config->value, true, 'es_BO', '', 'style=\'width:50px;\''));

$values[$desc] = $input;

//show table input
do_table_values($values, 'narrow');

?>
