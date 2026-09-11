<?php
function login_user(array $u): void {
    session_regenerate_id(true);
    $_SESSION['user']=['id'=>(int)$u['id'],'name'=>$u['name'],'email'=>$u['email'],'role'=>$u['role']];
}
function require_login(): void { if(empty($_SESSION['user'])) redirect('login.php'); }
function require_admin(): void {
    if(empty($_SESSION['user']) || !in_array($_SESSION['user']['role'],['super_admin','admin','editor','author'],true)) {
        redirect('login.php');
    }
}
function can_manage(string $area): bool {
    $r=$_SESSION['user']['role']??'';
    if($r==='super_admin') return true;
    $map=['posts'=>['admin','editor','author'],'categories'=>['admin','editor'],'tags'=>['admin','editor'],'comments'=>['admin','editor'],'media'=>['admin'],'pages'=>['admin'],'menus'=>['admin'],'ads'=>['admin'],'users'=>['admin'],'settings'=>['admin'],'analytics'=>['admin'],'subscribers'=>['admin'],'messages'=>['admin']];
    return in_array($r,$map[$area]??[],true);
}
