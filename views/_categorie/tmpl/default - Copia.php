<?php
defined('_JEXEC') or die('Restricted access');
foreach ($this->categorie as $val) {
    //Imposto l'icona del canale
    switch ($val['canale']) {
        case 1:
            $_img_categoria = 'components/com_gglms/images/cult.jpg';
            break;

        case 2:
            $_img_categoria = 'components/com_gglms/images/edu.jpg';
            break;
        case 3:
            $_img_categoria = 'components/com_gglms/images/fun.jpg';
            break;
        case 4:
            $_img_categoria = 'components/com_gglms/images/plus.jpg';
            break;

        default:
            $_img_categoria = 'components/com_gglms/images/star.png';
            break;
    }
    $_link = 'index.php?option=com_gglms&task=categorie&id=' . $val['id'];
    echo '<div class="tv_categorie_lista" id="' . $val['id'] . '"style="background-image:url(\'' . $_img_categoria . '\'); background-repeat: no-repeat">'
    . $val['categoria'] .
    '</div>';
    if ($val['id'] == JRequest::getInt('id')) {
        $_nome_categoria = $val['categoria'];
    }
}

$_img_categoria = 'components/com_gglms/images/'.$this->categoria['canale'].'.jpg';
?>

<div id="img_sezione">
    <img src="<?php echo $_img_categoria; ?>">

</div>
<div id="nome_categoria">
    <div class="titolo_categoria"><?php echo $this->categoria['categoria']; ?></div>
</div>

<?php
if (1 == $this->categoria['banner'] && isset($this->categoria['path_immagine'])) {
    ?>
    <div id="banner_categoria" style="margin-bottom: 20px;">
        <?php if ($this->categoria['link']) { ?>
            <a href="/home/mediatv/allegati/<?php echo $this->categoria['link']; ?>">
            <?php } ?>
            <img src="/home/mediatv/banner/categoria/<?php echo $this->categoria['path_immagine']; ?>">
            <?php if ($this->categoria['link']) { ?>
            </a>
        <?php } ?>
    </div>
    <?php
}
?>
<div id="tv_contenuti_categoria">
    <?php
    foreach ($this->items as $k => $v) {

        $_img_contenuto = "mediatv/_contenuti/" . $v->id . "/" . $v->id . ".jpg";

        if (!file_exists($_img_contenuto)) {
            $_img_contenuto = "mediatv/_contenuti/" . "voltoignoto.png";
        }
        ?>
        <div class="tv_contenuto" id="<?php echo $v->id; ?>">
            <div class="greybar"></div>

            <div class="tv_contenuto_background"  >

                <a href="<?php
                echo JRoute::_('index.php?option=com_gglms&alias=' . $v->alias . '');
                ?>">
                    <img width="118px" src="<?php echo $_img_contenuto; ?>">    
                </a>
            </div>

            <div class="tv_contenuto_titolo">
                <a href="<?php
                echo JRoute::_('index.php?option=com_gglms&alias=' . $v->alias . '');
                ?>">
                       <?php echo $v->titolo; ?>
                </a> 
            </div>

            <div class="tv_contenuto_descrizione">
                <?php echo $v->descrizione; ?>
            </div>









            <div class="datapubblicazione">
                <?php echo $v->datapubblicazione; ?>        

            </div>

        </div>
        <?php
    }
    ?>


    <?php //echo $i + 1 + $this->pagination->limitstart;     ?>
</div>
<div class="pagination_tv"> 
    <?php echo $this->pagination->getListFooter(); ?> 
</div>

<input type="hidden" name="view" value="categorie" />


