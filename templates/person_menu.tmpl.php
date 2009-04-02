<h1><?=__('Asistentes') ?></h1>

<h2><?=__('Bienvenido') ?> <?=$USER->nombrep ?> <?=$USER->apellidos ?></h2>

<div id="menuadmin">

    <div class="menuadmin column">

        <ul>

        <li><a href="<?=get_url('person/details') ?>"><?=__('Modificar mis datos') ?></a></li> 
        <li><a href="<?=get_url('person/record') ?>"><?=__('Hoja de registro') ?></a></li>

        </ul>

    </div>

    <div class="menuadmin column">

        <ul>

        <li><a href="<?=get_url('person/workshops') ?>"><?=__('Registro a talleres y/o tutoriales') ?></a></li>
        <li><a href="<?=get_url('person/events') ?>"><?=__('Listas eventos programados') ?></a></li>

        </ul>

    </div>

</div>
