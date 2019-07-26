<?php
/**
 * 
 * Function safexec
 * 
 * More safe and friendly version of PHP's exec()
 * @param $cmd (string)     -> the command to be executed
 * 
 * @param &$output (string) -> output of the executed command,
 * var to be passed as reference
 * 
 * @param $ar (array)       -> array of arguments that is to 
 * be passed to command safely
 * 
 * @return bool true if status of the command is 0 (success)
 * @return int exit status of the non-zero exited command
 * 
 * 
 */
if(
    !defined('SAFEXEC_DANG_ON') &&
    !function_exists('safexec')
){

define('SAFEXEC_DANG_ON', true);
function safexec($cmd, &$output = '', $ar = array(), $dang_on = false){
    if($dang_on === true){
        // Inspect the content of the command, return false
        //and raise error if it's certain type of rm -rf
        
    }
    if( !empty($ar) ){
        foreach($ar as $key => $valuearg){
            if(preg_match('/\|(FILTER\_(VALIDATE|SANITIZE)\_(?:\w+))$/m', $valuearg, $hits)){
                if(isset($hits[2]) && $hits[2] === 'SANITIZE'){

                    $ar[$key] = filter_var($valuearg, $hits[1]);

                } else if (isset($hits[2]) && $hits[2] === 'VALIDATE' ){
                    //apply validate
                    if(filter_var($valuearg, $hits[1]) === false){
                        trigger_error('The argument passsed to command is not valid. The command will not be executed. Exiting.');
                        return false;
                    }
                } else {
                    trigger_error('Unknown internal error regarding passed argument for command to be executed. The command will not be executed. Exiting.');
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

}