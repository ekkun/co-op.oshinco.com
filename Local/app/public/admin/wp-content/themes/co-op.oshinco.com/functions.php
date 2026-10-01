<?php
$files = glob(dirname(__FILE__) . '/functions/*.php');
sort($files); // アルファベット順にソート

foreach ($files as $file) {
  require_once $file;
}
