<?php

define('QUAD',true);
require_once 'params.php';
/*
$types = $mysqli->query("SELECT * FROM `type` WHERE 1");
while($type_info=$types->fetch_assoc()){
	$user_types[$type_info['id_type']] = $type_info['value'];
	}
	*/
	
//$res = $mysqli->query("SELECT * FROM `users` WHERE 1");
$res = $mysqli->query("SELECT users.id, users.login, users.email, type.value, banstatus.valueban FROM users, type, banstatus WHERE users.id_type = type.id_type AND banstatus.id_ban = users.banned");

$string="";
while($user_info=$res->fetch_assoc()){

$string.="
<tr>
    <th>{$user_info['id']}</th>
    <td>{$user_info['login']}</td>
    <td>{$user_info['value']}</td>
    <td>{$user_info['email']}</td>
	<td>{$user_info['valueban']}</td>
</tr>";

}

echo <<<XOF
<html>
 <head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <title>Таблица пользователей</title>
  <style type="text/css">
    table {
     border-collapse: collapse; /* Отображать двойные линии как одинарные */
    }
    th {
     background: #ccc; /* Цвет фона */
     text-align: left; /* Выравнивание по левому краю */
    }
    td, th {
     border: 1px solid #800; /* Параметры границы */
     padding: 4px; /* Поля в ячейках */
    } 
  </style>
 </head>
 <body>
 <table width="50%" cellspacing="0" border="1">
   <tr>
    <th>ID</th>
    <th>Login</th>
    <th>Тип пользователя</th>
	<th>Email пользователя</th>
	<th>Бан статус</th>
   </tr>
   {$string}
 </body>
</html>
XOF;

echo <<<XOF
<form action="/index">
<input type="submit" name="logout" value="покинуть это странное место" />
</form>
XOF;
