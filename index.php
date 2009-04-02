<?php
require_once('includes/lib.php');

/*
 * Main routing views
 *
 */

$q = optional_param('q');

if (empty($q) || $q == '/') {
    // default index
    do_header();
    include($CFG->tpldir . 'main_index.tmpl.php');
}

/*
 * Register
 *
 */
// admin login
/*if (preg_match('#^admin/register$#', $q)) {

    define('Context', 'admin');
    include($CFG->comdir . 'user_edit.php');
}*/

// author register
elseif (preg_match('#^speaker/register$#', $q)) {

    define('Context', 'ponente');
    define('Action', 'register');

    // return url
    $return_url = get_url();

    do_header(__('Registro de Ponentes'));

    if (empty($CFG->auth)) {
        include($CFG->comdir . 'user_edit.php');
    } else {
        include($CFG->comdir . 'user_register_external.php');
    }
}

// person register
elseif (preg_match('#^person/register$#', $q)) {

    define('Context', 'asistente');
    define('Action', 'register');

    // return url
    $return_url = get_url();

    do_header(__('Registro de Asistentes'));

    if (empty($CFG->auth)) {
        include($CFG->comdir . 'user_edit.php');
    } else {
        include($CFG->comdir . 'user_register_external.php');
    }
}

/*
 * Recover user password or login
 *
 */

// 
/*elseif (preg_match('#^admin/recover$#', $q)) {

    define('Context', 'admin');
    define('Action', 'recover')
    include($CFG->comdir . 'do_login.php');
}*/

/*
 * Login 
 *
 */

// admin login
elseif (preg_match('#^admin/login$#', $q)) {

    define('Context', 'admin');
    include($CFG->comdir . 'do_login.php');
}

// author login
elseif (preg_match('#^speaker/login$#', $q)) {

    define('Context', 'ponente');
    include($CFG->comdir . 'do_login.php');
}

// person login
elseif (preg_match('#^person/login$#', $q)) {

    define('Context', 'asistente');
    include($CFG->comdir . 'do_login.php');
}

/*
 * logout
 *
 */

// admin login
elseif (preg_match('#^admin/logout$#', $q)) {

    define('Context', 'admin');
    include($CFG->comdir . 'do_logout.php');
}

// author login
elseif (preg_match('#^speaker/logout$#', $q)) {

    define('Context', 'ponente');
    include($CFG->comdir . 'do_logout.php');
}

// person login
elseif (preg_match('#^person/logout$#', $q)) {

    define('Context', 'asistente');
    include($CFG->comdir . 'do_logout.php');
}

// force session destroy
elseif (preg_match('#^logout$#', $q)) {
    //ignore errors
    @session_start();
    @session_unset();
    @session_destroy();

    $title = __('Sesión Terminada');

    do_header($title);
?>

<h1><?=$title ?></h1>
<div class="block"></div>

<p class="error center"><?=__('Tu sesión ha caducado o salido forzosamente.') ?></p>

<?php
    do_submit_cancel('', __('Continuar'), get_url());
}

/*
 * Not logged in views
 *
 */

// general schedule
elseif (!empty($CFG->public_schedule) && schedule_has_events() && preg_match('#^general/schedule/?#', $q)) {
    define('Context', 'main');
    define('Action', 'viewschedule');

    do_header(__('Programa preliminar'));
    include($CFG->admdir . 'schedule_view.php');
    do_submit_cancel('', __('Regresar'), get_url());
}

// list proposals
elseif (!empty($CFG->public_proposals) && preg_match('#^general/proposals/?$#', $q)) {
    define('Context', 'main');
    define('Action', 'listproposals');
    $return_url = get_url();

    $title = __('Lista de propuestas enviadas');

    do_header($title);
?>

<h1><?=$title ?></h1>

<?php
    include($CFG->comdir . 'prop_list.php');
    do_submit_cancel('', __('Regresar'), $return_url);
}

// view some proposal
elseif (!empty($CFG->public_proposals) && preg_match('#^general/proposals/\d+$#', $q)) {
    define('Context', 'main');
    define('Action', 'viewproposal');
    $return_url = get_url('general/proposals');

    do_header(__('Detalles de propuesta'));
    include($CFG->comdir . 'prop_view.php');
}

// file download
elseif (!empty($CFG->public_proposals) && preg_match('#^general/proposals/\d+/files/\d+/?.*$#', $q)) {
    define('Context', 'main');
    define('Action', 'downloadfile');
    include($CFG->comdir . 'prop_files_download.php');
}

// view info of kind of proposals
elseif (preg_match('#^general/information$#', $q)) {

    do_header(__('Modalidades de participación'));
    include($CFG->tpldir . 'proposals_info.tmpl.php');
    do_submit_cancel('', __('Regresar'), get_url());
}

/*
 * schedule views
 *
 */

//TODO

/*
 * admin views
 *
 */
elseif (preg_match('#^admin/?.*#', $q)) {

    // Delegate routing
    include($CFG->incdir . 'admin_views.php');
}
 
/*
 *  author views
 *
 */
elseif (preg_match('#^speaker/?.*#', $q)) {

    // Delegate routing
    include($CFG->incdir . 'speaker_views.php');
}
 
/*
 *  person views
 *
 */
elseif (preg_match('#^person/?.*#', $q)) {

    // Delegate routing
    include($CFG->incdir . 'person_views.php');
}

/*
 * Default index
 *
 */
else {
    do_header(__('Página no encontrada'));
    include($CFG->tpldir . 'error_404.tmpl.php');
    do_submit_cancel('', __('Regresar'));
}

// finally
do_footer();
?>
