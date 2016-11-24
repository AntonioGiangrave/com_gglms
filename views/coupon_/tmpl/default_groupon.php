<?php
/**
 * @version		1
 * @package		webtv
 * @author 		antonio
 * @author mail         antonio@ggallery.it
 * @link		
 * @copyright	Copyright (C) 2011 antonio - All rights reserved.
 * @license		GNU/GPL
 */
// no direct access
defined('_JEXEC') or die('Restricted access');
?>
<script>
  jQuery(function() {
    jQuery.ajaxSetup({cache: false});

    jQuery('.uploadbox').hide();

    jQuery("button").click(function(e) {
      e.preventDefault();
      jQuery("#button_conferma_codice").hide();
      jQuery(".help-inline-coupon").html("Verifica in corso...");
      

      jQuery.get("index.php?option=com_gglms&task=check_coupon", {coupon: jQuery("#inputCoupon").val(), codiceverifica: jQuery("#inputVerifica").val()},
        function(data) {
          if (data.valido) {
            jQuery(".inputCoupongroup").removeClass('error').addClass('success');
            jQuery(".inputVerificagroup").removeClass('error').addClass('success');
            jQuery("#inputCoupon").prop('disabled', true);
            jQuery("#inputVerifica").prop('disabled', true);
            jQuery('.uploadbox').show();

          } else
          {
            jQuery("#button_conferma_codice").show();
            jQuery(".inputCoupongroup").addClass('error');
            jQuery(".inputVerificagroup").addClass('error');

            jQuery(".help-inline-sicurezza").html("");

          }
          jQuery(".help-inline-coupon").html(data.report);

        }, 'json');

    });
});



</script>


<div id="box_coupon_container">

  <div id="box_coupon">

   <p>Per poter accedere alla piattaforma dovrai inserire nello spazio sottostante il Codice di sicurezza&quot; e il &quot;Codice Groupon&quot; ricevuti al momento dell'acquisto del corso tramite Groupon. </p>

    <p>Questa operazione andrà eseguita solo durante ial primo accesso; per le visite successive sarà sufficiente inserire username e password cliccando sul pulsante &quot;Accedi&quot;.</p>

    <h3>Inserisci qui i tuoi codici: </h3>


    <!-- <input class="field" id="box_coupon_field" type="text" name="nome" /> -->
    <!-- <button id="button_conferma_codice">Conferma codice</button> -->
    <div class="row-fluid">
     <div class="span12">


      <form class="form-horizontal">

        <div class="control-group inputVerificagroup ">
          <label class="control-label" for="inputVerifica">Codice di sicurezza</label>
          <div class="controls">
            <input class="input-xlarge" type="text" id="inputVerifica" placeholder="codice di sicurezza">
            <span class="help-inline help-inline-sicurezza"></span>
          </div>
        </div>

        <div class="control-group  inputCoupongroup ">
          <label class="control-label" for="inputCoupon">Codice Groupon</label>
          <div class="controls">
            <input class="input-xlarge" type="text" id="inputCoupon" placeholder="codice groupon">
            <span class="help-inline help-inline-coupon"> </span>

          </div>
        </div>

        <div class="control-group">
          <div class="controls">


            <button id="button_conferma_codice" type="submit" class="btn">Conferma</button>
          </div>
        </div>
      </form>

    </div>
</div>
<div class="row-fluid">
    <div class="span12 uploadbox">


      <p class="warning">Per completare l'attivazione  carica qui sotto il PDF che Groupon ti ha inviato. Solo completando questa operazione potrai avere accesso ai tuoi corsi.</p>


      <form id="upload" method="post" action="index.php?option=com_gglms&task=fileUpload" enctype="multipart/form-data">
        <div id="drop">
          Trascina qui il PDF del tuo coupon

          <a>Sfoglia</a>
          <input type="file" name="upl"  />
          <input type="text" id="filename" name="filename" value="coupon123" />

        </div>

        <ul>
          <!-- The file uploads will be shown here -->
        </ul>

      </form>

    </div>

  </div>
  
  
</div>











