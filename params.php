<?php
if(!defined('QUAD'))
	err();
define('HOME','php.loc');  //для проверки рефера
define('SALT','quadropus');  //для засолки
define('LIFETIME',600);		//время жизни формы
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

function saling($sacred_string){
	return md5(md5($sacred_string.SALT));
}

function crypt_hide($code=true){				////функция шифровки/расшифровки
	$id_sess=session_id();
	if($code===true){
	return openssl_encrypt($id_sess.time().SALT,'RC4-40','gh');
	}
	else{
	$b=openssl_decrypt($code,'RC4-40','gh');
	return str_replace(array($id_sess,SALT),'',$b);
	}
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
		
if(isset($_SESSION['oldtime']) && $_SERVER['REQUEST_URI'] == $_SESSION['oldurl'] ){
	$deltatime=$_SERVER['REQUEST_TIME']-$_SESSION['oldtime'];
	$_SESSION['oldtime'] = $_SERVER['REQUEST_TIME'];
	if($deltatime<3){
		$_SESSION['count']++;
}
}
else{
	$_SESSION['oldtime'] = $_SERVER['REQUEST_TIME'];
	$_SESSION['oldurl'] = $_SERVER['REQUEST_URI'];
}

//*******************DBmysql********************************
$mysqli = new mysqli('127.0.0.1', 'root', '', 'first');
if ($mysqli->connect_error) {
    die('Ошибка подключения (' . $mysqli->connect_errno . ') '
            . $mysqli->connect_error);
}
//*************************index*************************************
function authform(){
$CRYPT=crypt_hide();
return <<<XOF
<form action="/login" method="post">
Username: <input required type="text" name="user" /><br />
Password: <input required type="password" name="pass" /><br />
<input hidden type="text" name="crypt" value="{$CRYPT}" />
<input type="submit" name="submit" value="Войти" />
</form>
XOF;
	
}
function welcom(){
if($_SESSION['id_type']==0){
echo <<<XOF
<form action="/users_table">
<input type="submit" name="users" value="Таблица пользователей" />
</form>
XOF;
}
	return <<<XOF
Вечер в хату, {$_SESSION['user']}{$_SESSION['id_type']} <br>
<form action="/logout">
<input type="submit" name="logout" value="покинуть это" />
</form>
XOF;
}


//*************************register*********************************

function regist_button(){
return <<<XOF
<p><a href="/register">Зарегистрироваться</a></p>
XOF;
}

function registration(){
if(empty($_SESSION['user']) && $_GET['method']=='register'){
$CRYPT=crypt_hide();
return <<<XOF
Введите данные в форму регистрации
<form action="/register" method="post">
Email: <input required type="text" name="email" /><br />
Username: <input required type="text" name="user" /><br />
Password: <input required type="password" name="pass" /><br />
<input hidden type="text" name="crypt" value="{$CRYPT}" />
<input type="submit" name="regsubmit" value="Зарегистрироваться" />
</form>
XOF;
}
to_location();
}

function writeregister($mysqli){
	$time_login=crypt_hide($_POST['crypt']);
	if(!empty($_POST['user']) && !empty($_POST['pass']) && !empty($_POST['email']) && (time()-$time_login)<LIFETIME){
	$regis_email = $mysqli->real_escape_string($_POST['email']);
	$regis_user = $mysqli->real_escape_string($_POST['user']);
	$regis_pass = $mysqli->real_escape_string($_POST['pass']);
	preg_match_all(/*'%[\.\-_A-Za-z0-9]+?@[\.\-A-Za-z0-9]+?[\ .A-Za-z0-9]{2,}%'*/'%.%',$regis_email, $match_email);	//регулярное выражение для email (ОТКЛЮЧЕНО) ($re = '/^[a-z0-9.]{1,30}@[a-z0-9]{1,30}\.[a-z]{2,10}$/mi';)							
	preg_match_all(/*'%[A-z\d]{3,}%'*/'%.%', $regis_user, $match_user);												//регулярное выражение для login  (ОТКЛЮЧЕНО)										
	preg_match_all(/*'%[A-z\d]{6,}%'*/'%.%', $regis_pass, $match_pass);												//регулярное выражение для pass  (ОТКЛЮЧЕНО)
	if( empty($match_email[0][0]) || empty($match_user[0][0])  || empty($match_pass[0][0])){
		to_location('register');
		}
	$chek_login = $mysqli->query("SELECT * FROM `users` WHERE login='{$regis_user}'"); 								//проверка уникальности Логина
	if($chek_login->num_rows>=1){
		to_location('register');
	}
	else {
	$sail_pass=saling($regis_pass);
	$write = $mysqli->query("INSERT INTO `users` (`id`, `login`, `password`, `time`, `banned`, `email`) VALUES (NULL, '{$regis_user}', '{$sail_pass}', CURRENT_TIMESTAMP, '0', '{$regis_email}')");
	}
	to_location();
}
}
//*************************login*************************************
function login($mysqli){
$host=parse_url($_SERVER['HTTP_REFERER'],PHP_URL_HOST);
$time_login=crypt_hide($_POST['crypt']);
if($host!=HOME && empty($_SESSION['user']) && $_GET['method']=='login' )
	authform();
elseif(!empty($_POST['user']) && !empty($_POST['pass']) && (time()-$time_login)<LIFETIME ){
		
		$authuser = $mysqli->real_escape_string($_POST['user']);
		$authpass = $mysqli->real_escape_string($_POST['pass']);
		$sail_pass=saling($authpass);
		$res = $mysqli->query("SELECT * FROM `users` WHERE login='{$authuser}' AND password='{$sail_pass}'");
		if($res->num_rows>=1){
		$user_info=$res->fetch_assoc();
		$_SESSION['user'] = $user_info['login'];
		$_SESSION['id_type'] = $user_info['id_type'];
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