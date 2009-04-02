<?php
    // running from system?
    if (empty($CFG)) {
        die;
    }

    if ($CFG->send_mail) {

        if (Context == 'ponente') {

            $registered_as = __('posible ponente');
            $url = get_url('speaker');

        } elseif (Context == 'asistente') {

            $registered_as = __('asistente');
            $url = get_url('person');

        }
 
        $toname = $user->nombrep .' '. $user->apellidos;
        $to = $user->mail;
        $subject = $CFG->conference_name . ': ' . __('Registro de ') . $registered_as;

        $message = sprintf(__('
Te has registrado como %s al %s

Usuario:    %s
Contraseña: %s

Puedes iniciar sesión entrando a la siguiente dirección:

%s


--
Equipo %s
%s

'), $registered_as, $CFG->conference_name, $user->login, $passwd, $url, $CFG->conference_name, $url);

        //3.. 2.. 1.. go!
        send_mail($toname, $to, $subject, $message);
?>

<p><?=__('Los datos de tu usuario y password han sido enviados al correo que registraste.') ?></p>
<p><?=__('Es posible que algunos servidores de correo registren el correo como correo no deseado  o spam y no se encuentre en su carpeta INBOX.') ?></p>

<?php
    } else {
?>

<p class="center error"><?=__('Por razones de seguridad deshabilitamos el envío de correo.') ?></p>

<?php } ?>
