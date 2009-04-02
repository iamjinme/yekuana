<?php
// dummy check
if (empty($CFG) || Context != 'admin') {
    die;
}

//FIXME: clearn return_path
$_SESSION['return_path'] = '';

// safe value
$where = '1=1';

// run filters
include($CFG->comdir . 'user_filter_optional_params.php');

if (Action == 'listspeakers') {
    $users = get_speakers($where);
    $desc = __('Ponentes');
    $local_url = 'speakers';
}

elseif (Action == 'listpersons') {
    $users = get_persons($where);
    $desc = __('Asistentes');
    $local_url = 'persons';
}

elseif (Action == 'controlpersons') {
    $where .= ' AND P.id_tasistente < 100';
    $users = get_persons($where);
    $desc = __('Control/Asistentes');
    $local_url = 'persons';
}

elseif (Action == 'workshopattendees') {
    preg_match('#^admin/proposals/(\d+)/persons$#', $q, $matches);
    $proposal_id = (!empty($matches)) ? (int) $matches[1] : 0;

    $proposal = get_proposal($proposal_id);

    $where .= ' AND P.id IN (SELECT id_asistente FROM '.$CFG->prefix.'inscribe WHERE id_evento = '.$proposal->id_evento.')';

    $desc = __('Asistentes');

    $local_url = 'persons';

    $users = get_persons($where);
}
?>

<h1><?=__('Lista de') ?> <?=$desc ?></h1>

<?php if (Action == 'workshopattendees') { ?>

<h2 class="center"><?=$proposal->nombre ?></h2>

<?php } ?>

<?php
if (!empty($users)) {
?>

<h4><?=$desc ?> <?=__('registrados') ?>: <?=sizeof($users) ?></h4>

<?php
    // show filter form
    include($CFG->comdir . 'user_filter.php');

    // build data table
    $table_data = array();

    if (Action == 'controlpersons') {
        $table_data[] = array(__('Nombre'), __('Login'), __('Estado'), __('Tipo'), __('Asistio?'), '', '');
    } else {
        $table_data[] = array(__('Nombre'), __('Login'), __('Departamento'), __('Estudios'), __('Registro'), '');
    }

    foreach ($users as $user) {

        $url = get_url('admin/'.$local_url.'/'.$user->id);
        $l_nombre = <<< END
<ul><li>
<a class="speaker" href="{$url}">{$user->apellidos} {$user->nombrep}</a>
</li></ul>
END;

        if (level_admin(2) && Action != 'workshopattendees') {
            $url = get_url('admin/'.$local_url.'/'.$user->id.'/delete');
            $l_delete = "<a class=\"precaucion\" href=\"{$url}\">" . __('Eliminar') . "</a>";
        } else {
            $l_delete = '';
        }
       
        if (Action == 'controlpersons') {
            $url = get_url('admin/persons/control/'.$user->id);

            $_SESSION['return_path'] = get_url('admin/persons/control');

            if (empty($user->asistencia)) {
                $l_asistio = __('No');
                $action_desc = __('+Asistencia');
            } else {
                $l_asistio = '<img src="'.get_url().'/images/checkmark.gif" />';
                $action_desc = __('-Asistencia');
            }

            $l_action = "<a class=\"verde\" href=\"{$url}\">{$action_desc}</a>";

            $table_data[] = array(
                $l_nombre,
                $user->login,
                $user->estado,
                $user->tasistente,
                $l_asistio,
                $l_action,
                $l_delete
                );
        } else {
            $table_data[] = array(
                $l_nombre,
                $user->login,
                $user->estado,
                $user->estudios,
                $user->reg_time,
                $l_delete
                );
        }
    }

    do_table($table_data, 'wide');

} else {
    $return_url = get_url('admin');
?>
<div class="block"></div>

<p class="error center"><?=__('No se encontraron registros.') ?></p>

<?php 
}
?>
