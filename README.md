# safexec

Attempt to create version of PHP's **exec()** that is more safe and every day usage friendly.


    echo safexec('dig %s', $o, array('example.com'));
    var_dump($o);

    echo safexec('dig %s', $o, array('example.com|FILTER_VALIDATE_DOMAIN'));

    echo safexec('dig %s', $o, array('example.com|FILTER_SANITIZE_STRING'));
    var_dump($o);

    echo safexec('php %s', $o2, array('--version'));
    var_dump($o2);

    echo safexec('whoami');


