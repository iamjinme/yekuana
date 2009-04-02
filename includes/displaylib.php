<?php
//display functions


function do_get_output ($function, $parameters) {
    $output = '';

//    print_object($parameters);
    if (function_exists($function)) {

        if (is_array($parameters)) {
            // add slashes and quotes
            $len = sizeof($parameters);
            for ($i=0; $i < $len; $i++) {
//              $parameters[$i] = "'" . addslashes($parameters[$i]) . "'" ;
                $arg = $parameters[$i];

                if (is_object($arg) || is_array($arg)) {
                    //$$arg = $arg;
                    $var_code = "\$var{$i} = \$arg;";
                    $var_name = "var{$i}";
                    eval($var_code);
//                    print_object($arg);
//                    print_object($$var_name);

                    $parameters[$i] = "\${$var_name}";
                } elseif (is_int($arg)) {
                    $parameters[$i] = $arg;
                } else {
                    $parameters[$i] = "'" . addslashes($arg) . "'" ;
                }
            }

            $args = implode(',', $parameters);

        } else {
            $args = $parameters;
        }

        $eval_code = "{$function}({$args});";

//        print_object($eval_code);

        // run the function and get output
        ob_start();
        eval($eval_code);
        $output = ob_get_contents();
        ob_end_clean();
    }

    return $output;
}

function do_header ($title='') {
    global $CFG;
    global $USER;
    
    if (!empty($title)) {
        $title .= ' :: ';
    }
    $title .= $CFG->conference_name;

    if (!empty($USER)
        && (Context == 'admin'
            || Context == 'ponente'
            || Context == 'asistente')) {
        // show login info
        $login_info = true;
    } else {
        $login_info = false;
    }

    include($CFG->tpldir . 'header.tmpl.php');
}

function do_footer () {
    global $CFG;
	include($CFG->tpldir . 'footer.tmpl.php');
}

function do_table ($data, $class='table-data', $toggle=true, $id='table-data') {
    if (!is_array($data)) {
        return false;
    }

    if ($toggle) {
        $trclass = 'table-headers';
    } else {
        $trclass = '';
    }

    $trfirst = true;
    $even = true;
?>

<table id="<?=$id ?>"class="<?=$class ?>"> 

<?php
    foreach ($data as $row) {
        if (!is_array($row)) {
            break;
        }

        if ($trfirst) {
            $trfirst = false;
?>

    <tr class="<?=$trclass ?>">

<?php   } elseif ($toggle) { ?>
    
    <tr class="<?=($even) ? 'even' : 'odd' ?>">

<?php   } else { ?>
    
    <tr>

<?php   }

        $ncol = 1;
        foreach ($row as $column) {
?> 
    <td class="column-<?=$ncol ?>"><?=$column ?></td>

<?php
            $ncol++;
        }
?>
    </tr>
<?php
        // toggle tr class even odd
        if ($toggle) {
            $even = ($even) ? false : true;
        }
    }
?>

</table>

<?php
}

function do_table_values ($values, $class='table-values') {
    if (!empty($values) && is_array($values)) {
        if ($class != 'table-values') {
            $tdclass = $class.'-';
        } else {
            $tdclass = '';
        }
?>  

    <table class="<?=$class ?>">

<?php  
        foreach ($values as $name => $value) {
?>
    <tr>
        <td class="<?=$tdclass ?>name"><?=$name ?>:</td>
        <td class="<?=$tdclass ?>result"><?=$value ?></td>
    </tr>
<?php
        }
?>
    </table>
<?php
    }
}

function do_table_input($data, $class='table-input', $id='table-input') {

    if (!is_array($data)) {
        return false;
    }
?>

<table id="<?=$id ?>"class="<?=$class ?>"> 

<?php foreach ($data as $row) { ?>

    <tr>

<?php
        $column = 0;
        foreach ($row as $column_data) {
            $column++;
            if ($column == 1) { // first column
                $column_class = 'class="name"';
            } elseif ($column == 2) { // second column
                $column_class = 'class="input"';
            } else {
                $column_class = '';
            }
?>

        <td <?=$column_class ?>><?=$column_data ?></td>

<?php   } ?>

    </tr>

<?php } ?>

</table>

<?php
}

function do_input ($name, $type, $value, $attrs='') {
?>

    <input name="<?=$name ?>" type="<?=$type ?>" value="<?=$value ?>" <?=$attrs ?> />

<?php
}

function do_input_select ($name, $options, $selected=0, $unset=true, $unsetdesc='', $unsetval=0, $extra='') {
    if (is_array($options)) {
        // array of objects, each object is an option with:
        // $option->id: input value
        // $option->desc: option description
?>

    <select name="<?=$name ?>" <?=$extra ?>>

<?php if ($unset) { ?>

        <option name="unset" value="<?=$unsetval ?>" <?=(empty($selected)) ? 'selected="selected"' : '' ?>><?=$unsetdesc ?></option>

<?php } ?>


<?php foreach ($options as $option) { ?> 

        <option value="<?=$option->id ?>" <?=($option->id == $selected) ? 'selected="selected"' : '' ?>><?=$option->descr ?></option>

<?php } ?>

    </select>

<?php
    }
}

function do_input_yes_no($name, $selected=0, $yes_desc='', $no_desc='', $extra='') {
    $options = array();

    if (empty($yes_desc)) {
        $yes_desc = __('Yes');
    }

    if (empty($no_desc)) {
        $no_desc = __('No');
    }

    $no = new StdClass;
    $no->id = 0;
    $no->descr = $no_desc;

    $yes = new StdClass;
    $yes->id = 1;
    $yes->descr = $yes_desc;

    $options[] = $no;
    $options[] = $yes;

    do_input_select($name, $options, $selected, false, '', 0, $extra);
}

function do_input_time_slot ($name, $start, $end, $selected=0, $timeslot=1) {
    $options = array();

    if ($start < $end) {
        $slotend = 0;

        for ($n=$start; $n<=$end; $n++) {
            $nend = $n + $timeslot -1;

            if ($nend <= $end) {
                $option = new StdClass;
                $option->id = $n;
                $option->descr = sprintf('%02d:00 - %02d:50', $n, $nend);
                $options[] = $option;
            }
        }
    }

    if (!empty($options)) {
        do_input_select($name, $options, $selected);
    }
}

function do_input_number_select ($name, $start, $end, $selected=0, $unset=true, $unsetdesc='', $unsetval=0, $isdate=false, $ismonth=false) {
    // build options object for do_input_select
    $options = array();

    if ($start < $end) {
        for ($n=$start; $n<=$end; $n++) {
            $option = new StdClass;
            $option->id = $n;

            // if is day or month
            if ($isdate) {
                $option->descr = sprintf('%02d', $n);

                if ($ismonth) {
                    $option->descr = month2name($option->descr);
                }
            } else {
                $option->descr = $n;
            }

            //add option to array options
            $options[] = $option;
        }
    } else {
        for ($n=$start; $n>=$end; $n--) {
            $option = new StdClass;
            $option->id = $n;

            if ($isdate) {
                $option->descr = sprintf('%02d', $n);
            } else {
                $option->descr = $n;
            }

            //add option to array options
            $options[] = $option;
        }
    }

    if (!empty($options)) {
        do_input_select($name, $options, $selected, $unset, $unsetdesc, $unsetval);
    }
} 

function do_input_birth_select ($dayname, $monthname, $yearname, $dayselect=0, $monthselect=0, $yearselect=0) {
    // alias
    do_input_date_select($dayname, $monthname, $yearname, $dayselect, $monthselect, $yearselect);
}

function do_input_date_select($dayname, $monthname, $yearname, $dayselect=0, $monthselect=0, $yearselect=0, $startyear=1999, $endyear=1950, $startmonth=1, $endmonth=12, $startday=1, $endday=31) {

    //day select
    do_input_number_select($dayname, $startday, $endday, $dayselect, true, __('Dia'), 0, true);

    //month select
    do_input_number_select($monthname, $startmonth, $endmonth, $monthselect, true, __('Mes'), 0, true, true);

    //year select
    do_input_number_select($yearname, $startyear, $endyear, $yearselect, true, __('Año'), 0, true);
}

function do_submit_cancel($submit_value, $cancel_value, $url='', $name='submit') {
    if (!empty($url)) {
        $onclick = "onClick=\"location.href='{$url}/'\"";
    } else { // default history back
        $onclick = "onClick=\"history.back(-1)\"";
    }
?>

<p id="buttons">

<?php
    if (!empty($submit_value)) {
        do_input($name, 'submit', $submit_value);
    }

    if (!empty($cancel_value)) {
        do_input($name, 'button', $cancel_value, $onclick);
    }
?>

</p>

<?php
}

// print a bold message in an optional color
function notify ($message, $style='error', $align='center') {
    $message = clean_text($message);
?>
    <div class="<?=$style ?>" align="<?=$align ?>"><?=$message ?></div>

<?php
}

function showError($errmsg) {
    show_error($errmsg);
}

function show_error($errmsg, $error = true) {
?>

<div id="messages">

<?php
    if ($error) {
?>

    <p class="error"><?=__('Por favor verifique lo siguiente') ?>:</p>
    <ul class="error">

<?php
        if (is_array($errmsg)) {
            foreach ($errmsg as $msg) {
?>

        <li><?=$msg ?></li>

<?php       }
        } else {
?>

        <li><?=$errmsg ?></li>

<?php   } ?>

    </ul>
<?php
    } else { 
    
        if (is_array($errmsg)) {
            foreach ($errmsg as $msg) {
?>
    <p><?=$msg ?></p>

<?php
            }
        } else {
?>

    <p><?=$errmsg ?></p>
            
<?php   } ?>
   
<?php } ?>

</div>
        
<?php
}

//size in bytes
function human_filesize($size) {
    $size = (int)$size;

    if ($size < 1024) {
        return $size . '&nbsp;bytes';
    }

    $size = $size / 1024;

    if ($size < 1024) {
        return sprintf('%.2d&nbsp;Kb.', $size);
    }

    $size = $size / 1024;

    if ($size < 1024) {
        return sprintf('%.2d&nbsp;Mb.');
    }

    //FIXME
    return sprintf('%.2d&nbsp;Mb.');
}

?>
