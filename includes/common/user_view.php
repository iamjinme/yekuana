<?php
// basic check
if (empty($q) || empty($CFG)) {
    die;
}

if (Context == 'admin') {
    if (Action == 'viewspeaker') {
        preg_match('#^admin/speakers/(\d+)/?$#', $q, $matches);
        $speaker_id = (!empty($matches)) ? (int) $matches[1] : 0;

        $user = get_speaker($speaker_id);
        $desc = __('Ponente');
        $prop_desc = __('Propuestas enviadas');
    }

    elseif (Action == 'viewperson') {
        preg_match('#^admin/persons/(\d+)/?$#', $q, $matches);
        $person_id = (!empty($matches)) ? (int) $matches[1] : 0;

        $user = get_person($person_id);
        $desc = __('Asistente');
        $prop_desc = __('Talleres/Tutoriales inscritos');
    }
}

elseif (Context == 'asistente') {
    $prop_desc = __('Talleres/Tutoriales inscritos');

    $user =get_person($USER->id);
}

if (!empty($user)) {
    if (Context == 'asistente') {
?>

<h1><?=__('Hoja de Registro') ?></h1>

<p class="center error"><?=__('Esta hoja te servirá para asistir a cualquier conferencia, plática informal y los talleres o tutoriales que te hayas registrado así como tambien confirmar tu participación en los eventos y extender tu constancia de registro.') ?></p>

<?php } else { ?>

<h1><?=__('Datos de') ?> <?=$desc ?></h1>

<?php
    }

    include($CFG->comdir . 'user_display_info.php');
?>

<h2 class="center"><?=$prop_desc ?></h2>

<?php
    include($CFG->comdir . 'prop_list.php');
} else {
?>

<h1><?=__('Usuario no encontrado') ?></h1>

<div class="block"</div>

<p class="error center"><?=__('El usuario que buscas no existe.') ?></p>

<?php 
}
?>
