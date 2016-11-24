 <div id="contenuti_congresso">
    <?php
for($i=0,$tot=count($this->contents); $i<$tot; $i++) {
?>
    <div class="tv_contenuto">
        <div class="greybar"></div>
        <div class="tv_contenuto_background">
            <a href="<?php echo JRoute::_('index.php?option=com_gglms&view=congresso&alias=' . $this->contents[$i]['alias_congresso'] . '&show='.$i); ?>">
                <img width="118px" src="/home/mediatv/_contenuti/<?php echo $this->contents[$i]['id']; ?>/<?php echo $this->contents[$i]['id']; ?>.jpg">
            </a>
        </div>
        <div class="tv_contenuto_titolo">
            <?php echo $this->contents[$i]['titolo']; ?>
            <div class="datapubblicazione"><?php echo $this->contents[$i]['datapubblicazione']; ?></div>
        </div>
    </div>
<?php
            }
        ?>
</div>

<!--<div style="clear:both"></div>-->
