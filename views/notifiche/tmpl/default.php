<?php
defined('_JEXEC') or die('Restricted access');
$urlback = base64_encode($_SERVER['REQUEST_URI']);

require_once JPATH_COMPONENT.'/helpers/output.php';
require_once JPATH_COMPONENT.'/helpers/gglms.php';

FB::log($results, "results");

?>


<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<script type="text/javascript">
    
$(function () {
	$('#rt-transition').replaceWith($('#gglms_container'))
});

</script>

<div id="gglms_container" class="container">
    
        <?php
            echo outputHelper::setGestioneNotificheJS()
        ?>

    
    <div>
            <form method="post"  role="notifiche" action="<?php echo JURI::root(); ?>component/gglms/search">
               <?php
               if(empty($this->results))
                  echo outputHelper::setTipologieContenuti(); 
               ?>
                <input hidden type="text" name="notifiche" value="Yes" />
                <button id = "btnNotifiche" class="btn btn-default" type="submit"> Imposta </button>
            </form>
        </div>   
    
    
</div>



