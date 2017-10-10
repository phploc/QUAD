<?php
$s=0;
$v=0;
$n=10000;

for($i=1;$i<=$n;$i++){
$avto=rand(1,3);
$chos=rand(1,3);
if($avto+$chos==2)
	$del=rand(2,3);
if($avto+$chos==3)
	$del=3;
if($avto+$chos==5)
	$del=1;
if($avto+$chos==6)
	$del=rand(1,2);
if($avto+$chos==4){
	if($avto==2)
		$del=(rand(1,2)*2)-1;
	else
		$del=2;
}		
	
if($avto==$chos)
$s++;	
else
$v++;

}
$win=$s/$n*100;
$winch=$v/$n*100;
echo 'это программа на парадокс монтихолла, что ты тут забыл? <br>';
echo $avto, $chos, $del;
echo '<br> wins(no change) ',$s/$n*100,' times';
echo '<br> wins(change) ',$v/$n*100,' times';
$winr[0]='wins(no change)';
$winr[1]=$win;
$winr[2]=' times/';
$winchr[0]='wins(change)';
$winchr[1]=$winch;
$winchr[2]=' times|';
$b=file_put_contents("monty.txt",$winr,FILE_APPEND);
$b2=file_put_contents("monty.txt",$winchr,FILE_APPEND);
echo $b,"	",$b2;