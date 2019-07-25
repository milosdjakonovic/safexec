<?php

function safexec($cmd, &$output = '', $ar = array()){
    if($ar){
        foreach($ar as $key => $valuearg){
            if(preg_match('/\|(FILTER\_(?:VALIDATE|SANITIZE)\_(?:\w+))$/m', $valuearg, $hits)){
                //$cnst = constant($hits[1]);
                if(isset($hits[2]) && $hits[2] === 'SANITIZE'){

                    //apply santize

                } else if (isset($hits[2]) && $hits[2] === 'VALIDATE' ){
                    //apply validate
                }
                else {
                    //serious error.stop
                    trigger_error("text of error", E_USER_NOTICE);
                    return false;
                }
            }
            $ar[$key] = escapeshellarg($valuearg);
        }
        exec(vsprintf($cmd, $ar), $output, $status);
    } else {
        exec($cmd, $output, $status);
    }
    $output = implode(PHP_EOL, $output);
    return ($status === 0) ? true : $status;
}

/*


echo safexec('dig %s', $o, array('example.com'));
var_dump($o);

echo safexec('dig %s', $o, array('example.com|FITER_VALIDATE_DOMAIN'));
echo safexec('dig %s', $o, array('example.com|FILTER_SANITIZE_STRING'));
var_dump($o);

echo safexec('php %s', $o2, array('--version'));
var_dump($o2);

echo safexec('whoami');



*/


