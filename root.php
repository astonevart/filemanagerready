<?php
if ($_POST['root']) {
    $sumroot = array_sum($_POST['root']);
    switch ($sumroot) {
        case 1:
            chmod($_GET['dir'].'/'.$_GET['name'], 0776);
            break;
        case 4:
            chmod($_GET['dir'].'/'.$_GET['name'], 0773);
            break;
        case 2:
            chmod($_GET['dir'].'/'.$_GET['name'], 0775);
            break;
        case 7:
            chmod($_GET['dir'].'/'.$_GET['name'], 0770);
            break;
        case 5:
            chmod($_GET['dir'].'/'.$_GET['name'], 0772);
            break;
        case 3:
            chmod($_GET['dir'].'/'.$_GET['name'], 0774);
            break;
        case 6:
            chmod($_GET['dir'].'/'.$_GET['name'], 0771);
            break;
    }
    header ("Location: index.php");
}
if ($_GET['name'] && $_GET['dir']) : ?>
    <form action='root.php?name=<?= $_GET['name'] ?>&dir=<?= $_GET['dir'] ?>' method='post'>
        <p>Запретить :</p>
        <p><input type="checkbox" name="root[]" value="1">Выполнение</p>
        <p><input type="checkbox" name="root[]" value="4">Чтение</p>
        <p><input type="checkbox" name="root[]" value="2">Запись</p>
        <p><input type="submit" value="Отправить"></p>
    </form>
<? endif ?>
