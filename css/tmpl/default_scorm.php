<?php
FB::log("TPL SCORM");
// no direct access
defined('_JEXEC') or die('Restricted access');


$path = $this->path."/";
FB::log($path);

// echo $this->initializeCache;

?>
<script type="text/javascript">
	jQuery(document).ready(function($) {
		// var pathschedacaso = '<?php echo $this->pathscorm;?>';
		// jQuery('#panel_scorm_body').load(pathschedacaso);
		jQuery('#panel_scorm').modal('show');
	});

</script>

<ul class="breadcrumb">
               <li><a href="index.php">Home</a><span class="divider"></span></li>
               <?php
               echo gglmsHelper::getBreadcrumb(NULL, NULL, $this->elemento['id']);
               ?>
               <li class="active"><?php echo $this->elemento['titolo']; ?></li>
              </ul>


<div id= "panel_scorm" class="modal fade"  tabindex="-1" backdrop="false" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
              <div class="modal-dialog modal-dialog-scorm">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Ipertesto</h4>
                </div>
                <div class="modal-body modal-body-scorm" id="panel_scorm_body">
                <iframe src="<?php echo $this->pathscorm;?>" name="course" noresize="" width="1024" height="700" style="framewrap: 0 !important;"></iframe>
                </div>
                <div class="modal-footer">
                    <!-- <button type="button" class="btn btn-default" data-dismiss="modal">Chiudi</button> -->

                </div>
            </div>
        </div>
    </div>




<iframe src="../vsscorm/api.php?SCOInstanceID=<?php echo $this->elemento['id']; ?>&UserID=<?php echo $this->id_utente; ?>" name="APIframe" noresize  width="0" height="0"></iframe>