<?php
session_start();
if(!defined('QUAD'))
	die('Этот файл нельзя вызывать напрямую');
define('HOME','php.loc');
date_default_timezone_set('Europe/Minsk');
//*******************BAN bonus system********************************
if($_SESSION['bonus']>30 && $_SERVER['REQUEST_TIME']<$_SESSION['bantime']){
unset($_SESSION['bonus']);
header("Location: //natribu.org/");
exit;
}
if($_SESSION['count']>5 || $_SERVER['REQUEST_TIME']<$_SESSION['bantime']){
	$_SESSION['bonus']+=5;
	$_SESSION['bantime']=$_SERVER['REQUEST_TIME']+$_SESSION['bonus'];
	unset($_SESSION['count'],$_SESSION['oldtime']);
	die('на F5 можно и поменьше нажимать');
}
		
if(isset($_SESSION['oldtime'])){
	$deltatime=$_SERVER['REQUEST_TIME']-$_SESSION['oldtime'];
	$_SESSION['oldtime'] = $_SERVER['REQUEST_TIME'];
	if($deltatime<3){
		$_SESSION['count']++;
}
}
else{
	$_SESSION['oldtime'] = $_SERVER['REQUEST_TIME'];
}

//*************************index*************************************
function authform(){
echo <<<XOF
<form action="login.php" method="post">
Username: <input required type="text" name="user" /><br />
Password: <input required type="password" name="pass" /><br />
<input type="submit" name="submit" value="Войти" />
</form>
XOF;
 exit;	
}
function welcom(){
	echo "Вечер в хату, ",$_SESSION['user'];
	echo <<<XOF
<form action="logout.php">
<input type="submit" name="submit" value="Выход" />
</form>
XOF;
 exit;
}

//*************************login*************************************
function login(){
$host=parse_url($_SERVER['HTTP_REFERER'],PHP_URL_HOST);
if($host!=HOME || $_SERVER['REQUEST_METHOD'] !='POST' )
	die('Закрой дверь с обратной стороны 1');	


$user1='qw';
$pass1='7b5b40e9d2c268ddc876d026cfd08583';
if((empty($_POST['user']) || empty($_POST['pass'])) ||
	($_POST['user']==$user1 && md5(md5($_POST['pass']))==$pass1 && $_SESSION['user'] = $user1)){
	header("Location: /");
	exit;
}
echo 'авторизация не пройдена';
}

//*************************logout*************************************
function logout(){
	$host=parse_url($_SERVER['HTTP_REFERER'],PHP_URL_HOST);
if($host!=HOME || $_SERVER['REQUEST_METHOD'] !='GET' )
	die('Закрой дверь с обратной стороны 2');	

unset($_SESSION['count'],$_SESSION['counter'],$_SESSION['user']);
header("Location: /");
exit;
}