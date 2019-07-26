<?php

function Check($description, $arg){
    if (!is_callable($arg)) return false;

    $additional = 'checkid_' . uniqid();


    if(call_user_func($arg, $additional)){
        echo "\r\n\r\nPASSED : $description\r\n";
        if(isset($GLOBALS[$additional]))
            echo $GLOBALS[$additional] . "\r\n";
        return true;
    } else {
        echo "\r\n\r\nFAILED : $description\r\n";
        if(isset($GLOBALS[$additional]))
            echo $GLOBALS[$additional] . "\r\n";        
        $GLOBALS['_Check_something_failed']=true;
        return true;
    }
}

register_shutdown_function(function(){
    if(isset($GLOBALS['_Check_something_failed']) && $GLOBALS['_Check_something_failed'] === true)
        echo "
|----------------------------------|
|-----There are failed tests!------|
|----------------------------------|
";
        else echo "
|----------------------------------|
|-------ALL TESTS ARE FINE!--------|
|----------------------------------|
";
});

function C_append($id, $val){
    $GLOBALS[$id] = $val;
}

/*Check("desc",function(){

});*/

require('safexec.php');


// #php pro tip: when you want to avoid name colision by prefixing var or fn name with 'custom', you are increasing the odds of colision.

Check("minimal example - command without arguments (whoami)", function($additional){
    $GLOBALS[$additional] = "simple as that";
    return safexec('whoami') === true;
});


Check("example with catching output - command without arguments (whoami)", function($a){
    if(!safexec('whoami', $output1)){
        $GLOBALS[$a] = "command whoami itself has failed";
        return false;
    }
    exec('whoami', $o1, $s1);
    if (implode( PHP_EOL, $o1 ) === $output1){
        $GLOBALS[$a] = "both commands are good";
        return true;
    }
});


Check("command dig with passed with param example.com", function($a){
    if(! safexec('dig %s', $output2, array('example.com')) ){
        $GLOBALS[$a] = 'dig command itself has failed';
    }
    return ( is_string($output2) && preg_match('/Got answer\:.*ANSWER SECTION\:/ms', $output2) );
});


Check("test of injection of command echo", function($a){
    if(safexec('php tstcmds/cmdinjectiontest.php %s', $output3, array('example.com && echo shouldnthappen'))){
        if(!empty($output3))
            $GLOBALS[$a] = $output3;
        return true;
    }
    return false;
});


Check('A simple example', function($a){
    global $$a; $$a = 'dasdsad';
    return true;
});


Check('A simple example 2', function($a){
    C_append($a, 'dasdsad');
    return true;
});


if(DIRECTORY_SEPARATOR === '/'){
    //*nix
   
    Check("test of injection of command echo", function($a){
        if(safexec('php tstcmds/cmdinjectiontest.php %s', $output3, array('example.com; echo shouldnthappen'))){
            if(!empty($output3))
                $GLOBALS[$a] = $output3;
            return true;
        }
        return false;
    });  
    
    Check("test of injection of command echo", function($a){
        if(safexec('php tstcmds/cmdinjectiontest.php %s', $output3, array('example.com | echo "shouldnthappen"'))){
            if(!empty($output3))
                $GLOBALS[$a] = $output3;
            return true;
        }
        return false;
    });    
    
}


