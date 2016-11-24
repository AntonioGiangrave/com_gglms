<?php
defined('_JEXEC') or die('Restricted access');
$urlback = base64_encode($_SERVER['REQUEST_URI']);
?>

<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>

<script type="text/javascript">


    jQuery(function() {

        jQuery("#listaUnita").accordion();

    });

</script>
<div id="listaUnita">
    <?php
    $i = 1;
    foreach ($this->esercizi as $unita) {
        ?>
        <h3>Unit <?php echo $i; ?></h3>

        <?php
        echo "<div>";
        $tmp = explode(",",$unita);
        $c=1;
        foreach ($tmp as $es) {
            echo " <a href=" . JRoute::_("index.php?option=com_gglms&view=esercizio&id=$es&urlback=$urlback") . " />$c</a> ";
            $c++;
        }
        echo "</div>";
        $i++;
    }
    ?>
</div>



