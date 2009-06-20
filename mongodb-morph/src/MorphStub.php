<?php
Phar::mapPhar();

//handle running from command line
if(isset($argc) && ($argc > 1)){
    $command = strtolower($argv[1]);
    switch($command){
        case '-?':
        case '-h':
            showInfo();
            break;
        case '-l':
            showInfo(true);
            break;
        case '-v':
            showVersion();
            break;
    }
    die();
}

/**
 * Simple autoloader for the morph package
 *
 * @param string $className
 * @return boolean
 */
function morphAutoloader($className)
{
    $included = false;
    $path = 'phar://' . str_replace('_', '/', $className) . '.php';
    if (file_exists($path)) {
        require $path;
        $included = true;
    }
    return $included;
}

//register the autoloader
spl_autoload_register('morphAutoloader');

/**
 * Displays the build number
 *
 * @return void
 */
function showVersion()
{
    $p = new Phar('phar://'.__FILE__);
    $m = $p->getMetadata();
    fwrite(STDOUT, "Morph Build: " . $m['Build'] . "\n");
}

/**
 * Displays the phar file metadata and optionally a file list
 *
 * @param boolean $includeFileList
 * @return void
 */
function showInfo($includeFileList = false)
{
    $p = new Phar('phar://'.__FILE__);
    fwrite(STDOUT, "Morph Library\n-------------\n");
    foreach ($p->getMetadata() as $field => $value){
        $field = str_pad($field, 16, ' ', STR_PAD_RIGHT);
        fwrite(STDOUT, "$field: $value\n");
    }

    if ($includeFileList === true) {
        fwrite(STDOUT, "\nFile List\n---------\n");
        $i = new RecursiveIteratorIterator($p);
        $baseName = 'phar://' . __FILE__ . '/';
        foreach($i as $file){
            $name = $file->getPath() .'/'. $file->getFilename();
            $name = str_replace($baseName, '', $name);
            fwrite(STDOUT, "$name\n");
        }
    }

    fwrite(STDOUT, "\n");

}

__HALT_COMPILER();