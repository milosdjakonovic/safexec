<?php



require('safexec.php');




if( safexec('whoami') === true ){
    echo "Passed - minimal example - command without arguments (whoami)\r\n";
} else {
    echo "Failed! - minimal example - command without arguments (whoami)\r\n";
}



if ( safexec('whoami', $output1) ){
    //if ($output1)
    exec('whoami', $o1, $s1);
    if( implode( PHP_EOL, $o1 ) === $output1 )
        echo "Passed - example with catching output - command without arguments (whoami)\r\n";
    else 
        echo "Failed! - example with catching output - outputs doesn't match - command without arguments (whoami)\r\n";
} else echo "Failed! - example with catching output - command itself failed - command without arguments (whoami)\r\n";




if( safexec('dig %s', $output2, array('example.com')) ){
    if( is_string($output2) && preg_match('/Got answer\:.*ANSWER SECTION\:/ms', $output2) ){
        echo "Passed - command dig with passed param example.com\r\n";
    }
}



safexec('php testcommand.php %s', $output3, array('example.com; echo shouldnthappen'));
echo $output3;

