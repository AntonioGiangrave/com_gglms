<?php

// no direct access
defined('_JEXEC') or die('Restricted access');

require_once 'models/libs/FirePHPCore/fb.php';
require_once JPATH_COMPONENT . '/helpers/output.php';

jimport('joomla.application.component.controller');
jimport('joomla.access.access');

class gglmsController extends JControllerLegacy {

    public function __construct($config = array()) {
        parent::__construct($config);

        // $config = JFactory::getConfig();
        //if ($config->get('debug')==1)
//        	FB::log("->costructor");
        $app = & JFactory::getApplication();
        //$user = & JFactory::getUser(569);  
        $user = & JFactory::getUser();


        // if ($config->get('debug')==1)
        // FB::log($user, " controller user");
        // if ($user->guest) {  //RS if ($user->id == 0) {
        // $msg = "Per accedere al corso è necessario loggarsi";
        // $uri      = JFactory::getURI();
        // $return      = $uri->toString();
        // $url  = JURI::base().'/component/users/?view=login';
        // $url .= '&return='.base64_encode($return);
        // $app->redirect(JRoute::_($url), $msg);
        // }         
        // if ($config->get('debug')==1)
        // 	FB::log($user, " controller user login ");
        // if($_REQUEST['notifiche']='yes')
        //     $this->setNotifiche();


        $document = JFactory::getDocument();


        JHtml::_('jquery.framework', false);
        JHtml::_('bootstrap.framework', false);
        //$document->addScript('components/com_gglms/js/jquery.min.js');
        // $document->addScript('https://code.jquery.com/jquery-1.11.3.min.js');
        // $document->addScript('components/com_gglms/js/bootstrap.min.js');
        // $document->addScript('components/com_gglms/js/bootstrapRating/js/star-rating.js');


        $document->addStyleSheet('http://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css');
        // $document->addStyleSheet('components/com_gglms/js/bootstrapRating/css/star-rating.css');
        // $document->addStyleSheet('components/com_gglms/css/bootstrap.min.css');
        // $document->addStyleSheet('components/com_gglms/css/docs.min.css');
        //RS    $document->addScript('components/com_gglms/js/search.js');
        // $document->addScriptDeclaration('jQuery.noConflict();'); //RS

        define('SMARTY_DIR', 'components/com_gglms/models/libs/smarty/smarty/');
        define('SMARTY_COMPILE_DIR', 'components/com_gglms/models/cache/compile/');
        define('SMARTY_CACHE_DIR', 'components/com_gglms/models/cache/');
        define('SMARTY_TEMPLATE_DIR', 'components/com_gglms/models/templates/');
        define('SMARTY_CONFIG_DIR', 'components/com_gglms/models/');
        define('SMARTY_PLUGINS_DIRS', 'components/com_gglms/models/libs/smarty/extras/');

        $this->registerTask('contenuto', 'contenuto');
        $this->registerTask('elemento', 'elemento');
        $this->registerTask('unita', 'unita');
        $this->registerTask('categorie', 'categorie');
        $this->registerTask('listcategories', 'listcategories');
        $this->registerTask('levelslist', 'levelslist');
        $this->registerTask('generateAlias', 'generateAlias');
        $this->registerTask('congresso', 'congresso');
        $this->registerTask('congressi', 'congressi');
        $this->registerTask('esercizio', 'esercizio');
        $this->registerTask('report', 'report');
        $this->registerTask('provagratuita', 'provagratuita');
        $this->registerTask('check_coupon', 'check_coupon');
        $this->registerTask('start_provagratuita', 'start_provagratuita');
        $this->registerTask('setTrack', 'setTrack');
        $this->registerTask('attestato', 'attestato');
        $this->registerTask('fileUpload', 'fileUpload');
        $this->registerTask('rating', 'rating');
        $this->registerTask('setPermanenza', 'setPermanenza');
        $this->registerTask('getCodiciSicurezzaGiorno', 'getCodiciSicurezzaGiorno');
        $this->registerTask('getReportCoupon', 'getReportCoupon');

        $this->registerTask('checkGroupon', 'checkGroupon');

    }

    public function setNotifiche() {


        FB::log($_REQUEST, " _REQUEST setNotifiche");
        $contenuti = outputHelper::getContenutiTipology();
        //FB::log($contenuti, " contenuti setNotifiche");
        $i = 0;
        foreach ($contenuti as $item) {
            if ($_REQUEST[$item['tipologia']] != '')
                $idTipologys[$i] = $_REQUEST[$item['tipologia']];
            $i++;
            FB::log($_REQUEST[$item['tipologia']], " setNotifiche dentro foreach  ");
        }
        FB::log($idTipologys, " idTipologys setNotifiche");

        outputHelper::deleteNotificheUtente();
        outputHelper::setNotificheUtente($idTipologys);
    }

    public function setPermanenza() {

        $japp = & JFactory::getApplication();
        require_once JPATH_COMPONENT . '/helpers/gglms.php';

        $uniqid = $_REQUEST['uniqid'];
        $permanenza = $_REQUEST['permanenza'];

        // fb::log($_REQUEST, "REQUEST");

        gglmsHelper::setPermanenza($permanenza, $uniqid);


        $japp->close();
    }

    public function rating() {
        $japp = & JFactory::getApplication();
        require_once JPATH_COMPONENT . '/helpers/rating.php';

        $func = $_REQUEST['func'];
        $id_elemento = $_REQUEST['id_elemento'];
        $star = $_REQUEST['star'];
        $rating = $_REQUEST['rating'];

        // fb::log($_REQUEST, "REQUEST");

        switch ($func) {
            case 'setRating':
                echo ratingHelper::setRating($id_elemento, $rating);
                break;

            case 'getRating':
                echo ratingHelper::getRating($id_elemento);
                break;

            case 'avgRating':
                echo ratingHelper::avgRating($id_elemento);
                break;

            case 'totRating':
                echo ratingHelper::totRating($id_elemento, $star);
                break;

            default:
                # code...
                break;
        }


        $japp->close();
    }

    public function fileUpload() {
        $japp = & JFactory::getApplication();
        // require_once('jupload/server/php/UploadHandler.php');
        $coupon = JRequest::getVar('filename');
        $allowed = array('pdf', 'xyz');

        if (isset($_FILES['upl']) && $_FILES['upl']['error'] == 0) {

            $extension = pathinfo($_FILES['upl']['name'], PATHINFO_EXTENSION);

            if (!in_array(strtolower($extension), $allowed)) {
                FB::log('Estensione non permessa');
                echo '{"status":"error1"}';
                exit;
            }
            $path = $japp->getCfg('mediagg_path');
            fb::log($path, "path");
            if (move_uploaded_file($_FILES['upl']['tmp_name'], $path . '/coupon/' . $coupon . "." . $extension)) {
                FB::log('stauts:ok');



                $model = $this->getModel('coupon');
                $model->abilitaCoupon($coupon);


                echo '{"status":"success"}';
                exit;
            }
        }
        FB::log('stauts:error2');
        echo '{"status":"error2"}';
        exit;
        $japp->close();
    }

    public function attestato() {
        //JRequest::setVar('view', 'attestato');
        $japp = & JFactory::getApplication();

        $model = & $this->getModel('attestato');

        $model->certificate();
        $japp->close();
    }

    public function elemento() {


        JRequest::setVar('view', 'elemento');
        parent::display();
    }

    public function unita() {
        JRequest::setVar('view', 'unita');
        parent::display();
    }

    public function report() {
        JRequest::setVar('view', 'report');
        parent::display();
    }

    public function esercizio() {
        JRequest::setVar('view', 'esercizio');
        parent::display();
    }

    public function provagratuita() {
        JRequest::setVar('view', 'provagratuita');
        parent::display();
    }

    public function listcategories() {


        JRequest::setVar('view', 'listcategories');
        $view = & $this->getView('listcategories', 'html');
        $view->setModel($this->getModel('webtv'));
        $view->display();



//        parent::display();
    }

    public function categorie() {
        JRequest::setVar('view', 'categorie');
        $id = JRequest::getInt('id');
        if (!empty($id)) {
            $view = & $this->getView('categorie', 'html');
            $view->setModel($this->getModel('categorie'), 'true');
            $view->setModel($this->getModel('webtv'));
            $view->display();
        } else {
            $this->setRedirect('index.php?option=com_gglms&task=listcategories');
        }
    }

    public function levelslist() {
        $app = &JFactory::getApplication();
        $level = $_POST['level'];
        $from = $_POST['from'];
        $to = $_POST['to'];


        $model = & $this->getModel('webtv');
        $contents = $model->getContentsByLevel($level, $from, $to);
        $tot = $model->totContentsByLevel($level);
        echo json_encode(array('from' => $from, 'to' => $to, 'total' => $tot, 'total_displayed' => count($contents), 'contents' => $contents));
        $app->close();
    }

    public function generateAlias() {
        $app = &JFactory::getApplication();
        $model = & $this->getModel('webtv');
        echo $model->generateContentAlias();
        echo $model->generateCategoryAlias();
        echo $model->generateCongressAlias();
        $app->close();
    }

    public function search() {
        JRequest::setVar('view', 'search');
        $view = & $this->getView('search', 'html');
        $view->setModel($this->getModel('webtv'));
        $view->setModel($this->getModel('search'));
        $view->display();
    }

    public function congresso() {
        JRequest::setVar('view', 'congresso');
        parent::display();
    }

    public function congressi() {
        JRequest::setVar('view', 'congressi');
        parent::display();
    }

    public function start_provagratuita() {
        $db = JFactory::getDBO();

        $user = JFactory::getUser();
        $userid = $user->get('id');

        $domani = date('Y-m-d G:i:s', mktime(date(G), date(i), date(s), date(m), date(d) + 1, date(Y)));
        $query = "INSERT IGNORE INTO eta_gg_coupon (coupon, id_utente, abilitato, data_utilizzo, data_abilitazione, data_scadenza) "
            . "VALUES ('provagratuita$userid', $userid, '1', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, '$domani')";

        $db->setQuery((string) $query);
        $res = $db->query();

        JRequest::setVar('view', 'provagratuita');
        parent::display();
    }

    public function checkGroupon()
    {
        $app = &JFactory::getApplication();
        $user = JFactory::getUser();
        $pas_coupon=trim(strtoupper(JRequest::getVar('coupon')));
        $pas_code=trim(strtoupper(JRequest::getVar('codiceverifica')));
        $pas_email= trim(strtolower($user->get('email')));
        $user_id= $user->get('id');

        if(strlen($pas_code)<5 || strlen($pas_coupon)<3):
            $arrayret = array();
            $arrayret["ok"] = false;
            $arrayret['error'] = "Verifica i campi inseriti";
            echo json_encode($arrayret);
            $app->close();
        endif;
        if($this->checkCouponExist($pas_coupon)):
            $arrayret = array();
            $arrayret["ok"] = false;
            $arrayret['error'] = "Il coupon è già stato usato";
            echo json_encode($arrayret);
            $app->close();
        endif;

        $user = "webmaster@bsinternational.eu";
        $password = "q9o5s0w1";
        $fopen = "http://ticket.groupon.it/api.xml?user=$user&password=$password&securitycode=$pas_code&coupon=$pas_coupon&email=$pas_email&country=IT ";
        $handle = fopen($fopen, "r");
        $result = stream_get_contents($handle);
        fclose($handle);
        $doc = new DOMDocument();
        $doc->loadXML($result);
        $xml_liv1 = $doc->getElementsByTagName("message");

        foreach ($xml_liv1 as $xml_liv1_det) {
            $stato = $xml_liv1_det->getAttribute('value');
            $opzione = $xml_liv1_det->getAttribute('opzione');
//          $titolo = $xml_liv1_det->getAttribute('titolo');
        }
        $array_groupon = array("user_id"=>$user_id, "stato"=>"$stato", "opzione"=>"$opzione", "coupon"=>"$pas_coupon", "code"=>"$pas_code", "message"=>"$result" );
        echo json_encode($this->checkStatusCoupon($array_groupon));
        $app->close();
    }

    public function checkStatusCoupon($array_groupon) {

        if ($array_groupon[stato] != "Valid and not redeemed") return $this->errorStatusCoupon($array_groupon['stato']);
        $arrayret = array();
        $db = & JFactory::getDbo();
        $query = "
        SELECT *
        FROM #__gg_opzioni_groupon
        WHERE 
        opzione =  '$array_groupon[opzione]' 
        ";
        $db->setQuery($query);
        $result_opzioni = $db->loadAssoc();
        if (!$result_opzioni){
            $arrayret["ok"] = false;
            $arrayret['error'] = "opzione non disponibile";
        }
        else {
            $model = $this->getModel('coupon');

            $arrayret["ok"] = true;
            $arrayret["opzione"] = $result_opzioni;
            $arrayret["dati_utente"]= $model->get_datiutente();
            $arrayret["mieicorsi"]= $model->get_listaCorsiFast($result_opzioni[id_corso]);
            FB::log($arrayret, "arrayret");
            $query = "
        INSERT INTO #__gg_coupon (
          coupon,
          corsi_abilitati,
          id_utente,
          creation_time,
          abilitato,
          id_iscrizione,
          data_utilizzo,
          data_abilitazione,
          durata,
          codiceverifica,
          sku)
        VALUES (
          '$array_groupon[coupon]',
          $result_opzioni[id_corso],
          $array_groupon[user_id],
          NOW(),
          1,
          '$array_groupon[message]',
          NOW(),
          NOW(),
          $result_opzioni[durata],
          '$array_groupon[code]',
          '$result_opzioni[sku]'
        )
        ";
            $db->setQuery($query);
            $db->query();

            $arrayret["id_opzione"]= $result_opzioni[id];
            $arrayret["coupon"]= $array_groupon[coupon];
            $arrayret['error'] = "Coupon valido";
        }
        return $arrayret;

    }

    public function checkCouponExist($pas_coupon){
        $db = & JFactory::getDbo();
        $query = "
        SELECT COUNT(coupon)
        FROM #__gg_coupon
        WHERE 
        coupon =  '$pas_coupon' 
        ";
        $db->setQuery($query);
        $result = $db->loadResult();
        FB::log($result, "verifica coupon");
        return $result;
    }

    public function errorStatusCoupon($stato) {

        $arrayret['error'] = "Controllare di aver inserito la mail usata per comprare il coupon e il codice sicurezza corretto";

        switch ($stato) {
            case "No data":
            case "No valid user:":
            case "Not valid":
                $arrayret['error'] = "Controllare di aver inserito la mail usata per comprare il coupon e il codice sicurezza corretto";
                break;
            case "Expired":
                $arrayret['error'] = "Coupon scaduto";
                break;
            case "Coupon not Found":
                $arrayret['error'] = "Coupon errato";
                break;
            case "Valid and redeemed":
                $arrayret['error'] = "Coupon già riscattato";
                break;
            case "Refuded":
                $arrayret['error'] = "Coupon rimborsato";
                break;
        }
        $arrayret["ok"] = false;
        $arrayret["stato"] = $stato;
//        $this->debug_msg($arrayret, __FUNCTION__);
        return $arrayret;
    }

    public function check_coupon() {
        $app = &JFactory::getApplication();

        $coupon = JRequest::getVar('coupon');
        $codiceverifica = JRequest::getVar('codiceverifica');
        $model = $this->getModel('coupon');
        $dettagli_coupon = $model->check_Coupon($coupon);
        FB::log($dettagli_coupon, "dettagliCoupon");
        if (empty($dettagli_coupon)) {
            $results['report'] = "<p> Il coupon inserito non è valido o è già stato utilizzato. (COD. 01)</p>";
            $results['valido'] = 0;
        } else {
            //EVITO QUESTO CONTROLLO PERCHE' MI SERVE ABILITARLO QUANDO CARICANO IL FILE PDF
            // if (!$dettagli_coupon['abilitato'] ) { 
            //     $results['report'] = "<p> Il coupon è in attesa di abilitazione. (COD. 03)</p>";
            //     $results['valido'] = 0;
            // } 
            // else 
            {
                $model->assegnaCoupon($dettagli_coupon['coupon'], $codiceverifica);
                // $model->iscriviUtente($dettagli_coupon['corsi_abilitati']);
                //$model->set_user_groups($dettagli_coupon['coupon']);
                $results['valido'] = 1;
                $results['report'] = "COUPON CORRETTO";
                $results['mieicorsi'] = $model->get_listaCorsiFast($dettagli_coupon['corsi_abilitati']);
                $results['coupon']= $coupon;
                $results['mieidati'] = $model->get_datiutente();

                fb::log($results);
                // $results['report'] .= "<br><h3> BENVENUTO!  <a href='/home/webtv.html'><img src='/home/images/login/webtv.png'></a></h3><br> ";
            }
        }

        echo json_encode($results);
        $app->close();
    }



    public function setTrack() {

        $user = JFactory::getUser();
        $user_id = $user->get('id');
        $id_elemento = JRequest::getInt('id', 0);
        $db = & JFactory::getDbo();

        $varName = $_REQUEST['varName'];
        $varValue = $_REQUEST['varValue'];
        $id_elemento = $_REQUEST['id_elemento'];

        $app = &JFactory::getApplication();
        $query = "
        Update #__gg_scormvars set varValue = '$varValue'
        Where 
        varName = '$varName' and 
        SCOInstanceID = $id_elemento  
        and UserID = $user_id
        ";


        // FB::log($query, "setTrack");


        $db->setQuery($query);
        $results = $db->query();


        // //echo json_encode($query);
        // echo true;
        echo $query;
        $app->close();
    }

    public function getTrack() {

        $varName = $_REQUEST['varName'];
        $user = JFactory::getUser();
        $user_id = $user->get('id');
        $id_elemento = JRequest::getInt('id', 0);
        $db = & JFactory::getDbo();

        $app = &JFactory::getApplication();


        $query = $db->getQuery(true);
        $query->select('varValue ');
        $query->from('scormvars as s');
        $query->where("s.SCOInstanceID = " . $id_elemento);
        $query->where("s.UserID = " . $user_id);
        $query->where("s.varName = '$varName'");
        $db->setQuery((string) $query, 0);
        $res = $db->loadResult();


        // echo json_encode($query);
        echo $res;
        $app->close();
    }

    public function getCodiciSicurezzaGiorno() {
        $giorno = JRequest::getVar('giorno');
        $app = &JFactory::getApplication();
        $model = $this->getModel('report');
        $where = " codiceverifica is not null "
            . " AND data_utilizzo  >   '$giorno' "
            . " AND data_utilizzo  <   DATE_ADD('$giorno' ,INTERVAL 1 DAY)";


        $results = $model->getReportCoupon($where);

        $out = '';
        foreach ($results as $result) {
            $out.=' ' . $result['codiceverifica'];
        }
        echo $out;

        $app->close();
    }

    public function setBadCode() {
        $codici = JRequest::getVar('codici');
        $app = &JFactory::getApplication();

        $codici = str_replace("  ", " ", $codici);
        $codici = explode(" ", $codici);
        $where_codici = "'".implode("','", $codici)."'";

        $model = $this->getModel('report');
        $where = "OR codiceverifica in ($where_codici)  ";

        $results = $model->setBadCode($where);

        echo implode(",", $codici);

        $app->close();
    }

    public function getReportCoupon() {
        $giorno = JRequest::getVar('giorno');
        $app = &JFactory::getApplication();
        $model = $this->getModel('report');
        $where = " codiceverifica is not null "
            . " AND data_utilizzo  >   '$giorno' "
            . " AND data_utilizzo  <   DATE_ADD('$giorno' ,INTERVAL 1 DAY)";


        $results = $model->getReportCoupon($where);

        $out = ' 
        
         <table id="contents" class="table ">
        <thead>
            <tr>
                <th>Coupon</th>
                <th>Codiceverifica</th>
                <th>Data utilizzo</th>
                <th>Corso</th>
                <th>Nome</th>
                <th>Cognome</th>
                <th>Telefono</th>
                <th>Email</th>
            </tr>
        </thead>
        <tbody>';

        foreach ($results as $coupon) {

            if($coupon['daverificare'])
                $out .= "<tr class='danger'>";
            else
                $out .= "<tr>";

            $out .= "<td>" . $coupon['coupon'] . "</td>";
            $out .= "<td>" . $coupon['codiceverifica'] . "</td>";
            $out .= "<td>" . $coupon['data_utilizzo'] . "</td>";
            $out .= "<td>" . $coupon['id_iscrizione'] . "</td>";
            $out .= "<td>" . $coupon['firstname'] . "</td>";
            $out .= "<td>" . $coupon['lastname'] . "</td>";
            $out .= "<td>" . $coupon['telefono'] . "</td>";


            if($coupon['daverificare'])
                $out .= "<td>" . $coupon['email'] . "</td>";
            else
                $out .= "<td></td>";


            $out .= "</tr>";
        }

        $out .= '</tbody>';
        $out .= '</table>';

        echo $out;

        $app->close();
    }



}

