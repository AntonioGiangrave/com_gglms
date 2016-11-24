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
            <img width="800" src="components/com_gglms/models/libs/pdf/imgs/header_regionale.jpg" align="center" />
        </div>

        <div>
            <h1>Programma nazionale per la formazione continua degli operatori della sanità</h1>
            <h1>REGIONE LIGURIA</h1>
            <p>(ai sensi della D.G.R. n. 146 dell'11/02/2005)</p>

            <h1>Il Provider Regionale ECM FAD
                <br>
                <b>ISTITUTO GIANNINA GASLINI</b>
            </h1>

            <p>Attesta che il Sig./Dott.<strong>{$data.cb_cognome|capitalize} {$data.cb_nome|capitalize}</strong>  </p>

            <p>nato a {$data.cb_luogodinascita|capitalize} {if !empty($data.cb_provinciadinascita)}({$data.cb_provinciadinascita}){/if}
                il {$data.cb_datadinascita} <br />
                ha partecipato al Corso di formazione a distanza dal titolo:</p>

            <h1><strong>{$data.titoloattestato}</strong></h1>
            <p> N.Codice Progetto {$data.codice_ecm} <br>
                tenutosi dal {$data.datainizio} al {$data.datafine}

                N. ore di formazione {$data.durata}
            </p>

            <p><strong>In conformita alla documentazione conservata e in applicazione ai criteri della D.G.R. n.146 dell'11/02/2005</strong></p>


            <p>
                ha conseguito<br />
                N. {$data.crediti} ({$data.crediti_testo}) Crediti formativi per l'anno 2013
            </p>
            <p>
                Genova, li {$data.datetest} 
            </p>




            <p>
                IL RAPPRESENTANTE LEGALE DELL'ORGANIZZATORE<br />
                Dott. Vincenzo Lorenzelli<br />
            <p style="font-size: 9pt;">Certificato senza firma autografa, sostituita dall’indicazione del nominativo del Rappresentante legale dell’ Ente, ai sensi dell’art. 3 – 2° comma, del D.L. n. 39 del 12/02/1993
            </p>
                    
                
<!--                <img width="200" src="components/com_gglms/models/libs/pdf/imgs/firma_lorenzelli.jpg" align="center" />-->
            </p>
        </div>
    </div>
</div>   
