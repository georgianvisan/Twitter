<?php
session_start();
define('ROOT',dirname(__DIR__));
var_dump(dirname(__DIR__));

$public = ['home','login','register'];
$private =['profile','addTweet','removeTweet','comment'];

if(!isset($_GET['page'])) {
$page = 'home';
} else {
$page = in_array($_GET['page'], $public) ?
$_GET['page'] : '404';
$action = ROOT."/includes/views/$page.php";
if (isset($action)){
    require_once $action;
}
}

include ROOT.'/includes/views/main.php';