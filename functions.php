<?php
function e($v): string { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }
function redirect(string $url): never { header("Location: $url"); exit; }
function site_url(string $path=''): string {
    global $config;
    return rtrim($config['site_url'] ?: '', '/') . '/' . ltrim($path, '/');
}
function setting(string $key, $default='') {
    static $cache = null;
    if ($cache === null) {
        $cache = [];
        try { foreach (db()->query("SELECT setting_key, setting_value FROM settings") as $r) $cache[$r['setting_key']] = $r['setting_value']; }
        catch(Throwable $e) {}
    }
    return $cache[$key] ?? $default;
}
function slugify(string $s): string {
    $s = trim(mb_strtolower($s));
    $s = preg_replace('/[^\pL\pN]+/u', '-', $s);
    return trim($s, '-') ?: 'post';
}
function unique_slug(string $slug, ?int $id=null): string {
    $base=$slug; $n=1;
    while (true) {
        $sql="SELECT id FROM posts WHERE slug=?".($id ? " AND id<>?" : "");
        $st=db()->prepare($sql); $id ? $st->execute([$slug,$id]) : $st->execute([$slug]);
        if (!$st->fetch()) return $slug;
        $slug=$base.'-'.$n++;
    }
}
function excerpt(string $html, int $len=180): string {
    $t=trim(preg_replace('/\s+/', ' ', strip_tags($html)));
    return mb_strlen($t)>$len ? mb_substr($t,0,$len).'…' : $t;
}
function reading_time(string $html): int { return max(1,(int)ceil(str_word_count(strip_tags($html))/200)); }
function is_post_published(array $p): bool {
    return $p['status']==='published' && strtotime($p['published_at']) <= time();
}
function pagination(int $page,int $per,int $total,string $base): string {
    $pages=max(1,(int)ceil($total/$per)); if($pages<=1)return '';
    $out='<nav class="pagination" aria-label="Pagination">';
    for($i=1;$i<=$pages;$i++) $out .= $i===$page ? '<strong>'.$i.'</strong>' : '<a href="'.e($base.$i).'">'.$i.'</a>';
    return $out.'</nav>';
}
function flash(string $type,string $msg): void { $_SESSION['_flash'][$type]=$msg; }
function show_flash(): void {
    foreach ($_SESSION['_flash'] ?? [] as $t=>$m) echo '<div class="flash '.e($t).'">'.e($m).'</div>';
    unset($_SESSION['_flash']);
}
function log_activity(int $uid,string $action,string $entity='',?int $entityId=null): void {
    $st=db()->prepare("INSERT INTO activity_logs(user_id,action,entity,entity_id,ip_address,user_agent) VALUES(?,?,?,?,?,?)");
    $st->execute([$uid,$action,$entity,$entityId,$_SERVER['REMOTE_ADDR']??'',substr($_SERVER['HTTP_USER_AGENT']??'',0,255)]);
}
function current_user(): ?array { return $_SESSION['user'] ?? null; }
function require_post_admin(): void { require_admin(); if(!in_array($_SESSION['user']['role'],['super_admin','admin','editor','author'],true)) exit('Forbidden'); }
