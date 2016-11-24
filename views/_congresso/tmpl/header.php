<?php
defined('_JEXEC') or die('Restricted access');

$content = $this->contents[$this->active_content_idx];
$content_id = $content['id'];
//$path="http://www.edu-live.tv/home/mediatv/_contenuti/".$id_contenuto."/";
$path = '/home/mediatv/_contenuti/' . $content_id . '/';
?>
<div class="titolo_contenuto"><?php echo $content['congresso']; ?></div>
<div class="titolo_contenuto"><?php echo $content['titolo']; ?></div>
<div id="article">
