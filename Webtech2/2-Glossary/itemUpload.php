<?php
require_once 'config.php';
require_once 'Word.php';
require_once 'Translation.php';
require_once 'MyPdo.php';
/**
 * @var $host
 * @var $db
 * @var $user
 * @var $password
 */

$dsn = "mysql:host=$host;dbname=$db";
$myPdo = new MyPDO($dsn, $user, $password);

if(!empty($_POST['word']) and !empty($_POST['desc']) and
    !empty($_POST['translated-word']) and !empty($_POST['translated-desc'])){
    $word = new Word($myPdo);
    $word->setTitle($_POST['word']);
    $word->save();

    $slovakTranslation = new Translation($myPdo);
    $slovakTranslation->setTitle($_POST['word']);
    $slovakTranslation->setDescription($_POST['desc']);
    $slovakTranslation->setLanguageId(1);
    $slovakTranslation->setWordId($word->getId());
    $slovakTranslation->save();

    $englishTranslation = new Translation($myPdo);
    $englishTranslation->setTitle($_POST['translated-word']);
    $englishTranslation->setDescription($_POST['translated-desc']);
    $englishTranslation->setLanguageId(2);
    $englishTranslation->setWordId($word->getId());
    $englishTranslation->save();
}
header("Location: index.php");

