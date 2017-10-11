<?php
if(!defined('QUAD'))
	die('eRrOr');
define('HOME','php.loc');  //для проверки рефера
date_default_timezone_set('Europe/Minsk');

ini_set("session.name", 'servise');
ini_set("session.cookie_lifetime", 604800);
ini_set("session.cookie_httponly", true);
ini_set("session.use_only_cookies", true);
ini_set("session.cookie_httponly", true);
ini_set("session.hash_function", 'crc32');
ini_set("session.cookie_domain", '.php.loc');
ini_set("session.save_path", 'F:\openserver\domains\php.loc\temp');
ini_set("session.gc_probability", 1);
ini_set("session.gc_divisor", 10);
session_start();


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
return <<<XOF
<form action="/login" method="post">
Username: <input required type="text" name="user" /><br />
Password: <input required type="password" name="pass" /><br />
<input type="submit" name="submit" value="Войти" />
</form>
XOF;
	
}
function welcom(){
	
	return <<<XOF
Вечер в хату, {$_SESSION['user']} <br>
<form action="/logout">
<input type="submit" name="logout" value="покинуть это" />
</form>
XOF;

}

//*************************login*************************************
function login(){
$host=parse_url($_SERVER['HTTP_REFERER'],PHP_URL_HOST);
if($host!=HOME || $_SERVER['REQUEST_METHOD'] !='POST' )
	die('Закрой дверь с обратной стороны 1');	


$user1='qw';
$pass1='7b5b40e9d2c268ddc876d026cfd08583';
if((empty($_POST['user']) || empty($_POST['pass'])) ||
	($_POST['user']===$user1 && md5(md5($_POST['pass']))===$pass1)){
		 $_SESSION['user'] = $user1;
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