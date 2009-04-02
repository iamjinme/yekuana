<?php
// running directly?
if (empty($CFG)) {
    die;
}

// add here new values
$configs = array(
    'conference_name' => 'string',
    'conference_link' => 'string',
    'adminmail' => 'string',
    'general_mail' => 'string',
    'wwwroot' => 'string',
    'limite' => 'integer',
    'def_hora_ini' => 'integer',
    'def_hora_fin' =>'integer',
    'send_mail' => 'integer',
    'smtp' => 'string',
    'unique_mail' => 'integer',
    'clean_url' => 'integer',
    'public_proposals' => 'integer',
    'public_schedule' => 'integer',
    'auth' => 'string',
    'locale' => 'string'
    );


// Common values
$submit = optional_param('submit');

if (!empty($submit)) {
    $cfg = new StdClass;

    foreach ($configs as $config => $type) {
        if ($type == 'integer') {
            $value = (int) optional_param($config, 0, PARAM_INT);
            $cfg->$config = $value;
        } else {
            $value = optional_param($config);

            if (!empty($value)) {
                $cfg->$config = $value;
            } else {
                // set to default CFG
                $cfg->$config = $CFG->$config;
            }

            $cfg->$config = $value;
        }
    }
}

?>
