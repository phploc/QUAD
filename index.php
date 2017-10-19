<?php 
define('QUAD',true);
require_once 'params.php';

if($_GET['method']==='login')
	login($mysqli);
elseif($_GET['method']==='logout')
	logout();
elseif($_GET['method']==='register' && $_SERVER['REQUEST_METHOD']=='POST'){
	writeregister($mysqli);
}
echo <<<XOF
<form action="montyhalleng" method="POST">
     <input name="myActionName" type="submit" value="{$_SESSION['count']}Посмотреть на парадокс{$_SESSION['bonus']}" />
</form> 
XOF;

if(empty($_SESSION['user'])){
	if($_GET['method']==='register' && $_SERVER['REQUEST_METHOD']=='GET'){
		echo registration();
	}
	else {
	var_dump($_SESSION);
	echo authform();
	echo regist_button();
	}
}
else{
	echo welcom();
}

?>