<?php
FB::log("TPL UPLOAD");
// no direct access
defined('_JEXEC') or die('Restricted access');


$path = $this->path."/";
$id= $this->elemento['id'];
$alias= $this->elemento['alias'];
// echo $this->initializeCache;
$nomefile= "cosegna-".$this->id_utente."-".$this->elemento['unita']['alias'];
$path= $_SERVER['DOCUMENT_ROOT']."/mediagg/contenuti/".$id."/";

?>


<div id="percorso_elemento">
    Torna a <a class="title" href="index.php?option=com_gglms&view=unita&alias=<?php echo $this->elemento['unita']['alias']; ?>">
    <?php  echo $this->elemento['unita']['categoria']; ?></a>
    <span class="title"><h2> <?php echo $this->elemento['titolo']; ?></h2></span>
</div>


<?php



function upload($dir = FALSE, $tipo = FALSE, $dim = FALSE, $debug = FALSE, $name = Null){


 if(!is_uploaded_file($_FILES['file']['tmp_name'])){
     echo "Nessun file selezionato da inviare al server";
     exit();
 }

 if(is_uploaded_file($_FILES['file']['tmp_name'])){

     //Funzione di debug
   if($debug != FALSE){
        // print_r($_FILES);
        // print_R($_REQUEST);
        // echo $source_dir.$dir;
   }    
   
     //Controllo che il file non esista
   if (is_file($dir.$_FILES['file']['name'])){
     echo "Esiste gi&agrave; un file con lo stesso nome!<br />
     Rinominarlo e tentare nuovamente!";
     exit();
 }

     //Controllo il tipo di file se ne ho definito uno o più obbligatori
 if($tipo != FALSE){  
     if(is_array($tipo)){ 
       if(!in_array($_FILES['file']['type'],$tipo)){
        echo "Tipo file non consentito";
        exit();
    }
}else{
   if($_FILES['file']['type'] != $tipo){
    echo "Tipo file non consentito";
    exit();
}
}
}

    //Controllo che le dimensioni non eccedino il massimo consentito
if($dim != FALSE){
  $dimz = $dim * 1024000;    
  if($_FILES['file']['size'] > $dimz){
    echo "Il file che si sta cercando di inviare &egrave; troppo grande!<br />
    la dimensione massime consentita &egrave; di " . $dim . " megabyte";
    exit();       
}
} 

    //Controllo che la directory di destinazione sia server writable
if(!is_writable($dir)){
 echo "Non si dispone dei permessi necessari!<br />
 Contattare l'amministratore del sistema e far settare i permessi della directory \"<i>". $dir . "</i>\" a 0777";
 exit();     
} 

    //Passati tutti i controlli posso inviare il file al server!



$tmp = explode(".", $_FILES['file']['name']);
$tmp = array_pop($tmp);
$name = $name.".".$tmp;

if(move_uploaded_file($_FILES['file']['tmp_name'], $source_dir.$dir.$name)){
 echo "Il file &egrave; stato inviato correttamente al server!".$source_dir.$dir.$name;
}
}
}



if(isset($_POST['go'])){
 upload($path,  NULL ,100, $debug = TRUE, $name=$nomefile);
}
else{
    ?>
    <form name="upload" method="post" action="" enctype="multipart/form-data" >

        <span class="btn btn-success fileinput-button">
            <i class="glyphicon glyphicon-plus"></i>
            <span>Seleziona il file da caricare </span>        
            <input type="file" name="file" id="file" />
        </span>


         <span class="btn btn-warning fileinput-button">
            <span>Invia</span>        
            <input type="submit" name="go" id="submit" value="Invia al Server" />
            </span>



    </form>
    <?php
}
?> 

