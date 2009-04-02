<?php
if (!defined('Context') || Context != 'admin' || Action != 'newevent') {
    header('Location: ' . get_url('admin'));
}

// setup a proposal as event
$proposal = new StdClass;

// setup event
$event = new StdClass;

require($CFG->admdir . 'event_optional_params.php');
?>

<h1><?=__('Registro de Evento') ?></h1>

<?php
// process submit
if (!empty($submit) && !empty($event) && !empty($proposal)) {
    // messages holder
    $errmsg = array();

    require($CFG->admdir . 'event_optional_params_check.php');

    if (!empty($errmsg)) {
        show_error($errmsg);
    } else {
        // insert or update propuesta
        require($CFG->admdir . 'event_update_info.php');

?>

<p class="error center"><?=__('Evento organización agregado.') ?></p>

<?php
        // refresh proposal
        $proposal = get_proposal($proposal->id);
        $prop_noshow_resume = true;

        // show proposal updated details
        include($CFG->comdir . 'prop_display_info.php');

        do_submit_cancel('', __('Continuar'), $return_url);
    }
} 

if (empty($submit) || !empty($errmsg)) {
?> 

<form method="POST" action="">

    <p class="center"><em><?=__('Los campos marcados con asterisco(*) son obligatorios') ?></em></p>

<?php
    // show input table
    include($CFG->admdir . 'event_input_table.php');

    do_submit_cancel(__('Registrar'), __('Cancelar'), $return_url);
?>

</form>

<?php
}
?>
