<?php 
define('QUAD',true);
require_once 'params.php';

if($_GET['method']==='login')
	login();
elseif($_GET['method']==='logout')
	logout();
	
echo <<<XOF
<form action="montyhalleng" method="POST">
     <input name="myActionName" type="submit" value="Посмотреть на парадокс" />
</form> 
XOF;

if(empty($_SESSION['user'])){
	echo authform();
}
else{
	echo welcom();
}

?>