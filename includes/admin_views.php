<?php
// called directly?
if (empty($CFG)) {
    header('Location: ..');
}

// Globals
define('Context', 'admin');
$return_url = get_url('admin');

// init session
require($CFG->comdir . 'session.php');

$q= optional_param('q');

// default index
if (preg_match('#^admin/?$#', $q)) {
    // default index
    do_header(__('Menu Administración'));
    include($CFG->tpldir . 'admin_menu.tmpl.php');
}

/*
 * Routing
 *
 */

// add speaker
elseif (level_admin(2) && preg_match('#^admin/speakers/new$#', $q)) {
    define('Action', 'newspeaker');

    do_header(__('Agregar ponente'));
    include($CFG->comdir . 'user_edit.php');
}

// list speakers
elseif (preg_match('#^admin/speakers/?$#', $q)) {
    define('Action', 'listspeakers');

    do_header(__('Listado de ponentes'));
    include($CFG->comdir . 'user_list.php');
    do_submit_cancel('', __('Regresar'), $return_url);
}

// list persons
elseif (preg_match('#^admin/persons/?$#', $q)) {
    define('Action', 'listpersons');

    do_header(__('Listado de asistentes'));
    include($CFG->comdir . 'user_list.php');
    do_submit_cancel('', __('Regresar'), $return_url);
}

// view person details
elseif (preg_match('#^admin/persons/\d+/?$#', $q)) {
    define('Action', 'viewperson');

    do_header(__('Detalles de asistente'));
    include($CFG->comdir . 'user_view.php');
    do_submit_cancel('', __('Regresar'));
}

// control persons
elseif (level_admin(2) && preg_match('#^admin/persons/control/?$#', $q)) {
    define('Action', 'controlpersons');

    do_header(__('Control de asistentes'));
    include($CFG->comdir . 'user_list.php');
    do_submit_cancel('', __('Regresar'), $return_url);
}

// control persons
elseif (level_admin(2) && preg_match('#^admin/persons/control/\d+$#', $q)) {
    define('Action', 'controlpersons');

    include($CFG->admdir . 'user_action.php');
}

// view speaker details
elseif (preg_match('#^admin/speakers/\d+/?$#', $q)) {
    define('Action', 'viewspeaker');

    do_header(__('Detalles de ponente'));
    include($CFG->comdir . 'user_view.php');
    do_submit_cancel('', __('Regresar'));
}

// add proposal
elseif (level_admin(2) && preg_match('#^admin/proposals/new$#', $q)) {
    define('Action', 'newproposal');

    do_header(__('Agregar ponencia'));
    include($CFG->comdir . 'prop_edit.php');
}

// list proposals
elseif (preg_match('#^admin/proposals/?$#', $q)) {
    define('Action', 'listproposals');

    $title = __('Listado de propuestas enviadas');
    do_header($title);
?>

<h1><?=$title ?></h1>

<?php
    include($CFG->comdir . 'prop_list.php');
    do_submit_cancel('', __('Regresar'), $return_url);
}

// view proposal
elseif (preg_match('#^admin/proposals/\d+/?$#', $q)) {
    define('Action', 'viewproposal');
    $return_url = get_url('admin/proposals');

    do_header(__('Detalles de ponencia'));
    include($CFG->comdir . 'prop_view.php');
}

// download proposals files
elseif (preg_match('#^admin/proposals/\d+/files/\d+/.+#', $q)) {
    define('Action', 'downloadfile');
    include($CFG->comdir . 'prop_files_download.php');
}

// update status of proposal
elseif (level_admin(3) && preg_match('#^admin/proposals/\d+/status/\d+/?$#', $q)) {
    define('Action', 'viewproposal');
    $return_url = get_url('admin/proposals');

    include($CFG->admdir . 'prop_action.php');
}

// list deleted proposals
elseif (level_admin(2) && preg_match('#^admin/proposals/deleted/?$#', $q)) {
    define('Action', 'listdeletedproposals');

    $title = __('Lista de ponencias eliminadas');

    do_header($title);
?>
        
<h1><?=$title ?></h1>

<?php
    include($CFG->comdir . 'prop_list.php');
    do_submit_cancel('', __('Regresar'), $return_url);
}

// view deleted proposal
elseif (level_admin(2) && preg_match('#^admin/proposals/deleted/\d+/?$#', $q)) {
    define('Action', 'viewdeletedproposal');
    $return_url = get_url('admin/proposals/deleted');

    do_header(__('Detalles de ponencia'));
    include($CFG->comdir . 'prop_view.php');
}

// change status of  deleted proposals
elseif (level_admin(2) && preg_match('#^admin/proposals/deleted/\d+/status/\d+/?$#', $q)) {
    define('Action', 'deletedproposal');
    include($CFG->admdir . 'prop_action.php');
}

// delete proposal
elseif (level_admin(2) && preg_match('#^admin/proposals/\d+/delete$#', $q)) {
    define('Action', 'deleteproposal');

    do_header(__('Eliminar ponencia'));
    include($CFG->comdir . 'prop_delete.php');
}

/*
 * Rooms Management
 * 
 */

// room add
elseif (level_admin(2) && preg_match('#^admin/rooms/new$#', $q)) {
    define('Action', 'newroom');

    do_header(__('Agregar lugar'));
    include($CFG->admdir . 'room_edit.php');
}

// rooms list
elseif (level_admin(2) && preg_match('#^admin/rooms/?$#', $q)) {
    define('Action', 'listrooms');

    do_header(__('Lista de lugares para eventos'));
    include($CFG->admdir . 'room_list.php');
    do_submit_cancel('', __('Regresar al Menu'), get_url('admin'));
}

// room edit
elseif (level_admin(2) && preg_match('#^admin/rooms/\d+/?$#', $q)) {
    define('Action', 'editroom');
    $return_url = get_url('admin/rooms');

    do_header(__('Editar lugar'));
    include($CFG->admdir . 'room_edit.php');
}

// room add
elseif (level_admin(2) && preg_match('#^admin/rooms/\d+/delete$#', $q)) {
    define('Action', 'deleteroom');
    $return_url = get_url('admin/rooms');

    do_header(__('Eliminar lugar'));
    include($CFG->admdir . 'room_delete.php');
}

// room events
elseif (preg_match('#^admin/rooms/\d+/events$#', $q)) {
    define('Action', 'eventsroom');
    $return_url = get_url('admin/rooms');

    $title = __('Listado de eventos por lugar');

    do_header($title);
?>

<h1><?=$title ?></h1>

<?php
    include($CFG->admdir . 'event_list.php');
    do_submit_cancel('', __('Regresar'), $return_url);
}

/*
 * Dates Management
 *
 */

// date add
elseif (level_admin(2) && preg_match('#^admin/dates/new$#', $q)) {
    define('Action', 'newdate');

    do_header(__('Agregar fecha'));
    include($CFG->admdir . 'date_edit.php');
}

// dates list
elseif (level_admin(2) && preg_match('#^admin/dates/?$#', $q)) {
    define('Action', 'listdates');

    do_header(__('Lista de fechas para eventos'));
    include($CFG->admdir . 'date_list.php');
    do_submit_cancel('', __('Regresar al Menu'), get_url('admin'));
}

// date edit
elseif (level_admin(2) && preg_match('#^admin/dates/\d+/?$#', $q)) {
    define('Action', 'editdate');
    $return_url = get_url('admin/dates');

    do_header(__('Editar fecha'));
    include($CFG->admdir . 'date_edit.php');
}

// date delete
elseif (level_admin(2) && preg_match('#^admin/dates/\d+/delete$#', $q)) {
    define('Action', 'deletedate');
    $return_url = get_url('admin/dates');

    do_header(__('Eliminar fecha'));
    include($CFG->admdir . 'date_delete.php');
}

// date events
elseif (level_admin(2) && preg_match('#^admin/dates/\d+/events$#', $q)) {
    define('Action', 'eventsdate');
    $return_url = get_url('admin/dates');

    $title = __('Listado de eventos por fecha');

    do_header($title);
?>
        
<h1><?=$title ?></h1>

<?php
    include($CFG->admdir . 'event_list.php');
    do_submit_cancel('', __('Regresar'), $return_url);
}

/*
 * Events
 *
 */

// add event
elseif (level_admin(2) && preg_match('#^admin/events/new/?#', $q)) {
    define('Action', 'newevent');
    $return_url = get_url('admin/events');
    do_header(__('Añadir evento'));
    include($CFG->admdir . 'event_new.php');
}

// list proposals to schedule
elseif (level_admin(2) && preg_match('#^admin/events/schedule/?$#', $q)) {
    define('Action', 'scheduleevent');
    $return_url = get_url('admin');
    $not_found_message = __('No se encontro ninguna ponencia habilitada o ya se encuentran programadas.');

    $title = __('Listado de ponencias listas para ser programadas');

    do_header($title);
?>

<h1><?=$title ?></h1>

<?php
    include($CFG->comdir . 'prop_list.php');
    do_submit_cancel('', __('Regresar'), $return_url);
}

// list attendees to workshops
elseif (level_admin(2) && preg_match('#^admin/proposals/\d+/persons$#', $q)) {
    define('Action', 'workshopattendees');
    $return_url = get_url('admin/events');

    do_header(__('Lista de asistentes'));
    include($CFG->comdir . 'user_list.php');
    do_submit_cancel('', __('Regresar', $return_url));
}

// add event
elseif (level_admin(2) && preg_match('#^admin/events/schedule/\d+?$#', $q)) {
    define('Action', 'scheduleevent');
    $return_url = get_url('admin/events/schedule');

    do_header(__('Listado de ponencias habilitadas'));
    include($CFG->admdir . 'event_edit.php');
}

// edit event
elseif (level_admin(2) && preg_match('#^admin/events/\d+/?$#', $q)) {
    define('Action', 'editevent');
    $return_url = get_url('admin/events');

    do_header(__('Reprogramar Evento'));
    include($CFG->admdir . 'event_edit.php');
}

// events list
elseif (preg_match('#^admin/events/?$#', $q)) {
    define('Action', 'listevents');

    $title = __('Listado de eventos programados');

    do_header($title);
?>
        
<h1><?=$title ?></h1>

<?php
    include($CFG->admdir . 'event_list.php');
    do_submit_cancel('', __('Regresar al Menu'), get_url('admin'));
}

// events cancel
elseif (level_admin(2) && preg_match('#^admin/events/\d+/cancel$#', $q)) {
    define('Action', 'cancelevent');

    if (!empty($_SESSION['return_path'])) {
        $return_url = $_SESSION['return_path'];
    } else {
        //default return url
        $return_url = get_url('admin/events');
    }

    do_header(__('Cancelar evento'));
    include($CFG->admdir . 'event_cancel.php');
}

/*
 * Administration
 *
 */

// config manager
elseif (level_admin(1) && preg_match('#^admin/config/?$#', $q)) {
    define('Action', 'config');

    do_header(__('Configuración del Sistema'));
    include($CFG->admdir . 'config_manager.php');
}

// config action
elseif (level_admin(1) && preg_match('#^admin/config/(open|close)/\d+$#', $q)) {
    define('Action', 'config');
    include($CFG->admdir . 'config_action.php');
}

// config manager
elseif (level_admin(1) && preg_match('#^admin/catalog/?$#', $q)) {
    define('Action', 'catalog');

    do_header(__('Administrar Catálogos del Sistema'));
    include($CFG->admdir . 'catalog_manager.php');
}

// add new admin user
elseif (level_admin(1) && preg_match('#^admin/new$#', $q)) {
    define('Action', 'newadmin');

    do_header(__('Nuevo administrador'));
    include($CFG->comdir . 'user_edit.php');
}

// list admin users
elseif (level_admin(1) && preg_match('#^admin/list$#', $q)) {
    define('Action', 'listadmins');

    do_header(__('Lista de administradores'));
    include($CFG->admdir . 'admin_list.php');
}

// admin action, change user tadmin
elseif (level_admin(1) && preg_match('#^admin/\d+/type/\d+$#', $q)) {
    define('Action', 'editadmin');
    include($CFG->admdir . 'admin_action.php');
}

/*
 * Delete Users
 *
 */

// admin delete
elseif (level_admin(1) && preg_match('#^admin/\d+/delete$#', $q)) {
    define('Action', 'deleteadmin');

    do_header(__('Eliminar administrador'));
    include($CFG->admdir . 'user_delete.php');
}

// admin delete
elseif (level_admin(2) && preg_match('#^admin/speakers/\d+/delete$#', $q)) {
    define('Action', 'deletespeaker');

    do_header(__('Eliminar ponente'));
    include($CFG->admdir . 'user_delete.php');
}

// admin delete
elseif (level_admin(2) && preg_match('#^admin/persons/\d+/delete$#', $q)) {
    define('Action', 'deleteperson');

    do_header(__('Eliminar asistente'));
    include($CFG->admdir . 'user_delete.php');
}

// menu edit details
elseif (preg_match('#^admin/details$#', $q)) {
    define('Action', 'editdetails');

    do_header(__('Modificar información personal'));
    include($CFG->comdir . 'user_edit.php');
}

/*
 * Schedule
 *
 */

// schedule view
elseif (preg_match('#^admin/schedule$#', $q)) {
    define('Action', 'viewschedule');

    do_header(__('Programa de Eventos'));
    include($CFG->admdir . 'schedule_view.php');
    do_submit_cancel('', __('Regresar'), $CFG->home_url);
}

// attach event to date
elseif (level_admin(2) && preg_match('#^admin/schedule/add/\d+/\d+/\d+$#', $q)) {
    define('Action', 'addschedule');

    do_header(__('Agregar evento'));
    include($CFG->admdir . 'schedule_add.php');
}

// attach event to date action
elseif (level_admin(2) && preg_match('#^admin/schedule/add/\d+/\d+/\d+/\d+$#', $q)) {
    define('Action', 'addschedule_action');

    do_header(__('Agregar evento'));
    include($CFG->admdir . 'schedule_add.php');
}


/*
 *
 *
 */
// page not found
else {
    do_header(__('Página no encontrada'));
    include($CFG->tpldir . 'error_404.tmpl.php');
    do_submit_cancel('', __('Regresar'));
}

// footer is called in main index
?>
