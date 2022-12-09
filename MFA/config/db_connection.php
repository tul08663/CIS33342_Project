<?php
/*
 * Name: DATABASE connection script
 * File name: db_connection.php
 * database Connection variables
 * */
define("HOST", "cis-linux2.temple.edu");
define("USER", "tul08663");
define("PASSWORD", "coozoowo");
define("DATABASE","fa22_3342_tul08663");
define('CHARSET', 'utf8');

function DB()
{
    static $instance;
    if ($instance === null) {
        $opt = array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => FALSE,
        );
        $dsn = 'mysql:host=' . HOST . ';dbname=' . DATABASE . ';charset=' . CHARSET;
        $instance = new PDO($dsn, USER, PASSWORD, $opt);
    }
    return $instance;
}

?>
