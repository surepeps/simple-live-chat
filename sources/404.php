<?php
  header("HTTP/1.0 404 Not Found");
  $sc['description'] = '';
  $sc['keywords']    = '';
  $sc['page']        = '404';
  $sc['title']       = "Page Not Found";
  $sc['content']     = Sh_LoadPage('404/content');
