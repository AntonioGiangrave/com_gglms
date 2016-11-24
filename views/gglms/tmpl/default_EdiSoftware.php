<?php
// no direct access
FB::log("-> tmpl Box");

defined('_JEXEC') or die('Restricted access');
require_once JPATH_COMPONENT . '/helpers/output.php';
require_once JPATH_COMPONENT . '/helpers/gglms.php';

FB::log($this->unita, "unita");
?>

<script type="text/javascript">
    $(function () {
        $('#rt-transition').replaceWith($('#gglms_container'))
    });


    $(document).ready(function () {
        $('.comprimi').hide();
    });


    $('.showhide.espandi').click(function (e) {
        $(this).parent().parent().addClass("boxHomeFullHeight");
        $(this).hide();
        $(this).next('.showhide.comprimi').show();
    });


    $('.showhide.comprimi').click(function (e) {
        $(this).parent().parent().removeClass("boxHomeFullHeight");
        $(this).hide();
        $(this).prev('.showhide.espandi').show();
    });

</script>

<div id="gglms_container" class="container-fluid">

    
            <!-- Table breadcrumb -->
            <table class="breadcrumb">
                <tr>
                    <td><a href="<?php echo JROUTE::_('index.php?option=com_gglms'); ?>">Home</a><span class="divider"></td>

                    <?php
                    FB::info($this->notifiche, "notifiche");
                    if ($this->notifiche == '1')
                        echo '<td><a href="' . JROUTE::_('index.php?option=com_gglms&view=notifiche') . '">Notifiche</a><span class="divider"></span></td>';
                    ?>
                </tr>
            </table>


            <!-- INIZIO COL -->
            <div id="gglms_menu">
                <?php
                echo outputHelper::menu();
                ?>
            </div>
            <!-- FINE COL -->

 

            <!-- gglms_content-->
            <div id="gglms_content">


                <div class="boxhome">
                    <h3 class="title"> 

                        <button type="button" class="btn btn-default showhide espandi" aria-label="Right Align">
                            <span class=" glyphicon glyphicon-plus" aria-hidden="true"></span> 
                        </button>
                        <button type="button" class="btn btn-default showhide comprimi" aria-label="Right Align">
                            <span class=" glyphicon glyphicon-minus" aria-hidden="true"></span>
                        </button>

                        Contenuti piu visti

                    </h3>


                    <?php echo outputHelper::getbox(gglmsHelper::getMostView()); ?>
                </div>

                <div class="boxhome">
                    <h3 class="title">

                        <button type="button" class="btn btn-default showhide espandi" aria-label="Right Align">
                            <span class=" glyphicon glyphicon-plus" aria-hidden="true"></span>
                        </button>

                        <button type="button" class="btn btn-default showhide comprimi" aria-label="Right Align">
                            <span class=" glyphicon glyphicon-minus" aria-hidden="true"></span>
                        </button>

                        Scelti per te

                    </h3>

                    <?php echo outputHelper::getbox(gglmsHelper::getContenuti(78)); ?>
                </div>

                <div class="boxhome">
                    <h3 class="title">

                        <button type="button" class="btn btn-default showhide espandi" aria-label="Right Align">
                            <span class=" glyphicon glyphicon-plus" aria-hidden="true"></span>
                        </button>

                        <button type="button" class="btn btn-default showhide comprimi" aria-label="Right Align">
                            <span class=" glyphicon glyphicon-minus" aria-hidden="true"></span>
                        </button>

                        I Più votati

                    </h3>

                    <?php echo outputHelper::getbox(gglmsHelper::getTopRated()); ?>
                </div>




            </div>

   
</div>


