<?php
/**
 * @var string $uploadPath
 */
?>

<?php
    require 'config.php';
    if (isset($_GET['path'])){
        $path = $_GET['path'];
        $pathSplit = (explode('/', $path));
        if(strcmp($pathSplit[count($pathSplit) -2], "..") == 0){
            //check if current dir is uploads
            if(count($pathSplit) == 3){
                $path = $uploadPath;
            }else{
                //remove empty string, .. and last dir from path
                $pathSplit = array_splice($pathSplit, 0, -3);
                $path = implode("/", $pathSplit) . "/";
            }
        //. always set path to uploads dir
        }elseif (strcmp($pathSplit[count($pathSplit) -2], ".") == 0){
            $path = $uploadPath;
        }
    }else{
        $path = $uploadPath;
    }
?>

<!doctype html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>File upload</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sortable/0.8.0/js/sortable.min.js" integrity="sha512-DEcSaL0BWApJ//v7ZfqAI04nvK+NQcUVwrrx/l1x7OJgU0Cwbq7e459NBMzLPrm8eLPzAwBtiJJS4AvLZDZ8xA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sortable/0.8.0/css/sortable-theme-bootstrap.css" integrity="sha512-ejAo3nK8bdfwg68A9g6QYUdqnTmcGem1OX8AeVwa+dQH2v22vEwPkbZQzltTE+bjXt72iGvglAw0h+Up+fOg0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
<header>
    <h1>Zadanie 1</h1>
</header>
<div class="table-container">
    <table class="sortable-theme-slick" data-sortable>
        <thead>
        <tr>
            <th>Názov</th>
            <th>Veľkosť</th>
            <th>Dátum</th>
        </tr>
        </thead>
        <tbody>
        <?php
        foreach (scandir($path) as $file)
        {
            ?>
            <tr>
                <?php
                    if(is_dir($path.$file)){
                        echo "<td><a href='?path=$path$file/'>$file</a></td>";
                        echo "<td></td>";
                        echo "<td></td>";
                    }else{
                        $fileTimestamp = filemtime($path.$file);
                        echo "<td>".str_replace( '('.$fileTimestamp.')', '', $file)."</td>";
                        echo "<td>".filesize($path.$file)."</td>";
                        echo "<td>".date("d.m.Y h:i:s", $fileTimestamp)."</td>";
                    }
                ?>
            </tr>
            <?php
        }
        ?>

        </tbody>
    </table>
</div>
<div class="form-container">
    <form action="upload.php" method="post" enctype="multipart/form-data">
        <div class="row">
            <label for="title">Názov súboru</label>
            <input type="text" id="title" name="title">
        </div>
        <div class="row">
            <label for="file">Vyberte súbor</label>
            <input type="file" id="file" name="fileToUpload">
        </div>
        <div class="row">
            <button type="submit">Submit</button>
        </div>
    </form>
</div>
</body>
</html>