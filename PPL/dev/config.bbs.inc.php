<?php

/**
 * @exception ÅäÖÃÎÄ¼þ
 * @copyright by vyouzhi on 20080809
 * @name config.php
 */
//if (!defined('APP_DB')){exit;}

/**
 * ADB config   , set all adb server in here 
 * host 
 * rw  //  rw is the server is master or salve or master and salve ,set here ,the MySql.class.php can clever switch
 *
 * for hash to select adb server
 */
if (!defined('M5CPL'))exit;

$L_r = array(
    //array('host'=>"127.0.0.1", 'port'=>'8000', 'rw'=>"r"),
    //array('host'=>"192.168.3.94", 'port'=>'8120', 'rw'=>"r"),
    //array('host'=>"192.168.3.94", 'port'=>'8122', 'rw'=>"r"),
    array('host'=>"192.168.3.7", 'port'=>'8120', 'rw'=>"r"),
    );
    
$L_w = array(
    array('host'=>"192.168.3.7", 'port'=>'8120', 'rw'=>"w"),
    );

?>
