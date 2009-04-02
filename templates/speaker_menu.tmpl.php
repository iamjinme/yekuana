<h1><?=__('Ponentes') ?></h1>

<h2><?=__('Bienvenido') ?> <?=$USER->nombrep ?> <?=$USER->apellidos ?></h2>

<div id="menuadmin">

    <div class="menuponente column">

        <ul>
        <li><a href="<?=get_url('speaker/details') ?>"><?=__('Modificar mis datos') ?></a></li>
        <li><a href="<?=get_url('speaker/proposals/new') ?>"><?=__('Enviar propuesta de ponencia') ?></a></li>
        <li><a href="<?=get_url('speaker/proposals') ?>"><?=__('Lista de propuestas enviadas') ?></a></li>

<?php if (events_for('speaker', $USER->id)) { ?>

        <li><a href="<?=get_url('speaker/events') ?>"><?=__('Lista de mis eventos programados') ?></a></li>

<?php } ?>

        </ul>

    </div>

</div>
