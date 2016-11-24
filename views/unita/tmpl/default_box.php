<?php
// no direct access
FB::log("-> tmpl Box");

defined('_JEXEC') or die('Restricted access');
require_once JPATH_COMPONENT.'/helpers/output.php';
require_once JPATH_COMPONENT.'/helpers/gglms.php';

FB::log($this->unita, "unita"); 

?>
<script type="text/javascript">
  


  jQuery(document).ready(function() {

    // $(function () {
    //   $('#rt-mainbody-surround').replaceWith($('#gglms_container'))
    // });


    $('#myTab a').click(function (e) {
      e.preventDefault()
      $(this).tab('show')
    })

    // $('#tabs-1').removeClass('fade');
    // $('#tabs-1').addClass('active');


  });



</script>




<div id="gglms_container" class="container">

  <ul class="breadcrumb">
    <li><a href="index.php">Home</a><span class="divider"></span></li>
    <?php
    echo gglmsHelper::getBreadcrumb($this->unita['id']);
    ?>
  </ul>



  <!-- INIZIO COL -->
  <!-- <div id="gglms_menu"> -->
    <?php
    //echo outputHelper::menu($this->unita['categoriapadre'], $this->unita['id']); 
    ?>
  <!-- </div> -->
  <!-- FINE COL -->

  <?php
  $unit = gglmsHelper::getSubUnit($this->unita['id']);
  $contenuti = gglmsHelper::getContenuti($this->unita['id']);

  $all = array_merge($unit, $contenuti);


  ?>
  <div id="gglms_content">
    <?php
    echo outputHelper::getTabbedView($all);
    ?>

  </div>
</div>


