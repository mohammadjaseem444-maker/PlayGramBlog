<?php
require_once __DIR__.'/../config/bootstrap.php';
$title=$title??setting('site_name','PlayGramBlog'); $desc=$description??setting('site_description','A modern blogging platform.');
?>
<!doctype html><html lang="en"><head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title><?=e($title)?></title><meta name="description" content="<?=e($desc)?>">
<link rel="canonical" href="<?=e(site_url($_SERVER['REQUEST_URI']??''))?>">
<meta property="og:title" content="<?=e($title)?>"><meta property="og:description" content="<?=e($desc)?>">
<link rel="stylesheet" href="<?=e(site_url('assets/css/app.css'))?>">
</head><body class="<?=isset($_SESSION['dark'])?'dark':''?>">
<header class="site-head"><div class="wrap nav"><a class="brand" href="<?=e(site_url())?>">PlayGramBlog</a>
<nav><a href="<?=e(site_url())?>">Home</a><a href="posts.php">Latest</a><a href="category.php">Categories</a><a href="search.php">Search</a><a href="contact.php">Contact</a></nav>
<button class="theme" id="theme">☾</button><a class="account" href="login.php"><?=current_user()?'Account':'Login'?></a></div></header><main class="wrap">
<?php show_flash(); ?>
