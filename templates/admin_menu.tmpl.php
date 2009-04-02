<h1><?=__('Bienvenido Administrador') ?></h1>

<h3>:: <?=$USER->nombrep ?> <?=$USER->apellidos ?></h3>

<div id="menuadmin">

    <div id="menuadmin-ponencias" class="menuadmin column">

        <a name="ponencias"></a>

        <h3><?=__('Ponencias y Ponentes') ?></h3>

        <ul>

<?php if (level_admin(2)) { ?>

<li><a href="<?=get_url('admin/speakers/new') ?>"><?=__('Agregar ponente') ?></a></li>

<?php } ?>

<li><a href="<?=get_url('admin/speakers/') ?>"><?=__('Listado de ponentes') ?></a></li>

<?php if (level_admin(2)) { ?>

<li><a href="<?=get_url('admin/proposals/new') ?>"><?=__('Agregar ponencia') ?></a></li>

<?php } ?>


<li><a href="<?=get_url('admin/proposals/') ?>"><?=__('Listado de ponencias') ?></a></li>

        </ul>

    </div>

   <div id="menuadmin-eventos" class="menuadmin column">

        <a name="eventos"></a>

        <h3><?=__('Eventos y Asistentes') ?></h3>

        <ul>

<?php if (level_admin(2)) { ?>

        <li><a href="<?=get_url('admin/persons/control') ?>"><?=__('Control de asistencias') ?></a></li>

        <li><a href="<?=get_url('admin/persons/') ?>"><?=__('Listado de asistentes') ?></a></li>

<?php } ?>

        <li><a href="<?=get_url('admin/schedule') ?>"><?=__('Programa preliminar') ?></a></li>

<?php if (level_admin(2)) { ?>

        <li><a href="<?=get_url('admin/events/new') ?>"><?=__('Agregar evento') ?></a></li>

        <li><a href="<?=get_url('admin/events/schedule') ?>"><?=__('Eventos pendientes') ?></a></li>

<?php } ?>

        <li><a href="<?=get_url('admin/events') ?>"><?=__('Listado de eventos') ?></a></li>

<?php if (level_admin(2)) { ?>

        <li><a href="<?=get_url('admin/workshops/add') ?>"><?=__('Inscripción a talleres/tutoriales') ?></a></li>
        <li><a href="<?=get_url('admin/workshops/remove') ?>"><?=__('Baja de talleres/tutoriales') ?></a></li>

<?php } ?>

       </ul>

    </div>

<?php if (level_admin(2)) { ?>

    <div id="menuadmin-lugares" class="menuadmin column">

        <a name="lugares"></a>

        <h3><?=__('Lugares y Fechas') ?></h3>

        <ul>

        <li><a href="<?=get_url('admin/rooms/new') ?>"><?=__('Registrar lugar') ?></a></li>
        <li><a href="<?=get_url('admin/rooms/') ?>"><?=__('Listado de lugares') ?></a></li>
        <li><a href="<?=get_url('admin/dates/new') ?>"><?=__('Registrar fecha') ?></a></li>
        <li><a href="<?=get_url('admin/dates/') ?>"><?=__('Listado de fechas') ?></a></li>

        </ul>

    </div>

<?php } ?>

 
    <div id="menuadmin-admin" class="menuadmin column">

        <a name="admin"></a>

        <h3><?=__('Administración') ?></h3>

        <ul>

<?php if (level_admin(1)) { ?>

        <li><a href="<?=get_url('admin/config') ?>"><?=__('Configuración') ?></a></li>
        <li><a href="<?=get_url('admin/catalog') ?>"><?=__('Administrar catálogos') ?></a></li>
        <li><a href="<?=get_url('admin/new') ?>"><?=__('Agregar administrador') ?></a></li>
        <li><a href="<?=get_url('admin/list') ?>"><?=__('Listar administradores') ?></a></li>
        <li><a href="<?=get_url('admin/proposals/deleted') ?>"><?=__('Listar ponencias eliminadas') ?></a></li>

<?php } ?>
        <li><a href="<?=get_url('admin/details') ?>"><?=__('Modificar mis datos') ?></a></li>

        </ul>

    </div>

</div><!-- #menuadmin -->
