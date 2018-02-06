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
elseif($_GET['method']==='personalmes'){
	echo personal_mes($mysqli);
}
elseif($_GET['method']==='sendmes'){
	if(!empty($_POST["user"])){
		echo write_mes($mysqli);
	}
	else{
		echo send_mes($_POST["username"]);
	}
	
}
echo <<<XOF
<form action="/montyhalleng" method="POST">
     <input name="myActionName" type="submit" value="{$_SESSION['count']}Посмотреть на парадокс{$_SESSION['bonus']}" >
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
	echo <<<XOF
		
		<form action='/sendmes' method='POST' >
			<input type='submit' name='users' value='Написать собеседнику' >
			<input hidden type='text' name='username'  >
			</form>
	
XOF;

	echo welcom();
}

?>