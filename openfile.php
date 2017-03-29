<?php
if (isset($_GET['name'])){
$filepath =  $_GET['dir'].'/'.$_GET['name'];
$file = file_get_contents($filepath);
    echo "<form action='openfile.php?path=$filepath&dir={$_GET['dir']}' method='post'>";
    echo "<input type='text' name='rename' value='{$_GET['name']}' style='color: darkolivegreen; display: block; background: mediumaquamarine; padding: 10px '><br>";
    echo "<textarea name='file' style='display: block; height: 100%; width: 100%; background: lightyellow'>$file</textarea></p>";
    echo "<input type='submit' value='Сохранить' name ='save' style='padding: 10px; float: right; margin-bottom: 10px'>";
    echo "<input type='submit' value='Назад' name ='back' style='padding: 10px; margin-left: 10px; float: right; margin-bottom: 10px'></form>";
}
if ($_POST['save']){
        file_put_contents($_GET['path'], $_POST['file']);
        rename($_GET['path'],$_GET['dir'].'/'.$_POST['rename']);
        header ("Location: index.php?dir={$_GET['dir']}");
}
if ($_POST['back']){
    header ("Location: index.php?dir={$_GET['dir']}");
}







