function sliding(tempo){
    if (old_tempo != tempo && typeof(jumper.length) != 'undefined') {
        old_tempo = tempo;
        var currTime = parseInt(tempo);
        var i = 0;
        var past_jumper_selector = new Array();
        while (i<jumper.length && currTime>=parseInt(jumper[i]['tstart'])) {
            past_jumper_selector[i] = '#'+i;
            i++;
        }
        i--; // col ciclo while vado avanti di 1
        if (i<jumper.length && i != jumper_old) { // se cambio jumper
            jQuery('#'+jumper_old).css('background-color', '#fff');
            jumper_old = i;
        
            // cambio slide
            var url = path_slide+'normal/slide'+(i+1)+'.jpg';
            jQuery('#slide_src').attr('src',url);
            jQuery('#slide').fadeIn();
            // cancello eventuali jumper azzurri
            jQuery('.jumper').css('background-color', '#fff');
        
            // jumper attuale è azzurro
            jQuery('#'+i).css('background-color','#98ACC6');
        }
    }
}
