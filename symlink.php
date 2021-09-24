<?php

$targetFolder = $_SERVER['DOCUMENT_ROOT'].'/storage/app/public';
$linkFolder = $_SERVER['DOCUMENT_ROOT'].'/public/storage';
symlink($targetFolder,$linkFolder);
print('Symlink process successfully completed');

?>