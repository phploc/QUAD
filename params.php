<?php
if(!defined('QUAD'))
	err();
define('HOME','php.loc');  //для проверки рефера
define('SALT','quadropus');  //для засолки
define('LIFETIME',600);		//время жизни формы


$RES = array (
    'result=1' => 'Данный логин уже занят. Придумайте другой.',
    'result=2' => 'Пароли в формах не совпадают.',
    'result=3' => 'Письмо успешно отправлено.',
    'result=4' => 'Ошибка при отправки письма.',
    'result=5' => 'Пароль успешно сменён.',
    'result=6' => 'Пароль не соответствует правилам. (Минимум 6 цифр или латинских символов)',
    'result=7' => 'Данного логина не существует'
);


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

function send_mail($recipient,$mail_theme,$mail_body){						//функция отправки письма
	require_once "SendMailSmtpClass.php";
	$mailSMTP = new SendMailSmtpClass('trotzky.viktor@yandex.ru','fuckyou1','ssl://smtp.yandex.ru','php.loc',465);
	$headers= "MIME-Version: 1.0\r\n";
	$headers .= "Content-type: text/html; charset=utf-8\r\n"; // кодировка письма
	$headers .= "From: php.loc <trotzky.viktor@yandex.ru>\r\n"; // от кого письмо
	$result =  $mailSMTP->send($recipient, $mail_theme, $mail_body, $headers); // отправляем письмо
	$_POST['result']=$result;
	if($result === true){
		return "3";
	}else{
		return $result;
	}
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
Password: <input required id='pass' type="password" name="pass" /><br />
<input hidden type="text" name="crypt" value="{$CRYPT}" />
<input type="submit" name="submit" value="Войти" />
</form>
XOF;

/*
return <<<XOF

<script>
  function getValue(){
    var text = document.getElementById("pass").value;
	text+='dshfjkl';
	document.getElementById("pass").value=text;
    alert(text);
  }
</script>

<form action="/login" method="post">
Username: <input required type="text" name="user" /><br />
Password: <input required id='pass' type="password" name="pass" /><br />
<input hidden type="text" name="crypt" value="{$CRYPT}" />
<input type="submit" name="submit" onclick="getValue()" value="Войти" />
</form>
XOF;
*/	
}
function welcom(){
if($_SESSION['id_type']==7){
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


//*************************forgot_pass*********************************
function forgot_pass_button(){
return <<<XOF
<p><a href="/forgot">Восстановление пароля</a></p>
XOF;
}
function forgot_pass(){
	return <<<XOF
Введите ваш ник для восстановления пароля
<form action="/forgot" method="post">
Username: <input required type="text" name="user" /><br />
<input type="submit" name="passsubmit" value="Сменить пароль" />
</form>
XOF;
}
function forgot_pass_letter($mysqli,$login){
	
	$chek_login = $mysqli->query("SELECT * FROM `users` WHERE login='{$login}' LIMIT 1"); 								//проверка уникальности Логина
	if($chek_login->num_rows<1){
		to_location('/?result=7');
	}
	$login_info=$chek_login->fetch_assoc();
	$id=$login_info['id'];
	$time=time()+86400;
	$hash=openssl_encrypt(openssl_encrypt($time.$id,'RC4-40','gh'),'RC4-40','gh');
	$url='http://'.HOME."/index.php?superstring={$hash}";
	$send_res = send_mail($login_info['email'],'Восстановление пароля',"Для восстановления пароля перейдите по этой ссылке: {$url}");
	to_location('/?result='.$send_res);
		
	
}
function reset_pass($string){
	$b=openssl_decrypt(openssl_decrypt($string,'RC4-40','gh'),'RC4-40','gh');
	$time=substr($b,0,10);
	
	if($time>time()){
	$id=substr($b,10);
	
	return <<<XOF
	Введите ваш новый пароль
	<form action="/writepass" method="post">
	Password: <input required type="password" name="pass" /><br />
	Repeat Password: <input required type="password" name="rep_pass" /><br />
	<input hidden type="text" name="crypt" value="{$id}" />
	<input type="submit" name="passsubmit" value="Сменить пароль" />
	</form>
XOF;
	}
	to_location();
}
function write_pass($mysqli){
	if($_POST['pass'] !== $_POST['rep_pass']){
		to_location('?result=2');		//ОШИБКА Пароли не совпадают
	}
	$id=$_POST['crypt'];
	$new_pass = $mysqli->real_escape_string($_POST['pass']);
	preg_match(/*'%^[A-z\d]{6,}$%'*/'%.%', $new_pass, $match_pass);
	if(mb_strlen($new_pass) == mb_strlen($match_pass[0]) ){
		to_location('?result=6');
	}
	$sail_pass=saling($new_pass);
	$write_pass = $mysqli->query("UPDATE `users` SET `password` = '{$sail_pass}' WHERE `users`.`id` = {$id}");
	to_location('?result=5');
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
Repeat Password: <input required type="password" name="rep_pass" /><br />
<input hidden type="text" name="crypt" value="{$CRYPT}" />
<input type="submit" name="regsubmit" value="Зарегистрироваться" />
</form>
XOF;
}
to_location();
}

function writeregister($mysqli){
	$time_login=crypt_hide($_POST['crypt']);
	if(!empty($_POST['user']) && !empty($_POST['pass']) && !empty($_POST['email']) && (time()-$time_login)<LIFETIME && $_POST['pass'] === $_POST['rep_pass'] ){
	$regis_email = $mysqli->real_escape_string($_POST['email']);
	$regis_user = $mysqli->real_escape_string($_POST['user']);
	$regis_pass = $mysqli->real_escape_string($_POST['pass']);
	preg_match(/*'%^[\.\-_A-Za-z0-9]+?@[\.\-A-Za-z0-9]+?[\ .A-Za-z0-9]{2,}$%'*/'%.%',$regis_email, $match_email);	//регулярное выражение для email (ОТКЛЮЧЕНО) ($re = '/^[a-z0-9.]{1,30}@[a-z0-9]{1,30}\.[a-z]{2,10}$/mi';)							
	preg_match(/*'%^[A-z\d]{5,}$%'*/'%.%', $regis_user, $match_user);												//регулярное выражение для login  (ОТКЛЮЧЕНО)										
	preg_match(/*'%^[A-z\d]{6,}$%'*/'%.%', $regis_pass, $match_pass);												//регулярное выражение для pass  (ОТКЛЮЧЕНО)
	if( empty($match_email[0]) || empty($match_user[0])  || empty($match_pass[0])){
		to_location('register');
		}
	$chek_login = $mysqli->query("SELECT * FROM `users` WHERE login='{$regis_user}' LIMIT 1"); 								//проверка уникальности Логина
	if($chek_login->num_rows>=1){
		to_location('register?result=1');    //ОШИБКА логин занят
	}
	$sail_pass=saling($regis_pass);
	$write = $mysqli->query("INSERT INTO `users` (`id`, `login`, `password`, `time`, `banned`, `email`) VALUES (NULL, '{$regis_user}', '{$sail_pass}', CURRENT_TIMESTAMP, '1', '{$regis_email}')");
	$send_res = send_mail($regis_email,'Регистрация','Поздравляем вас с успешной регистрацией');
	to_location('/?result='.$send_res);
}
to_location('register?result=2');		//ОШИБКА Пароли не совпадают
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
		$res = $mysqli->query("SELECT * FROM `users` WHERE login='{$authuser}' AND password='{$sail_pass}' LIMIT 1");
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


