<?php
if ($sc['loggedin'] == true) {
  header("Location: " . $sc['config']['site_url'].'/dashboard');
  exit();
}

$Lpage = 'content';
$sc['description'] = $sc['config']['siteDesc'];
$sc['keywords']    = $sc['config']['siteKeywords'];
$sc['page']        = 'login';
$sc['title']       = $sc['config']['siteTitle'];
$sc['content']     = Sh_LoadPage('login/'.$Lpage);
