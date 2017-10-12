<?php

if(!defined('QUAD'))
	err();
define('HOME','php.loc');  //для проверки рефера
date_default_timezone_set('Europe/Minsk');

ini_set("session.name", 'servise');
ini_set("session.cookie_lifetime", 604800);
ini_set("session.cookie_httponly", true);
ini_set("session.use_only_cookies", true);
ini_set("session.cookie_httponly", true);
ini_set("session.hash_function", 'crc32');
ini_set("session.cookie_domain", HOME);
ini_set("session.save_path", "F:\openserver\domains\\".HOME."\\temp");
ini_set("session.gc_probability", 1);
ini_set("session.gc_divisor", 10);
session_start();


function head($url='/'){			//функция перенаправления
	header("Location: ".$url);
	exit;
}

function err($errortext='eRrOr',$bool=true){		////функция ошибки
	if($bool===true)
	die($errortext);
}


//*******************BAN bonus system********************************
if($_SESSION['bonus']>30 && $_SERVER['REQUEST_TIME']<$_SESSION['bantime']){
	unset($_SESSION['bonus']);
	head('//natribu.org/');
	}
if($_SESSION['count']>5 || $_SERVER['REQUEST_TIME']<$_SESSION['bantime']){
	$_SESSION['bonus']+=5;
	$_SESSION['bantime']=$_SERVER['REQUEST_TIME']+$_SESSION['bonus'];
	unset($_SESSION['count'],$_SESSION['oldtime']);
	err();
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

//*******************DBmysql********************************

$mysqli = new mysqli('127.0.0.1', 'root', '', 'first');
if ($mysqli->connect_error) {
    die('Ошибка подключения (' . $mysqli->connect_errno . ') '
            . $mysqli->connect_error);
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
function login($mysqli){
$host=parse_url($_SERVER['HTTP_REFERER'],PHP_URL_HOST);
if($host!=HOME || $_SERVER['REQUEST_METHOD'] != 'POST' )
	err();	



if(!empty($_POST['user']) || !empty($_POST['pass'])){
		$salt='quadropus';
		$res = $mysqli->query("SELECT * FROM `users` WHERE login='{$_POST['user']}' AND password='".md5(md5($_POST['pass'].$salt))."'");
		if($res->num_rows>=1){
		$_SESSION['user'] = $_POST['user'];
		head();
		}
}
echo 'авторизация не пройдена';
}

//*************************logout*************************************
function logout(){
	$host=parse_url($_SERVER['HTTP_REFERER'],PHP_URL_HOST);
if($host!=HOME || $_SERVER['REQUEST_METHOD'] !='GET' )
	err();	

unset($_SESSION['count'],$_SESSION['counter'],$_SESSION['user']);
head();
}