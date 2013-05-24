<?php
session_start();

ob_start();
include "./common.php";

$error = '';
moderator_access();

if (!check_access()){
    exit;
}

foreach (@$_POST as $key => $value){
    $_POST[$key] = trim($value);
}

$id = @intval($_GET['id']);

$ad = new VclubAdvertising();

if (!empty($_POST['add'])){

    $ad->add($_POST);

    header("Location: vclub_ad.php");
    exit;
}else if (!empty($_POST['edit']) && $id){

    $ad->updateById($id, $_POST);

    header("Location: vclub_ad.php");
    exit;
}else if (!empty($_GET['del']) && $id){

    $ad->delById($id);

    header("Location: vclub_ad.php");
    exit;
}

if (!empty($_GET['edit']) && !empty($id)){

    $current_ad = $ad->getById($id);
}

$ads = $ad->getAllWithStatForMonth();

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<style type="text/css">

body {
    font-family: Arial, Helvetica, sans-serif;
    font-weight: bold;
}
td {
    font-family: Arial, Helvetica, sans-serif;
    font-size: 14px;
    text-decoration: none;
    color: #000000;
}
.list, .list td, .form{
    border-width: 1px;
    border-style: solid;
    border-color: #E5E5E5;
}
a{
	color:#0000FF;
	font-weight: bold;
	text-decoration:none;
}
a:link,a:visited {
	color:#5588FF;
	font-weight: bold;
}
a:hover{
	color:#0000FF;
	font-weight: bold;
	text-decoration:underline;
}
</style>
<title><?=_('Video club advertising')?></title>
</head>
<body>
<table align="center" border="0" cellpadding="0" cellspacing="0">
<tr>
    <td align="center" valign="middle" width="100%" bgcolor="#88BBFF">
    <font size="5px" color="White"><b>&nbsp;<?=_('Video club advertising')?>&nbsp;</b></font>
    </td>
</tr>
<tr>
    <td width="100%" align="left" valign="bottom">
        <a href="index.php"><< <?=_('Back')?></a>
    </td>
</tr>
<tr>
    <td align="center">
    <font color="Red">
    <strong>
    <? echo $error?>
    </strong>
    </font>
    <br>
    <br>
    </td>
</tr>
<tr>
<td align="center">
    <table class='list' cellpadding='3' cellspacing='0'>
        <tr>
            <td><?= _('Title')?></td>
            <td>Video URL</td>
            <td><?= _('Must watch')?></td>
            <td style="text-align: center"><?= _('Watch started')?><br>(<?=_('in the last 30 days')?>)</td>
            <td style="text-align: center"><?= _('Watch ended')?><br>(<?=_('in the last 30 days')?>)</td>
            <td>&nbsp;</td>
        </tr>
        <?
        foreach ($ads as $ad){
            echo '<tr>';
            echo '<td>'.$ad['title'].'</td>';
            echo '<td>'.$ad['url'].'</td>';
            echo '<td>'.($ad['must_watch']=='all' ? _('All') : $ad['must_watch'].'%').'</td>';
            echo '<td>'.intval($ad['started']).'</td>';
            echo '<td>'.intval($ad['ended']).'</td>';
            echo '<td>';
            echo '<a href="?edit=1&id='.$ad['id'].'">edit</a>&nbsp;';
            echo '<a href="?del=1&id='.$ad['id'].'" onclick="if(confirm(\''._('Do you really want to delete this record?').'\')){return true}else{return false}">del</a>';
            echo '</td>';
            echo '</tr>';
        }
        ?>
    </table>
</td>
</tr>
<tr>
    <td align="center">
    <br>
    <br>

    <form method="POST">
        <table class="form">
            <tr>
                <td><?= _('Title')?></td>
                <td>
                    <input type="text" name="title" size="37" maxlength="40" value="<?echo @$current_ad['title']?>"/>
                    <input type="hidden" name="<?= !empty($_GET['edit']) ? "edit" : "add" ?>" value="1">
                </td>
            </tr>
            <tr>
                <td>Video URL</td>
                <td><input type="text" name="url" size="37" value="<?echo @$current_ad['url']?>"/></td>
            </tr>
            <tr>
                <td><?= _('Must watch')?></td>
                <td>
                    <select name="must_watch">
                        <option value="all" <?= @$current_ad['must_watch']=='all'? 'selected' : ''?>><?=_('All')?></option>
                        <option value="90"  <?= @$current_ad['must_watch']=='90'? 'selected' : ''?>>90%</option>
                        <option value="80"  <?= @$current_ad['must_watch']=='80'? 'selected' : ''?>>80%</option>
                        <option value="70"  <?= @$current_ad['must_watch']=='70'? 'selected' : ''?>>70%</option>
                        <option value="60"  <?= @$current_ad['must_watch']=='60'? 'selected' : ''?>>60%</option>
                        <option value="50"  <?= @$current_ad['must_watch']=='50'? 'selected' : ''?>>50%</option>
                        <option value="40"  <?= @$current_ad['must_watch']=='40'? 'selected' : ''?>>40%</option>
                        <option value="30"  <?= @$current_ad['must_watch']=='30'? 'selected' : ''?>>30%</option>
                        <option value="20"  <?= @$current_ad['must_watch']=='20'? 'selected' : ''?>>20%</option>
                        <option value="10"  <?= @$current_ad['must_watch']=='10'? 'selected' : ''?>>10%</option>
                        <option value="5"  <?= @$current_ad['must_watch']=='5'? 'selected' : ''?>>5%</option>
                        <option value="0"  <?= @$current_ad['must_watch']=='0'? 'selected' : ''?>>0%</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td></td>
                <td><input type="submit" value="<?= _('Save')?>"/></td>
            </tr>
        </table>
    </form>

    </td>
</tr>
</table>