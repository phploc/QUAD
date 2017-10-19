<?php
if(!defined('QUAD'))
	err();
define('HOME','php.loc');  //для проверки рефера
define('SALT','quadropus');  //для засолки
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
function to_location($url='/'){			//функция перенаправления
	header("Location: ".$url);
	exit;
}
function err($errortext,$bool=true){		////функция ошибки
	if($bool===true)
	die($errortext);
}
//*******************BAN bonus system********************************
if($_SESSION['bonus']>30 && $_SERVER['REQUEST_TIME']<$_SESSION['bantime']){
	unset($_SESSION['bonus']);
	to_location('//natribu.org/');
	}
if($_SESSION['count']>5 || $_SERVER['REQUEST_TIME']<$_SESSION['bantime']){
	$_SESSION['bonus']+=5;
	$_SESSION['bantime']=$_SERVER['REQUEST_TIME']+$_SESSION['bonus'];
	unset($_SESSION['count'],$_SESSION['oldtime']);
	err('на F5 можно и поменьше нажимать');
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
<input hidden type="text" name="token" value="Войти" />
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


//*************************register*********************************

function regist_button(){
return <<<XOF
<p><a href="register">Зарегистрироваться</a></p>
XOF;
}

function registration(){
if(empty($_SESSION['user']) && $_GET['method']=='register'){
return <<<XOF
Введите данные в форму регистрации
<form action="/register" method="post">
E-mail: <input required type="text" name="e-mail" /><br />
Username: <input required type="text" name="user" /><br />
Password: <input required type="password" name="pass" /><br />
<input type="submit" name="regsubmit" value="Зарегистрироваться" />
</form>
XOF;
}
to_location();
}

function writeregister($mysqli){
	if(!empty($_POST['user']) || !empty($_POST['pass']) || !empty($_POST['e-mail'])){
	$regis_email = $mysqli->real_escape_string($_POST['e-mail']);
	$regis_user = $mysqli->real_escape_string($_POST['user']);
	$regis_pass = $mysqli->real_escape_string($_POST['pass']);
	$chek_login = $mysqli->query("SELECT * FROM `users` WHERE login='{$regis_user}'"); 								//проверка уникальности Логина
	preg_match_all(/*'%[\.\-_A-Za-z0-9]+?@[\.\-A-Za-z0-9]+?[\ .A-Za-z0-9]{2,}%'*/'%.%',$regis_email, $match_email);	//регулярное выражение для email (ОТКЛЮЧЕНО) ($re = '/^[a-z0-9.]{1,30}@[a-z0-9]{1,30}\.[a-z]{2,10}$/mi';)							
	preg_match_all(/*'%[A-z\d]{3,}%'*/'%.%', $regis_user, $match_user);												//регулярное выражение для login  (ОТКЛЮЧЕНО)										
	preg_match_all(/*'%[A-z\d]{6,}%'*/'%.%', $regis_pass, $match_pass);												//регулярное выражение для pass  (ОТКЛЮЧЕНО)
	if($chek_login->num_rows>=1 || empty($match_email[0][0]) || empty($match_user[0][0])  || empty($match_pass[0][0])){
		to_location('register');
		}
	else {
	$write = $mysqli->query("INSERT INTO `users` (`id`, `login`, `password`, `time`, `banned`, `email`) VALUES (NULL, '{$regis_user}', '".md5(md5($regis_pass.SALT))."', CURRENT_TIMESTAMP, '0', '{$regis_email}')");
	}
	to_location();
}
}
//*************************login*************************************
function login($mysqli){
$host=parse_url($_SERVER['HTTP_REFERER'],PHP_URL_HOST);
if($host!=HOME && empty($_SESSION['user']) && $_GET['method']=='login' )
	authform();	
elseif(!empty($_POST['user']) || !empty($_POST['pass'])){
	
		$authuser = $mysqli->real_escape_string($_POST['user']);
		$authpass = $mysqli->real_escape_string($_POST['pass']);
		$res = $mysqli->query("SELECT * FROM `users` WHERE login='{$authuser}' AND password='".md5(md5($authpass.SALT))."'");
		if($res->num_rows>=1){
		$_SESSION['user'] = $_POST['user'];
		to_location();
		}
		else
		echo 'авторизация не пройдена';
}

}
//*************************logout*************************************
function logout(){
	$host=parse_url($_SERVER['HTTP_REFERER'],PHP_URL_HOST);
if($host!=HOME || $_SERVER['REQUEST_METHOD'] !='GET' )
	err('Закрой дверь с обратной стороны 2');	
unset($_SESSION['count'],$_SESSION['counter'],$_SESSION['user']);
to_location();
}