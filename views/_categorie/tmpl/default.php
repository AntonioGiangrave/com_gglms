<?php
defined('_JEXEC') or die('Restricted access');

FB::log($this->categoria);
$_img_categoria = 'components/com_gglms/images/'.$this->categoria['canale'].'.jpg';
?>


<div id="img_sezione">
    <img src="<?php echo $_img_categoria; ?>">

</div>
<div id="nome_categoria">
    <div class="titolo_categoria"><?php echo $this->categoria['categoria']; ?></div>
</div>

?>
