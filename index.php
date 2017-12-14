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
elseif($_GET['method']==='forgot' && $_SERVER['REQUEST_METHOD']=='POST'){
	echo forgot_pass_letter($mysqli,$_POST['user']);
}
elseif($_GET['method']==='writepass' && $_SERVER['REQUEST_METHOD']=='POST'){
	echo write_pass($mysqli);
}
echo <<<XOF
<form action="/montyhalleng" method="POST">
     <input name="myActionName" type="submit" value="{$_SESSION['count']}Посмотреть на парадокс{$_SESSION['bonus']}" />
</form> 
<form action="/mail" method="POST">
     <input name="myActionName" type="submit" value="E-mail" />
</form>
XOF;

if(empty($_SESSION['user'])){
	
	$ok=strstr($_SERVER['REQUEST_URI'],'result=');
		if($ok){
			if(empty($RES[$ok])){
			echo rawurldecode($ok);	
			}
			echo $RES[$ok],'<br>';
		}
		
	if($_GET['method']==='register' && $_SERVER['REQUEST_METHOD']=='GET'){
		echo registration();
	}
	elseif($_GET['method']==='forgot' && $_SERVER['REQUEST_METHOD']=='GET'){
		echo forgot_pass();
	}
	elseif(isset($_GET['superstring']) && $_SERVER['REQUEST_METHOD']=='GET'){
		echo reset_pass($_GET['superstring']);
	}
	else {
	echo authform();
	echo regist_button();
	echo forgot_pass_button();
	}
}
else{
	echo welcom();
}

?>