<?php
declare(strict_types=1);
session_start();
$msg='';
if($_SERVER['REQUEST_METHOD']==='POST'){
  $host=trim($_POST['host']);$dbn=trim($_POST['db']);$user=trim($_POST['user']);$pass=$_POST['pass'];$name=trim($_POST['name']);$email=trim($_POST['email']);$pw=$_POST['password'];
  try{
    $pdo=new PDO("mysql:host=$host;charset=utf8mb4",$user,$pass,[PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION]);
    $sql=file_get_contents(__DIR__.'/../database/playgramblog.sql');$pdo->exec($sql);
    $pdo->prepare("INSERT INTO users(name,email,password_hash,role,status) VALUES(?,?,?,'super_admin','active')")->execute([$name,$email,password_hash($pw,PASSWORD_DEFAULT)]);
    $pdo->prepare("INSERT INTO settings(setting_key,setting_value) VALUES('site_name','PlayGramBlog'),('site_description','A modern blogging platform'),('enable_registration','1'),('enable_comments','1') ON DUPLICATE KEY UPDATE setting_value=VALUES(setting_value)")->execute();
    file_put_contents(__DIR__.'/../config/config.php',"<?php return ".var_export(['db_host'=>$host,'db_name'=>$dbn,'db_user'=>$user,'db_pass'=>$pass,'site_url'=>rtrim($_POST['site_url'],'/'),'app_env'=>'production'],true).";");
    file_put_contents(__DIR__.'/.installed','Installed '.date('c'));
    $msg='Installation complete. Delete or protect the install directory, then open the site.';
  }catch(Throwable $e){$msg='Installation failed: '.htmlspecialchars($e->getMessage(),ENT_QUOTES,'UTF-8');}
}
?><!doctype html><html><head><meta name="viewport" content="width=device-width,initial-scale=1"><link rel="stylesheet" href="../assets/css/app.css"><title>Install PlayGramBlog</title></head><body><main class="wrap" style="max-width:700px;padding:40px 0"><h1>Install PlayGramBlog</h1><p><?=htmlspecialchars($msg)?></p><form class="form" method="post"><label>Site URL</label><input name="site_url" placeholder="https://example.com" required><label>MySQL host</label><input name="host" value="localhost" required><label>Database name</label><input name="db" value="playgramblog" required><label>Database user</label><input name="user" required><label>Database password</label><input type="password" name="pass"><hr><label>First admin name</label><input name="name" required><label>First admin email</label><input type="email" name="email" required><label>Admin password</label><input type="password" name="password" minlength="10" required><button>Install</button></form></main></body></html>