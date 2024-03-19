 <?php
$total_data="st/baz.dat";
$real_data="st/real.dat";
$time=time();
$now=(int)(time()/86400);
$past_time=time()-10;

$readdata=fopen($real_data,"r") or die("Не могу открыть файл $real_data");
$real_data_array=file($real_data);
fclose($readdata);

if(getenv('HTTP_X_FORWARDED_FOR'))
        $user=getenv('HTTP_X_FORWARDED_FOR');
else
        $user=getenv('REMOTE_ADDR');

$d=count($real_data_array);
for($i=0;$i<$d;$i++)
        {
        list($live_user,$last_time)=explode("::","$real_data_array[$i]");
        if($live_user!=""&&$last_time!=""):
        if($last_time<$past_time):
                $live_user="";
                $last_time="";
        endif;
        if($live_user!=""&&$last_time!="")
                {
                if($user==$live_user)
                        {
                        $real_array[]="$user::$time\r\n";
                        }
                else
                        $real_array[]="$live_user::$last_time";
                }
        endif;
        }

        if(isset($real_array)):
        foreach($real_array as $i=>$str)
                {
                if($str=="$user::$time\r\n")
                        {
                        $ok=$i;
                        break;
                        }
                }
        foreach($real_array as $j=>$str)
                {
                if($ok==$j) { $real_array[$ok]="$user::$time\r\n"; break;}
                }
       endif;

$writedata=fopen($real_data,"w") or die("Не могу открыть файл $real_data");
flock($writedata,2);
if($real_array=="") $real_array[]="$user::$time\r\n";
foreach($real_array as $str)
        fputs($writedata,"$str");
flock($writedata,3);
fclose($writedata);

$readdata=fopen($real_data,"r") or die("Не могу открыть файл $real_data");
$real_data_array=file($real_data);
fclose($readdata);
$real=count($real_data_array);

$f=fopen($total_data,"a");
$call="$user|$now\n";
$call_size=strlen($call);
flock($f,2);
fputs($f, $call,$call_size);
flock($f,3);
fclose($f);

$tarray=file($total_data);
$total_hits=count($tarray);

$today_hits_array=array();
for($i=0;$i<count($tarray);$i++)
        {
        list($ip,$t)=explode("|",$tarray[$i]);
        if($now==$t) { array_push($today_hits_array,$ip); }
        }
$today_hits=count($today_hits_array);

$total_hosts_array=array();
for($i=0;$i<count($tarray);$i++)
        {
        list($ip,$t)=explode("|",$tarray[$i]);
        array_push($total_hosts_array,$ip);
        }
$total_hosts=count(array_unique($total_hosts_array  ));

$today_hosts_array=array();
for($i=0;$i<count($tarray);$i++)
        {
        list($ip,$t)=explode("|",$tarray[$i]);
        if($now==$t) { array_push($today_hosts_array,$ip); }
        }
$today_hosts=count(array_unique($today_hosts_array  ));



   $content = "<div class=\"link\">⤚ Посетителей всего: $total_hits</div> <style>.main {color: red}</style> ";
	echo $content;

   $content = "<div class=\"link\">⤚ Посетителей сегодня: $today_hits</div> <style>.main {color: red}</style> ";
	echo $content;

   $content = "<div class=\"link\">⤚ Переходов всего: $total_hosts</div> <style>.main {color: red}</style> ";
	echo $content;
	
   $content = "<div class=\"link\">⤚ Переходов сегодня: $today_hosts</div> <style>.main {color: red}</style> ";
	echo $content;

	$content = "<div class=\"link\">⤚ Сейчас на сайте: $real</div> <style>.main {color: red}</style> ";
	echo $content;


?> 