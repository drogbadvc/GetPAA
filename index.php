<?php
require('vendor/autoload.php');

use Cocur\Slugify\Slugify;
use csrfhandler\csrf;
use Plasticbrain\FlashMessages\FlashMessages;

// Csrf for only post/get requests
$isValid = csrf::post();
$GetValid = csrf::get();
$token = csrf::token();
// Rewrite directory with slugify
$slugify = new Slugify();
// Message Flash for errors
$msg = new FlashMessages();

Storage::session();
$paa = new Paa($slugify, $msg);

if ($isValid) {
    [$keyword, $paa_record, $depth, $lang] = $paa->inputKeywordsPost();
    $slug_csv = $slugify->slugify($keyword);
} else {
    $paa->renderError('Oops, Token invalide :(');
}

if (isset($_GET['export']) && $_GET['export'] === 'csv') {
    if ($GetValid) {
        $paa->exportCSV();
    }
}
?>

<?php require_once 'header.php' ?>
<?php require_once 'contain.php' ?>