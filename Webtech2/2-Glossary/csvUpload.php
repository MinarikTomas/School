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

if(is_uploaded_file($_FILES['file']['tmp_name'])){
    $file = fopen($_FILES['file']['tmp_name'], 'r');
    while(!feof($file)){
        $csvData = fgetcsv($file, null, ";");
        if ($csvData[0]){
            $word = new Word($myPdo);
            $word->setTitle($csvData[2]);
            $word->save();

            $slovakTranslation = new Translation($myPdo);
            $slovakTranslation->setTitle($csvData[2]);
            $slovakTranslation->setDescription($csvData[3]);
            $slovakTranslation->setLanguageId(1);
            $slovakTranslation->setWordId($word->getId());
            $slovakTranslation->save();

            $englishTranslation = new Translation($myPdo);
            $englishTranslation->setTitle($csvData[0]);
            $englishTranslation->setDescription($csvData[1]);
            $englishTranslation->setLanguageId(2);
            $englishTranslation->setWordId($word->getId());
            $englishTranslation->save();
        }
    }
}
header("Location: index.php");
