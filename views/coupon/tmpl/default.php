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
    jQuery(function () {
        jQuery.ajaxSetup({cache: false});

        //Verifica Codice Sicurezza
        function convalida() {
            var pattern = /^[a-zA-Z0-9]{10}$/;
            var codicesicurezza = jQuery("#inputVerifica").val();
            if (codicesicurezza.search(pattern) == -1) {
                console.log("negativo");
                return false;
            } else {
                console.log("convalida va bene");
                return true;
            }
        }
        
        jQuery("button").click(function (e) {
            e.preventDefault();

            if (!convalida())
            {
                jQuery(".inputVerificagroup").addClass('error');
                jQuery(".help-inline-sicurezza").html("Codice non valido!");
            } else
            {
                jQuery("#button_conferma_codice").hide();
                jQuery(".inputVerificagroup").removeClass('error').addClass('success');
                jQuery(".help-inline-sicurezza").html("Codice valido");
                jQuery(".help-inline-coupon").html("Verifica in corso...");

                jQuery.get("index.php?option=com_gglms", {
                        task:'checkGroupon',
                        coupon: jQuery("#inputCoupon").val(),
                        codiceverifica: jQuery("#inputVerifica").val()
                    },

                    function (data) {
                        if (data.ok) {
                            jQuery(".inputCoupongroup").removeClass('error').addClass('success');
                            jQuery("#inputCoupon").prop('disabled', true);
                            jQuery("#report").html(data.mieicorsi);
                            
                        } else
                        {
                            jQuery("#button_conferma_codice").show();
                            jQuery(".inputCoupongroup").addClass('error');

                        }
                        jQuery(".help-inline-coupon").html(data.error);
                    },
                    'json');
            }
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

        <div id="report">
        </div>
    </div>

</div>









