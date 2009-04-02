<?php
if (!defined('Context') || empty($CFG) || empty($q)
    || (Context != 'ponente' && Context != 'admin')) {

    header('Location: ' . get_url());
}

if (Action == 'newproposal') {
    //    
}

else { // want to update the page
    preg_match('#^speaker/proposals/(\d+)/update$#', $q, $matches);
    $proposal_id = (!empty($matches)) ? (int) $matches[1] : 0;

    $proposal = get_proposal($proposal_id, $USER->id);
}

if (empty($proposal)) {
    //initialize proposal
    $proposal = new StdClass;
}

require($CFG->comdir . 'prop_optional_params.php');

if (Action == 'newproposal') {
?>

<h1><?=__('Nueva propuesta') ?></h1>

<?php
// check if register is open
require($CFG->comdir . 'register_flag_check.php');
?>

<?php } else { ?>

<h1><?=__('Modificar ponencia') ?></h1>

<?php
}


// process submit
if (!empty($submit)) {
    // messages holder
    $errmsg = array();

    require($CFG->comdir . 'prop_optional_params_check.php');

    if (!empty($errmsg)) {
        show_error($errmsg);
    } else {
        // insert or update propuesta
        require($CFG->comdir . 'prop_update_info.php');

        do_submit_cancel('', __('Continuar'), $return_url);
    }
}

if (empty($submit) || !empty($errmsg)) { // show form
?> 

<form method="POST" action="">

    <p class="center"><em><?=__('Los campos marcados con asterisco(*) son obligatorios') ?></em></p>

<?php
    include($CFG->comdir . 'prop_input_table.php');

    if (Action == 'newproposal') {
        do_submit_cancel(__('Registrar'), __('Cancelar'), $return_url);
    } else {
        do_submit_cancel(__('Actualizar'), __('Cancelar'), $return_url);
        do_input('proposal_id', 'hidden', $proposal_id);
    }
?>

</form>

<?php
}
?>
