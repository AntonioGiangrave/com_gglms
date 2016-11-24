<?php
// no direct access
defined('_JEXEC') or die('Restricted access');
?>
<div id="lista_congressi">
    <p class='lista_congressi_title'>
        <?php
        if (1 == $this->type)
            echo '<div class="titolo_contenuto">VIRTUAL FORUM</div>';
        elseif (2 == $this->type)
            echo '<div class="titolo_contenuto">SPECIALE CONGRESSI</div>';
        ?>
    </p>

    <?php
    $i = 1;
    foreach ($this->congressi as $congresso) {
        ?>
        <div class="congresso">
            <p class="titolo_congresso"><?php echo $congresso['congresso']; ?></p>
            <a href="<?php echo JROUTE::_('index.php?option=com_gglms&view=congresso&alias=' . $congresso['alias']); ?>">
                <img src="/home/mediatv/copertine/<?php echo $congresso['id']; ?>.jpg" alt="<?php echo $congresso['congresso']; ?>" />
            </a>
        </div>
        <?php
        if (0 === $i++ % 2) {
            echo '<div style="clear:both"></div>';
        }
    }
    ?>

</div>
