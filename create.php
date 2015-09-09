<?php
$MAXPROCESS=10;
function deldir($dir){
  $dh=opendir($dir);

  while ($file=readdir($dh)) {

    if($file!="." && $file!="..") {

      $fullpath=$dir."/".$file;

      if(!is_dir($fullpath)) {

          unlink($fullpath);

      } else {

          deldir($fullpath);

      }

    }

  }

 

  closedir($dh);

  if(rmdir($dir)) {

    return true;

  } else {

    return false;

  }

}

function pstatus($pid){
    $command = 'ps -p '.$pid;
    exec($command,$op);
    if (!isset($op[1]))return false;
    else return true;
}
function dcopy($source, $destination){   
　　if(!is_dir($source)){   
　　　　echo("Error:the".$source."is not a directory!");   
　　　　return 0;   
　　}   


　　if(!is_dir($destination)){   
　　　　mkdir($destination,0777);  
　　} else return 0;
 
　　$handle=dir($source);   
　　while($entry=$handle->read()) {   
　　　　if(($entry!=".")&&($entry!="..")){   
　　　　　　if(is_dir($source."/".$entry)){      
　　　　　　　　dcopy($source."/".$entry,$destination."/".$entry);   
　　　　　　}   
　　　　　　else{   
　　　　　　　　copy($source."/".$entry,$destination."/".$entry);   
　　　　　　}   
　　　　}   
　　}   
 
　　return 1;   
}  
function delete_old_process($link){
    $ret=sqlquery('SELECT * FROM `process` where 1',$link);
    while ($i = $ret->fetch(PDO::FETCH_ASSOC)){
    if (!pstatus($i['pid'])){
        sqlexec('DELETE FROM `process` where pid=?',array($i['pid']),$link);
        deldir('qqbot/'.$i['id']);
    }
    }
}
require_once('function/sqllink.php');
if(!isset($_POST['type'])) die('{"retcode":999,"msg":"INCOMPLETE POST DATA"}');
$link=sqllink();
if(!$link) die('{"retcode":999,"reason":"DATABASE ERROR"}');
delete_old_process($link);
$res=sqlquery('SELECT count(*) FROM `process`',$link);
$result=$res->fetch(PDO::FETCH_NUM);
if((int)($result[0])>$MAXPROCESS) die('{"retcode":9,"msg":"TOO MANY simultaneous process. Try again later!"}');;

if(!$link->beginTransaction()) die('{"retcode":999,"reason":"DATABASE ERROR"}');
$res=sqlquery('SELECT max(`id`) FROM `process`',$link);
$result=$res->fetch(PDO::FETCH_NUM);
$maxnum=($result==FALSE)?0:(int)($result[0]);
$newid=$maxnum+1;

$type="";
switch ($_POST['type']) {
    case 'qzoneliker':
        if (dcopy('qqbot/qzoneliker', 'qqbot/'.$newid)==0) {$link->rollBack(); die('{"retcode":999,"msg":"UNABLE TO CREATE FOLDER!"}');}
        $type='qzoneliker';
        break;
    case 'qqrobot':
        if (dcopy('qqbot/qqrobot', 'qqbot/'.$newid)==0) {$link->rollBack(); die('{"retcode":999,"msg":"UNABLE TO CREATE FOLDER!"}');}
        $myfile = fopen('qqbot/'.$newid."/groupfollow.txt", "w") or {$link->rollBack(); deldir('qqbot/'.$newid); die('{"retcode":996,"msg":"UNABLE TO CREATE GROUPFOLLOW.TXT!"}');}
        fwrite($myfile, $_POST['groups']);
        fclose($myfile);
        $type='qqrobot';
        break;
    case 'qqparking':
        if (dcopy('qqbot/qqrobot', 'qqbot/'.$newid)==0) {$link->rollBack(); die('{"retcode":999,"msg":"UNABLE TO CREATE FOLDER!"}');}
        $myfile = fopen('qqbot/'.$newid."/config.txt", "w") or {$link->rollBack(); deldir('qqbot/'.$newid); die('{"retcode":996,"msg":"UNABLE TO CREATE GROUPFOLLOW.TXT!"}');}
        fwrite($myfile, $_POST['email']."\n");
        fwrite($myfile, $_POST['welcome']."\n");
        fclose($myfile);
        $type='qqparking';
        break;
    default:
        $link->rollBack();
        die('{"retcode":996,"msg":"UNKNOWN TYPE SUBMITTED!"}');
        break;
}

$command = 'nohup python2 qqbot/'.$newid.'/qqbot.py > /dev/null 2>&1 & echo $!';
exec($command ,$op);
$pid = (int)$op[0];
sqlexec('INSERT INTO `process` VALUES (?,?,?)',array($newid,$pid,$type),$link);
$link->commit();
die('{"retcode":0,"reason":"SUCCESS","id":"'.$newid.'"}');
?>