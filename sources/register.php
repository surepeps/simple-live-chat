<?php
if ($sc['loggedin'] == true) {
  header("Location: " . $sc['config']['site_url'].'/dashboard');
  exit();
}

$Lpage = 'register';
$sc['description'] = $sc['config']['siteDesc'];
$sc['keywords']    = $sc['config']['siteKeywords'];
$sc['page']        = 'register';
$sc['title']       = $sc['config']['siteTitle'];
$sc['content']     = Sh_LoadPage('login/'.$Lpage);
