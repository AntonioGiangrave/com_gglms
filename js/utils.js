/**
 * Se l'immagine non esiste carica un immagine di default.
 * Uso: <img src="images/immagine.jpg" onerror="imgError(this);" />
 * 
 * @param Object image
 */
function imgError(image){
    image.onerror = '';
    image.src = 'components/com_gglms/images/broken.png';
    return true;
}