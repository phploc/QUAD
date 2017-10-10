<?php 
define('QUAD',true);
require_once 'params.php';
echo <<<XOF
<form action="montyhalleng" method="POST">
     <input name="myActionName" type="submit" value="Посмотреть на парадокс" />
</form> 
XOF;

if(empty($_SESSION['user'])){
	authform();
}
else{
	welcom();
}

?>