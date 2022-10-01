<?php
/**
 * @var string $uploadPath
 */
?>

<?php
require 'config.php';
if (isset($_POST['title'])){
    $filename = $_POST['title'] . '(' .time() . ')' . '.' . pathinfo($_FILES['fileToUpload']['name'])['extension'];
    move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $uploadPath . $filename);
    header("Location: index.php");
    exit;
}
