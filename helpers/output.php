<?php
/**
 * @package		Joomla.Tutorials
 * @subpackage	Components
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		License GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access to this file
defined('_JEXEC') or die;
require_once JPATH_COMPONENT . '/helpers/gglms.php';
require_once JPATH_COMPONENT . '/helpers/rating.php';

class outputHelper {

    public function deleteNotificheUtente() {
        $user = JFactory::getUser();
        $userid = $user->get('id');

        $db = JFactory::getDBO();
        $query = "delete from #__gg_notifiche_utente_contenuti_map where  idutente=" . $userid;
        $db->setQuery($query);
        $results = $db->query();

        FB::log($query, " query deleteNotificheUtente ");
        FB::log($res, " res deleteNotificheUtente ");
        return $res;
    }

    public function setNotificheUtente($idTipologys) {
        $user = JFactory::getUser();
        $userid = $user->get('id');

        $db = JFactory::getDBO();
        foreach ($idTipologys as $id) {
            $query = "insert  into  #__gg_notifiche_utente_contenuti_map(idutente,idtipologiacontenuto) values(" . $userid . "," . $id . ")";
            $db->setQuery($query);
            $results = $db->query();
        }
        FB::log($query, " query setNotificheUtente ");
        FB::log($res, " res setNotificheUtente ");
        return $res;
    }

    public function getContenutiTipology() {
        $user = JFactory::getUser();
        $userid = $user->get('id');

        $db = JFactory::getDBO();
        $query = "select * from #__gg_contenuti_tipology where pubblicato = 1 order by ordinamento asc";
        $db->setQuery($query);
        $res = $db->loadAssocList();



        FB::log($query, " query getTipologieContenuti ");
        FB::log($res, " res getTipologieContenuti ");
        return $res;
    }

    public function getIdTipologiaContenuto() {
        $user = JFactory::getUser();
        $userid = $user->get('id');


        $dbUser = JFactory::getDBO();
        $queryUser = "select idtipologiacontenuto from #__gg_notifiche_utente_contenuti_map where idutente = " . $userid;
        $dbUser->setQuery($queryUser);
        $resUser = $dbUser->loadColumn(0);

        FB::log($queryUser, " queryRes getTipologieContenuti ");
        FB::log($resUser, " resUser getTipologieContenuti ");
        return $resUser;
    }

    //RS
    public static function setTipologieContenuti() {

        $user = JFactory::getUser();
        $userid = $user->get('id');

        $res = outputHelper::getContenutiTipology();
        $resUser = outputHelper::getIdTipologiaContenuto();

        FB::log($query, " query getTipologieContenuti ");
        FB::log($queryUser, " queryRes getTipologieContenuti ");

        FB::log($res, " res getTipologieContenuti ");
        FB::log($resUser, " resUser getTipologieContenuti ");
        $xml = "
			<fieldset>
                                <h3>Seleziona per quali contenuti vuoi essere aggiornato</h3>";
        foreach ($res as $item) {

            if (in_array($item['id'], $resUser)) {
                $xml.="
                                    <input type='checkbox' id='" . $item['tipologia'] . "' name='" . $item['tipologia'] . "' value='" . $item['id'] . "' checked='checked' />  " . $item['descrizione'] . "  <br />
			";
                FB::info(" getTipologieContenuti dentro if");
            } else {
                $xml.="
                                        <input type='checkbox'  id='" . $item['tipologia'] . "' name='" . $item['tipologia'] . "' value='" . $item['id'] . "' />  " . $item['descrizione'] . "  <br />
                            ";
                FB::info(" getTipologieContenuti fuori if ");
            }
        }
        $xml.="
                        </fieldset>
			";
        FB::log($xml, " xml getTipologieContenuti ");
        return $xml;
    }

    //RS
    public static function setGestioneNotificheJS() {

        $user = JFactory::getUser();
        $userid = $user->get('id');

        $db = JFactory::getDBO();
        $query = "select * from #__gg_contenuti_tipology where pubblicato = 1 order by ordinamento asc";
        $db->setQuery($query);
        $res = $db->loadAssocList();

        FB::log($query, " query setGestioneNotificheJS ");

        FB::log($res, " res setGestioneNotificheJS ");

        $xml = "  <script type='text/javascript'>
                        function GestioneNotificheJS(){
                            var param=[];
                    ";
        foreach ($res as $item) {
            $xml.="
                                    var j" . $item['tipologia'] . " = document.getElementById('" . $item['tipologia'] . "');
                                    if(j" . $item['tipologia'] . ".checked  ) 
                                      param.push(" . $item['id'] . ");  
                                    
                            ";
        }
        $xml.="var url = 'index.php?option=com_gglms&view=search&notifiche=yes&tipologiaContenuti=' + param.toString();";
        //$xml.="alert(url);";  
        $xml.=" return url;";
        $xml.=";}</script>";
        //RS $route=JRoute::_('index.php?option=com_gglms&view=search&search='.$search);
        FB::log($xml, " xml setGestioneNotificheJS ");
        return $xml;
    }

    public static function getTabbedView($items) {

        //Preparo tutti i tab per i diversi tipi di oggetto
        $tab = array();
        $content_type = gglmsHelper::content_type();
        foreach ($content_type as $type) {
            $tab[$type->id] = array();
        }

        //Popolo l'array di risultati
        foreach ($items as $item) {

            //per le categorie verifico se hanno contenuti visibili al loro interno
            if (($item['tipologia'] == '101') || ($item['tipologia'] == '102')) {
                $totContenuti = gglmsHelper::getTOTContenuti($item['id']);
                if ($totContenuti > 0)
                    array_push($tab[$item['tipologia']], $item);
            }
            else {
//                array_push($tab[$item['tipologia']], $item);
// BLOCCO A UNA SOLA TAB                
                array_push($tab[1], $item);
            }
        }
        ?>

        <!--        <div  role="tabpanel">
                    <ul id="linguettetab" class="nav nav-tabs" role="tablist">
        <?php
//                foreach ($content_type as $type) {
//                    if (!empty($tab[$type->id]))
//                        echo "<li role='presentation' >
//					       <a class='navtab' aria-controls='#tabs-" . $type->id . "' role='tab' data-toggle='tab'
//						href='#tabs-" . $type->id . "'>" . $type->descrizione . " (<b>" . sizeof($tab[$type->id]) . "</b>)</a></li>";
//                }
        ?>
                    </ul>-->


        
        
        <div class="tab-content">
            <?php
            foreach ($content_type as $type) {
                if (!empty($tab[$type->id])) {
                    echo '<div role="tabpanel" class="tab-pane fade" id="tabs-' . $type->id . '"> ';  //Apro la tab
                    echo '<div id="results">';
                    outputHelper::getbox($tab[$type->id]);
                    echo '</div>'; // Chiudo results
                    echo '</div>'; // Chiudo la Tab
                }
            }
            ?>
        </div> <!-- CHIUSURA TAB-CONTENT -->
        </div>  <!-- CHIUSURA TABS -->

        <script type="text/javascript">
            $("#linguettetab li:first-child").addClass("active");
            $(".tab-pane").first().addClass("active in");
            $("#gglms_menu li ").click(function () {
                window.location = $(this).find("a").attr("href");
                return false;
            });


            // $('#tabs-1').removeClass('fade');  
            // $('#tabs-1').addClass('active');



        </script>

        <?php
    }

    //RS

    public static function getTabbedView4TextSearch($items) {

        //Preparo tutti i tab per i diversi tipi di oggetto
        $tab = array();
        $content_type = gglmsHelper::content_type();
        foreach ($content_type as $type) {
            $tab[$type->id] = array();
        }
        FB::log($content_type, " list content type tabbedView4TextSearch ");
        //Popolo l'array di risultati
        foreach ($items as $item) {

            //per le categorie verifico se hanno contenuti visibili al loro interno
            if (($item['tipologia'] == '101') || ($item['tipologia'] == '102')) {
                $totContenuti = gglmsHelper::getTOTContenuti($item['id']);
                if ($totContenuti > 0)
                    array_push($tab[$item['tipologia']], $item);
            }
            else {
                array_push($tab[$item['tipologia']], $item);
            }
        }

        FB::log($tab, " list content tab tabbedView4TextSearch ");
        ?>

        <div  role="tabpanel">



            <ul id="linguettetab" class="nav nav-tabs" role="tablist">
                <?php
                foreach ($content_type as $type) {
                    if (!empty($tab[$type->id]))
                        echo "<li role='presentation' >
					<a  class='navtab' aria-controls='#tabs-" . $type->id . "' role='tab' data-toggle='tab'
						href='#tabs-" . $type->id . "'>" . $type->descrizione . " (<b>" . sizeof($tab[$type->id]) . "</b>)</a></li>";
                }
                ?>
            </ul>


            <div class="tab-content">
                <?php
                foreach ($content_type as $type) {
                    if (!empty($tab[$type->id])) {
                        echo '<div role="tabpanel" class="tab-pane fade" id="tabs-' . $type->id . '"> ';  //Apro la tab
                        echo '<div id="results">';
                        outputHelper::getbox($tab[$type->id]);
                        echo '</div>'; // Chiudo results
                        echo '</div>'; // Chiudo la Tab
                    }
                }
                ?>
            </div> <!-- CHIUSURA TAB-CONTENT -->
        </div>  <!-- CHIUSURA TABS -->



        <script type="text/javascript">
            $("#linguettetab li:first-child").addClass("active");
            $(".tab-pane").first().addClass("active in");
            $("#gglms_menu li ").click(function () {
                window.location = $(this).find("a").attr("href");
                return false;
            });



        </script>

        <?php
    }

    public static function getbox($items) {


        foreach ($items as $item) {

            switch ($item['tipologia']) {

                case '101':
                case '102':
                    outputHelper::getUnit($item);
                    break;

                default:
                    outputHelper::getContent($item);
                    break;
            }
        }
    }

    public static function getUnit($item) {

        //FB::log($item, 'item');

        $totContenuti = gglmsHelper::getTOTContenuti($item['id']);
        if ($totContenuti == 0)
            return;

        echo '<div class="box col-md-2  img-rounded">';
        //RS echo '<a href="component/gglms/unita/' . $item['alias'] . '" title="' . htmlentities(utf8_decode($item['descrizione'])) . '"  >';
        $url = 'index.php?option=com_gglms&view=unita&alias=' . $item['alias'];
        echo '<a href="' . JROUTE::_($url) . '"  title="' . htmlentities(utf8_decode($item['descrizione'])) . '"  >';
        FB::log($url, " URL getUnit ");
        ?>

        <div class="boxtitle">
            <?php echo $item['titolo']; ?>
        </div>

        <div class="boximg">
            <?php
            if (file_exists('../mediagg/images/unit/' . $item["id"] . '.jpg'))
                echo '<img class="img-responsive" src="../mediagg/images/unit/' . $item["id"] . '.jpg">';
            else
                echo '<img class="img-responsive" src="components/com_gglms/images/sample.jpg">';
            ?>
        </div>




        <div class="boxinfo">
            <table width="100%">
                <tr>            
                    <td><h5>Elementi: <?php echo $totContenuti; ?></h5></td>
                </tr>
            </table>
        </div>

        </a>

        <?php
        echo "</div>";
    }

    public static function getContent($item) {

        $prerequisiti = gglmsHelper::_chek_prerequisiti($item['id']);
        $stato = gglmsHelper::_check_stato($item['id']);


        FB::log($item['alias'] . "-" . $prerequisiti . "-" . $stato, "debug dentro al contenuto");

        echo '<div class="box col-md-2  img-rounded">';
        if ($prerequisiti) {
            //RS echo '<a href="component/gglms/contenuto/' . $item['alias'] . '"  title="' . strip_tags($item['descrizione']) . '" >';
            $url = 'index.php?option=com_gglms&view=contenuto&alias=' . $item['alias'];

            // FB::log($url ," URL getContent ");
            echo '<a href="' . JROUTE::_($url) . '"  title="' . strip_tags($item['descrizione']) . '" >';
        }
        ?>

        <div class="boxtitle">
            <?php echo $item['titolo']; ?>
        </div>

        <div class="boximg">

            <?php
            if (file_exists('../mediagg/contenuti/' . $item["id"] . '/' . $item["id"] . '.jpg'))
                echo '<img class="img-responsive"  src="../mediagg/contenuti/' . $item["id"] . '/' . $item["id"] . '.jpg">';
            else
                echo '<img class="img-responsive"  src="components/com_gglms/images/sample.jpg">';
            ?>
        </div>

        <div class="boxinfo">


            <table width="100%">
                <tr>            
                        <!--<td rowspan="2" width="33%"><?php //echo  outputHelper::getContentIconStatus($item);      ?> </td>-->
                    <td rowspan="2" width="20%"><?php
                        $prerequisiti = gglmsHelper::_chek_prerequisiti($item['id']);
                        $stato = gglmsHelper::_check_stato($item['id']);

                        outputHelper::getContentIconStatus($prerequisiti, $stato);
                        ?> </td>



                    <td width="20%">Durata</td>
                    <td width="20%"><?php echo outputHelper::convertiDurata($item["durata"]); ?></td>
                    <!-- <td width="40%"><?php echo ratingHelper::totRating($item["id"]); ?> valutazioni</td> -->
                </tr>
                <tr>    
                    <!-- <td>Visite</td> -->
                    <!-- <td><?php // echo gglmsHelper::getViews($item['id']);    ?></td> -->
                    <!-- <td> <input 
                            type="number" 
                            class="rating" 
                            step=1
                            data-size="xs" 
                            data-rtl="false"
                            data-min="0" data-max="5"
                            data-glyphicon="false" 
                            data-rating-class="rating-fa"
                            data-show-caption="false"
                            data-show-clear="false"
                            readonly="true" 
                            value= "<?php //echo ratingHelper::avgRating($item["id"]);    ?>"
                            > </td> -->
                </tr>
            </table>
        </div>

        <?php
        if ($prerequisiti)
            echo '</a>';
        echo "</div>";
    }

    public static function menu($item = 2, $active = null) {

        $root = outputHelper::getUnitmenu($item);
        $out = '<nav>';
        $out.=outputHelper::buildmenu($root, 0, $active);
        $out.='</nav>';
        return $out;
    }

    //RS
    public static function resultsListAttributes($counters, $attributeSearchParam, $search, $uri) {



        $root = outputHelper::getResultsListCountAttributes($counters);
        FB::log($root, "count results list ");
        $out = '<nav class= "gglms_resultslist">';
        $out.=outputHelper::buildResultsListAttributes((array) $root, $attributeSearchParam, $search, $uri);
        $out.='</nav>';
        FB::log($out, "out prodotto ");
        return $out;
    }

    //RS
    public static function resultsListFormato() {

        $root = outputHelper::getCountResultsListFormato();
        FB::log($root, "items results list ");
        $out = '<nav class= "gglms_resultslist">';
        $out.=outputHelper::buildResultsListFormato($root);
        $out.='</nav>';
        FB::log($out, "out formato ");
        return $out;
    }

    public static function buildmenu($items, $level = 0, $active = null) {

        // FB::log($items, "items build menu") ;
        $classlevel = "level" . $level;
        $level++;
        $badge = "";
        $out = "";


        if (sizeof($items) > 0) {
            $out = "<ul class='$classlevel list-group'>";

            foreach ($items as $item) {
                if (isset($item->titolo)) {
                    // FB::log($active."-".$item->id, "active - item id");
                    $activeclass = ($active && $active == $item->id) ? " active " : "";

                    $out .="<li class='list-group-item" . $activeclass . "'>";

                    $subUnit = outputHelper::getUnitmenu($item->id);

                    if (sizeof($subUnit) > 0)
                        $badge = ' <span class="badge">' . sizeof($subUnit) . '</span>';
                    $out.='<a class="link' . $activeclass . '" href="' . JURI::base() . "component/gglms/unita/" . $item->alias . '">' . $item->titolo . $badge . '</span></a>';
                    $out.=outputHelper::buildmenu($subUnit, $level, $active);

                    $out.="</li>";
                }
            }
            $out.="</ul>";
        }

        return $out;
    }

//RS 
    public static function buildResultsListAttributes($items, $attributeSearchParam, $search, $uri) {

        FB::log($items, " input buildResultsListAttributes");
        FB::log($search, " input buildResultsListAttributes search");
        FB::log($attributeSearchParam, " input buildResultsListAttributes attributeSearchParam ");

        $out = "";

        if (sizeof($items) > 0) {
            $route = JRoute::_('index.php?option=com_gglms&view=search&search=' . $search);
            foreach ($items as $key => $item) {
                if (sizeof($item) > 0) {
                    if (array_key_exists($key, $attributeSearchParam) && $attributeSearchParam[$key] != '') {
                        $temp = explode("&", $uri);
                        FB::log($temp, " explode uri prima");
                        foreach ($temp as $ind => $action) {
                            $action = explode("=", $action);
                            if ($action[0] == $key) {
                                $attribCloseNum = $action[1];
                                unset($temp[$ind]);
                            }

                            FB::log($action, " explode action ");
                        }
                        if ($key == 'livello')
                            switch ($attribCloseNum) {
                                case 1:
                                    $attribClose = 'Promo';
                                    break;
                                case 2:
                                    $attribClose = 'Utente';
                                    break;
                                case 3:
                                    $attribClose = 'Esperto';
                                    break;
                                case 4:
                                    $attribClose = 'Installatore';
                                    break;
                                case 5:
                                    $attribClose = 'Sviluppatore';
                                    break;
                            }
                        if ($key == 'area')
                            switch ($attribCloseNum) {
                                case 1:
                                    $attribClose = 'Funzionale';
                                    break;
                                case 2:
                                    $attribClose = 'Tecnologica';
                                    break;
                                case 3:
                                    $attribClose = 'Informativa';
                                    break;
                                case 4:
                                    $attribClose = 'Sviluppo';
                                    break;
                            }
                        if ($key == 'formato')
                            $attribClose = $attribCloseNum;
                        if ($key == 'prodotto')
                            $attribClose = $attribCloseNum;

                        FB::log($temp, " explode uri dopo");
                        // $out.='<h1 class="h1Custom"><a class="aCustom" href="' .implode("&",$temp).'">'.$key.' </a><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></h1>';
                        $out.='<h1 class="h1Custom">' . $key . '</h1>';
                        $out.='<ul><li><a class="li-aCustom" href="' . implode("&", $temp) . '">' . urldecode($attribClose) . ' </span><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a></li></ul>';

                        FB::log($out, " output buildResultsListAttributes close ");
                    }
                    else {
                        $out.='<h1 class="h1Custom">' . $key . '</h1>';
                        $out.= "<ul>";
                        foreach ($item as $attribute) {
                            $nameAtt = current($attribute);
                            if ($key == 'livello')
                                switch (current($attribute)) {
                                    case 1:
                                        $nameAtt = 'Promo';
                                        break;
                                    case 2:
                                        $nameAtt = 'Utente';
                                        break;
                                    case 3:
                                        $nameAtt = 'Esperto';
                                        break;
                                    case 4:
                                        $nameAtt = 'Installatore';
                                        break;
                                    case 5:
                                        $nameAtt = 'Sviluppatore';
                                        break;
                                }
                            if ($key == 'area')
                                switch (current($attribute)) {
                                    case 1:
                                        $nameAtt = 'Funzionale';
                                        break;
                                    case 2:
                                        $nameAtt = 'Tecnologica';
                                        break;
                                    case 3:
                                        $nameAtt = 'Informativa';
                                        break;
                                    case 4:
                                        $nameAtt = 'Sviluppo';
                                        break;
                                }

                            reset($attribute);
                            // $out.='<li><a href="' .JRoute::_('index.php?option=com_gglms&view=search&search='.$search.'&'.$key.'='.current($attribute).'').'">' .current($attribute). ' (' . $attribute['count']. ') </span></a></li>';
                            $out.='<li><a class="li-aCustom" href="' . JRoute::_($uri . '&' . $key . '=' . current($attribute) . '') . '">' . $nameAtt . ' (' . $attribute['count'] . ') </span></a></li>';
                            FB::log($out, " output buildResultsListAttributes list");
                            FB::log($nameAtt, " nameAtt ");
                        }
                        $out.="</ul>";
                    }
                }
            }
        }
        FB::log($out, " output buildResultsListAttributes");
        return $out;
    }

    //RS
    public static function getResultsListCountAttributes($counters) {
        try {
            FB::log($counters, "return getCountResultsListAttributes");
            ;
            return $counters;
        } catch (Exception $e) {
            
        }
    }

    public static function getUnitmenu($item) {
        try {

            $db = JFactory::getDbo();
            $query = $db->getQuery(true);

            $query->select('*');
            $query->from('#__gg_unit AS u');
            $query->where("u.categoriapadre=" . $item);
            $query->where("u.tipologia != 110");
            $query->order("ordinamento");





            $db->setQuery($query);
            // Check for a database error.
            if ($db->getErrorNum()) {
                JError::raiseWarning(500, $db->getErrorMsg());
            }

            $res = $db->loadObjectList();

            foreach ($res as $key => $item) {
                $sub_content = gglmsHelper::getTOTContenuti($item->id);
                $sub_unit = gglmsHelper::getSubUnit($item->id);

                if (!$sub_content && !$sub_unit)
                    unset($res[$key]);
            }

            // FB::log($res, " getUnitMenu");

            return $res;
        } catch (Exception $e) {
            
        }
    }

    public static function getContentIconStatus($prerequisiti, $stato) {

        if (!$prerequisiti) {
            echo '<img class="img-rounded" title="Contenuto non ancora visionabile" src="components/com_gglms/images/state_red.jpg"> ';
        } else {
            if ($stato == "completed") {
                echo '<img class="img-rounded" title="Contenuto già visionato" src="components/com_gglms/images/state_green.jpg">';
            } else {
                echo '<img class="img-rounded" title="Contenuto da visionare" src="components/com_gglms/images/state_grey.jpg"> ';
            }
        }
    }

    public static function convertiDurata($durata) {
        $m = floor(($durata % 3600) / 60);
        $s = ($durata % 3600) % 60;
        $result = sprintf('%02d:%02d', $m, $s);

        return $result;
    }

    public static function getContent_Footer($item) {

        FB::log($item, 'itemFooter');


        echo '<a href="component/gglms/contenuto/' . $item['alias'] . '"  title="' . htmlentities(utf8_decode($item['abstract'])) . '" >';
        ?>
        <div class="boxContentFooter img-rounded">
            <div class="boxtitle">
                <?php
                $maxlengh = 80;
                if (strlen($item['titolo']) > $maxlengh)
                    $item['titolo'] = substr($item['titolo'], 0, $maxlengh) . "...";
                echo $item['titolo'];
                ?>
            </div>

            <div class="boximg">

                <?php
                if (file_exists('../mediagg/contenuti/' . $item["id"] . '/' . $item["id"] . '.jpg'))
                    echo '<img class="img-responsive" src="../mediagg/contenuti/' . $item["id"] . '/' . $item["id"] . '.jpg">';
                else
                    echo '<img class="img-responsive" src="components/com_gglms/images/sample.jpg">';
                ?>
            </div>

            <div class="boxinfo">
                <table width="100%">
                    <tr>            
                        <td rowspan="2" width="33%"><?php echo outputHelper::getContentIconStatus($item); ?> </td>
                           <!--  <td width="33%">Durata</td>
                           <td width="33%"><?php //echo outputHelper::convertiDurata($item["durata"]);     ?></td> -->
                    </tr>
                    <tr>    
                      <!--  <td>Visite</td>
                      <td><?php //echo $item["views"];   ?></td> -->
                    </tr>
                </table>
            </div>
        </div>
        </a>
        <?php
    }

}
