<?php
if (!defined('DS')) {
    define('DS', DIRECTORY_SEPARATOR);
}

$library = "PHPMailer";

$filename = dirname(__FILE__) . DS . 'compiled' . DS . $library;

require_once implode(DS,array(dirname(__FILE__) , 'libs')) . DS . 'class.phpmailer.php';

if(!class_exists('PHPMailer',false)){
    throw new Exception(__LINE__ . " : error");
}
$x = new PHPMailer();

if (!defined('__PHAR__VERSION__')) {
    define('__PHAR__VERSION__', $x->Version);
}

unset($x);


if (!is_dir(dirname(__FILE__) . DS . 'compiled')) {
    if (!mkdir(dirname(__FILE__) . DS . 'compiled')) {
        throw new Exception('Cant Create Folder');
    };
}

/**
 * Remove Previous Compiled Archives
 */
if (is_readable($filename)) {
    unlink($filename);
}

$archive = new Phar($filename . '.phar', 0, ucwords($library));
$archive->buildFromDirectory('libs');
$bootstrap = file_get_contents(dirname(__FILE__) . DS . 'phar-bootstrap.php');
$archive->setStub($bootstrap);
$archive = null;
unset($archive);

if ((defined('__PHAR__VERSION__')) && (__PHAR__VERSION__)) {
    file_put_contents($filename . '-' . __PHAR__VERSION__ . '.phar', file_get_contents($filename . '.phar'));
}


/**
 * Build Compressed Versions
 */
if (extension_loaded('zlib')) {
    //Create GZ Archive, That will use Phar's Stub
    if (function_exists('gzopen')) {
        if (is_readable($filename . '.gz')) {
            unlink($filename . '.gz');
        }
        $gz = gzopen($filename . '.gz', 'w9');
        gzwrite($gz, file_get_contents($filename . '.phar'));
        gzclose($gz);
        if ((defined('__PHAR__VERSION__')) && (__PHAR__VERSION__)) {
            file_put_contents($filename . '-' . __PHAR__VERSION__ . '.gz', file_get_contents($filename . '.gz'));
        }
    }
}

if (extension_loaded('bz2')) {
    //Create BZ2 Archive, That will use Phar's Stub
    if (function_exists('bzopen')) {
        if (is_readable($filename . '.bz2')) {
            unlink($filename . '.bz2');
        }
        $bz2 = bzopen($filename . '.bz2', 'w');
        bzwrite($bz2, bzcompress(file_get_contents($filename . '.phar'), 9));
        bzclose($bz2);
        if ((defined('__PHAR__VERSION__')) && (__PHAR__VERSION__)) {
            file_put_contents($filename . '-' . __PHAR__VERSION__ . '.bz2', file_get_contents($filename . '.bz2'));
        }
    }
} else {
    //
}