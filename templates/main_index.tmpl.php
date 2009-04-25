<div id="frontpage">
    <h1><?=__('Registro') ?></h1>

    <p><?=__('Gracias por tu interés en') ?> <?=$CFG->conference_name ?></p>

    <h3><a href="<?=get_url('speaker/register') ?>"><?=__('Registro de ponentes') ?></a>
    - <a href="<?=get_url('speaker/login') ?>"><?=__('Accede a tu cuenta') ?></a></h3>

    <p><?=__('Es necesario tu registro, mediante el cual podrás enviar ponencias y estar informado del evento.') ?></p>

    <h3><a href="<?=get_url('person/register') ?>"><?=__('Registro de asistentes') ?></a>
    - <a href="<?=get_url('person/login') ?>"><?=__('Accede a tu cuenta') ?></a></h3>

    <p><?=__('Es necesario tu registro, mediante el cual podrás realizar preinscripción así como
tambien tu inscripción en los talleres/tutoriales además de mantenerte informado del evento.') ?></p>

<?php if (!empty($CFG->public_proposals)) { ?>

    <h3><a href="<?=get_url('general/proposals') ?>"><?=__('Lista preliminar de ponencias') ?></a></h3>

    <p><?=__('Aquí podrás ver las propuestas ponencias que han sido enviadas y el status en el que se encuentran dichas ponencias.') ?></p>

<?php } ?>

<?php if (!empty($CFG->public_schedule) && schedule_has_events()) { ?>

    <h3><a href="<?=get_url('general/schedule') ?>"><?=__('Programa preliminar') ?></a></h3>

    <p><?=__('Aquí podrás ver el programa preliminar de ponencias y eventos.') ?></p>

<?php } ?>

    <h3><a href="<?=get_url('general/information') ?>"><?=__('Modalidades de participación') ?></a></h3>

    <p><?=__('Modalidades de las ponencias que encontraras en el evento!') ?></p>
    
    <h3><a href="<?=get_url('admin') ?>"><?=__('Panel de Administraci�n') ?></a></h3>

    <p><?=__('Iniciar sesi&oacute;n como administrador') ?></p>
</div>

<div class="block"></div>
