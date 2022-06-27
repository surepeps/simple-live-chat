<?php
if ($sc['loggedin'] == false) {
  header("Location: " . $sc['config']['site_url'].'/login');
  exit();
}

$Lpage = 'content';
$sc['description'] = $sc['config']['siteDesc'];
$sc['keywords']    = $sc['config']['siteKeywords'];
$sc['page']        = 'dashboard';
$sc['title']       = $sc['config']['siteTitle'];
$sc['content']     = Sh_LoadPage('dashboard/'.$Lpage);
