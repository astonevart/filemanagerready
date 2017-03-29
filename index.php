<?php
function sizeFold($dir, $file)
{
    $size = 0;
    $path = $dir . '/' . $file;
    $items = scandir($path);
    foreach ($items as $item) {
        if ($item != '.' && $item != '..') {

            if (is_file($path . '/' . $item)) {
                $size += filesize($path . '/' . $item);
            }
            if (is_dir($path . '/' . $item)) {
                $size += sizeFold($path, $item);
            }

        }
    }
    return $size;
}
function delete($dir, $file)
{
    $path = $dir . '/' . $file;
    if (is_dir($path)) {
        $items = scandir($path);
        foreach ($items as $item) {
            if ($item != '.' && $item != '..') {
                if (is_dir($path . '/' . $item)) {
                    delete($path, $item);
                } else {
                    unlink($path . '/' . $item);
                }
            }
        }
        rmdir($path);
    } else {
        unlink($path);
    }
}
function sortDate($dir){
    $items = array();
    foreach (scandir($dir) as $item) {
        if ($item != '.' && $item != '..') {
            $items[$item] = filemtime("$dir/$item");
        }
    }
    arsort($items);
    $items = array_keys($items);
    return $items;
}


function sortSize($dir){
    $items = array();
    foreach (scandir($dir) as $item) {
        if ($item != '.' && $item != '..') {

            if (is_file("$dir/$item")){
                $items[$item] = filesize("$dir/$item");
            }
            if (is_dir("$dir/$item")){
                $items[$item] = sizeFold($dir,$item);
            }
        }
    }
    arsort($items);
    $items = array_keys($items);
    return $items;
}



if ($_GET['namedelete'] && $_GET['dir']) {
    if (is_file($_GET['dir'] . '/' . $_GET['namedelete']) or is_dir($_GET['dir'] . '/' . $_GET['namedelete']))
        delete($_GET['dir'], $_GET['namedelete']);
}
define("ROOT", dirname(__FILE__));
if (is_uploaded_file($_FILES["filename"]["tmp_name"])) {
    move_uploaded_file($_FILES["filename"]["tmp_name"], $_GET['uploadnamedir'] . '/' . $_FILES['filename']['name']);
}



if ($_GET['dir']) {
    $files = scandir($_GET['dir']);
    $dir = $_GET['dir'];
} else if (isset($_GET['dirsortdate'])) {
    $files = sortDate($_GET['dirsortdate']);
    $dir = $_GET['dirsortdate'];
}
else if  (isset($_GET['dirsortfile'])){
    $files = scandir($_GET['dirsortfile'],1);
    $dir = $_GET['dirsortfile'];
}
else if  (isset($_GET['dirsortsize'])){
    $files = sortSize($_GET['dirsortsize']);
    $dir = $_GET['dirsortsize'];
}
else{
    $dir = ROOT . '/uploads';
    $files = scandir($dir);

}

if ($dir != ROOT . '/uploads/') {
    $back = substr($dir, 0, strrpos($dir, "/"));
    echo "<a href='index.php?dir=$back' style='padding: 10px; cursor: pointer; display: inline-block; background: lemonchiffon;
 border: 1px solid'>наверх</a>";
}
echo "<table style='border: 1px solid; padding:10px; background: gray; margin-bottom: 10px'>";
echo "<tr><th></th><th><a href='index.php?dirsortfile=$dir' style='color: black; text-decoration: none; border: 1px solid'>Файлы &uarr; &darr;</a></th><th>Права</th><th><a href='index.php?dirsortsize=$dir' style='color: black; text-decoration: none; border: 1px solid'>Размер &uarr; 	&darr;</a></th><th><a href='index.php?dirsortdate=$dir' style='color: black; text-decoration: none; border: 1px solid'>Дата создания &uarr; 	&darr;</a></th></tr>";
foreach ($files as $file) {
    if ($file != '.' && $file != '..') {
        $root = substr(sprintf('%o', fileperms($dir . '/' . $file)), -4);
        if (is_dir($dir . '/' . $file)) {
            $directory = $dir . '/' . $file;
            /*ссылка на удаление*/
            echo "<tr><td style='border: 1px solid; padding: 5px; background: lightgrey; cursor: pointer'><a href='index.php?namedelete=$file&dir=$dir'
 style='text-decoration: none; color: chocolate; float: right;'>x</a></td>";
            /*ссылка на папки*/
            echo "<td style='border: 1px solid; padding: 5px; background: lightgrey'><a href='index.php?dir=$directory'
 style='text-decoration: none; color: darkslategray; display: inline-block; background: goldenrod; padding-left: 3px; padding-right: 3px'>{$file}</a></td>";
            /*ссылка на права доступа*/
            echo "<td style='border: 1px solid; padding: 5px; background: lightgrey'><a href='root.php?name=$file&dir=$dir'
 style='text-decoration: none; color: chocolate'>$root</a></td>";
            /*размер*/
            $size = sizeFold($dir, $file)/1024;
            $sizeFormat = number_format($size, 2, '.', '');
            echo "<td style='border: 1px solid; padding: 5px; background: lightgrey'>{$sizeFormat} КБ</td>";
            /*Дата создания*/
            $date = date("F d Y H:i:s.", filectime($dir . '/' . $file));
            echo "<td style='border: 1px solid; padding: 5px; background: lightgrey'>$date</td></tr>";
        } else {
            /*ссылка на удаление*/
            echo "<tr><td style='border: 1px solid; padding: 5px; background: lightgrey; cursor: pointer'><a href='index.php?namedelete=$file&dir=$dir'
 style='text-decoration: none; color: chocolate; float: right'>x</a></td>";
            /*ссылка на файлы*/
            echo "<td style='border: 1px solid; padding: 5px; background: lightsteelblue'><a href='openfile.php?name=$file&dir=$dir'
 style='text-decoration: none; color: teal'>{$file}</a></td>";
            /*ссылка на права доступа*/
            echo "<td style='border: 1px solid; padding: 5px; background: lightgrey'><a href='root.php?name=$file&dir=$dir'
 style='text-decoration: none; color: chocolate'>$root</a></td>";
            /*Размер*/
            $size = filesize($dir . '/' . $file)/1024;
            $sizeFormat = number_format($size, 2, '.', '');
            echo "<td style='border: 1px solid; padding: 5px; background: lightgrey'>{$sizeFormat} КБ</td>";
            /*Дата создания*/
            $date = date("F d Y H:i:s.", filectime($dir . '/' . $file));
            echo "<td style='border: 1px solid; padding: 5px; background: lightgrey'>$date</td></tr>";
        }
    }
}
echo "</table>";
echo "<form action=index.php?dir=$dir&uploadnamedir=$dir method=post enctype=multipart/form-data>
<input type=file name=filename>
<input type=submit value=Загрузить></form>";