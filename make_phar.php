<?php

if (ini_get('phar.readonly')) {
   echo "You must set the php.ini option 'phar.readonly = 0' to enable phar creation";
   die(); 
}

$filename = 'Morph.phar';
$base_dir = dirname(__FILE__);
$src_dir = $base_dir . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'morph' . DIRECTORY_SEPARATOR;
$metadata = array(
    'Author' => 'Jonathan Moss <xirisr@gmail.com>',
    'Decription' => 'The Morph package provides a high level framework for working with MongoDB',
    'Copyright' => 'Jonathan Moss (c) 2008-' . date('Y'),
    'Build' => time(),
    'Created Date' => date('Y-m-d H:I:S'),
);

if (file_exists($filename)) {
    echo "Deleteing the old phar file\n";
    unlink($filename);
}

echo "Creating phar file...\n";
$phar = new Phar($filename, 0, 'Morph');
$phar->buildFromDirectory($src_dir);
$phar->setStub(file_get_contents($src_dir . '..' . DIRECTORY_SEPARATOR . 'bootstrap.php'));
$phar->setMetadata($metadata);
$phar->compressFiles(Phar::GZ);
echo "All done!\n";