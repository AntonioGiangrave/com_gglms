<?php
FB::log("TPL SCORM");
// no direct access
defined('_JEXEC') or die('Restricted access');


$path = $this->path."/";
FB::log($path);

// echo $this->initializeCache;

?>
<div id="percorso_elemento">
    Torna a <a class="title" href="index.php?option=com_gglms&view=unita&alias=<?php echo $this->elemento['unita']['alias']; ?>">
        <?php  echo $this->elemento['unita']['categoria']; ?></a>
    <span class="title"><h2> <?php echo $this->elemento['titolo']; ?></h2></span>
</div>


 <div class="pulsanti">
                                    <?php
                                    foreach ($this->files as $file) {
                                        switch ($file['type']) {
                                            case '1':
                                            $ico = '';
                                            break;  

                                            case '2':
                                            $ico = '';
                                            break;

                                            case '3':
                                            $ico = '';
                                            break;     

                                            default:
                                            $ico = '';
                                            break;
                                        }

                                        echo '<div class="allegato"><a target="_blank" href="../mediagg/files/'.$file['id'].'/'.$file['filename'].'">'.$file['name'].'</a></div>';

                                    }

                                    ?>

                                </div> 