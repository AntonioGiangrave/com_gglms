{* Smarty XHTML *}
<style>
    #container {
        text-align:center;
    }
    #attestato {
        margin: 0 auto;
        text-align:center;
    }
    h1 {
        color: navy;
        font-family: times;
        font-size: 16pt;
        text-align:center;
    }
    p {
        color: #000;
        font-family: times;
        font-size: 12pt;
        text-align:center;
    }
</style>
<div id="container">
    <div id="attestato">
        <div style="text-align: center">
            <img width="800" src="components/com_gglms/models/libs/pdf/imgs/AttestatoHeader.png" align="center" />
        </div>

        <div>
            <h1>ATTESTATO DI PARTECIPAZIONE</h1>
            <p> si attesta che </p>
            <p><strong>{$data.cb_cognome|capitalize} {$data.cb_nome|capitalize}</strong><br /> </p>

            <p>nato a {$data.cb_luogodinascita|capitalize} {if !empty($data.cb_provinciadinascita)}({$data.cb_provinciadinascita}){/if}<br /></p>
            <p>ha frequentato il corso </p>
            <p><strong>{$data.titoloattestato}</strong></p>
            <p>della durata di {$data.durata} ore ai sensi dell'art.37,comma 1 lettera a, del D.Lgs. 81/08 <br> e dell'Accordo Conferenza Stato Regioni del 21 dicembre 2011 terminato il 28/03/2013</p>


            <p>
                Genova, li {$data.datetest} 
            </p>
            <div style="text-align: center; width: 300px; position: relative; float: left">
                IL RAPPRESENTANTE LEGALE DELL'ORGANIZZATORE<br />
                Dott. Vincenzo Lorenzelli<br />
                <img width="300" src="components/com_gglms/models/libs/pdf/imgs/firma_lorenzelli.jpg" align="center" />
            </div>
        </div>
        <div style="text-align: center; bottom: 0px; position: absolute;">
            <img height ="200" width="800" src="components/com_gglms/models/libs/pdf/imgs/AttestatoFooterAlto.png" align="center" />
        </div>

    </div>
</div>   
