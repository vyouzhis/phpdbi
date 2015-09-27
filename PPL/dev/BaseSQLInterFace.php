<?php
if (!defined('M5CPL')){exit;}
interface BaseSQL{
    public static function Query($sql, $pb="");
    public static function FetchArray($sql, $pb="");
    public static function FetchOne($sql, $pb="");
    public static function Delete($sql, $pb="");
    public static function Count($sql, $pb="");
    public static function Insert($sql, $pb="");
    public static function Update($sql, $pb="");
}

?>