<?php

if (empty($CFG) && Context == 'admin') {
    die;
}

if (!empty($submit) && !empty($configs)) {
    if (empty($cfg->conference_name)
        || empty($cfg->conference_link)
        || empty($cfg->adminmail)
        || empty($cfg->general_mail)
        || empty($cfg->wwwroot)
        || !preg_match('#.+\@.+\..+#', $cfg->adminmail)
        || !preg_match('#.+\@.+\..+#', $cfg->general_mail)
        || !preg_match('#https?://\w+#', $cfg->wwwroot)) {

        $errmsg[] = __('Asegúrate de llenar correctamente la información del sitio.');
    }

    if (preg_match('#.+/$#', $cfg->wwwroot)) {
        //remove trailing slash
        $cfg->wwwroot = substr($cfg->wwwroot, 0, strlen($cfg->wwwroot)-1);
    }
}

