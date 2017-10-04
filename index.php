<?php
define('QUAD',true);
require_once 'params.php';
if(empty($_SESSION['user'])){
	authform();
}
else{
	welcom();
}
?>