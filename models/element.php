<?php

/**
 * WebTVContenuto Model
 * 
 * @package    Joomla.Components
 * @subpackage WebTV
 */
defined('_JEXEC') or die('Restricted access');


jimport('joomla.application.component.model');
require_once JPATH_COMPONENT . '/helpers/gglms.php';

/**
 * WebTVContenuto Model
 * 
 * @package    Joomla.Components
 * @subpackage WebTV
 */
class gglmsModelElement extends JModelLegacy {

    private $_dbg;
    private $_japp;
    private $_elemento;
    private $_user;
    private $_user_id;
    private $_id;
    private $_iscrizione;
    private $_id_elemento;

    public function __construct($config = array()) {
        parent::__construct($config);
        $this->_dbg = JRequest::getBool('dbg', 0);
        $this->_japp = JFactory::getApplication();
        $user = JFactory::getUser();
        $this->_user_id = $user->get('id');
        $this->_id_elemento = JRequest::getInt('id', 0);
        $this->SCOInstanceID = $this->_id_elemento;
        $this->_user = & JFactory::getUser();
        

        if ($user->guest) {  
          $msg = "Per accedere al corso è necessario loggarsi";

          $uri      = JFactory::getURI();
          $return      = $uri->toString();

          $url  = JURI::base().'/accedi.html';
          $url .= '&return='.base64_encode($return);
          $this->_japp->redirect(JRoute::_($url), $msg);

      }         



        //$this->initializeTrack();
        //bypasso check iscrizione
        //$this->checkIscrizione();
      // $this->_checkCoupon();
      // $this->_checkPermessi();
  }

  public function __destruct() {
    
  }

    /**
     * Ritorna tutti gli elementi dell'id corso passato in url (idc). 
     * L'id del contenuto viene letto da URL e deve essere un intero valido.
     * 
     * @return array
     */
    public function getElemento() {
        try {

            $idlink = JRequest::getInt('idlink', 0);

            //Se esiste idlink eseguo la query specifica per ricavarmi l'unita
            if ($idlink) {
                $query = 'SELECT
                c.*,
                m.idlink
                FROM  #__gg_contenuti AS c
                JOIN  #__gg_unit_map AS m on c.id= m.idcontenuto
                WHERE
                m.idlink=' . $idlink . '
                LIMIT 1';
                FB::log($query, "Query contenuto con idLink");
            }
            //altrimenti faccio la query secca per il contenuto
            else {
                $query = 'SELECT
                    *
                FROM
                    #__gg_contenuti AS c
                WHERE
                c.id=' . $this->_id_elemento . '
                LIMIT 1';
                FB::log($query, "Query contenuto SENZA idLink");
            }


            $this->_db->setQuery($query);
            if (false === ($results = $this->_db->loadAssoc()))
                throw new RuntimeException($this->_db->getErrorMsg(), E_USER_ERROR);
            $this->_elemento = empty($results) ? array() : $results;
            if (!empty($results)) {
                if (!empty($results['propedeuticita'])) {
                    // controllo propedeuticità
                    $check = $this->_chek_prerequisites($this->_user_id, $results['propedeuticita']);
                    if (!$check) {
                        echo "Propedeuticità non soddisfatta";
                        FB::error("ATTENZIONE: REQUISITI DI PROPEDEUTICITA' NON RISPETTATI!!");
                        //$this->_japp->redirect('index.php?option=com_gglms&view=corso&id=' . $results['idcorso'], 'Propedeuticità non soddisfatta per accedere a questo contenuto', 'error');
                        $this->_elemento = array();
                    }
                }
            } else {
                FB::error("ATTENZIONE: REQUISITI DI PROPEDEUTICITA' NON RISPETTATI!!");
                echo "Contenuto inesistente";
                // $this->_japp->redirect('index.php', 'Contenuto inesistente', 'error');
            }

            $this->_elemento['unita'] = array();
            if (!empty($this->_elemento['idlink'])) {

                $query = 'SELECT
                    *
                FROM  #__gg_unit AS u
                JOIN  #__gg_unit_map  AS m on u.id= m.idunita
                WHERE
                m.idlink =' . $this->_elemento['idlink'] . '
                LIMIT 1';

                FB::log($query, "Query per caricare unità da id link");

                $this->_db->setQuery($query);
                if (false === ($results = $this->_db->loadAssoc()))
                    throw new RuntimeException($this->_db->getErrorMsg(), E_USER_ERROR);
                $this->_elemento['unita'] = $results;
            }else {
                $query = 'SELECT
                    *
                FROM  #__gg_unit AS u
                JOIN  #__gg_unit_map  AS m on u.id= m.idunita
                WHERE
                m.idcontenuto =' . $this->_elemento['id'] . '
                LIMIT 1';

                FB::log($query, "Query per caricare unità da id contenuto");

                $this->_db->setQuery($query);
                if (false === ($results = $this->_db->loadAssoc()))
                    throw new RuntimeException($this->_db->getErrorMsg(), E_USER_ERROR);
                $this->_elemento['unita'] = $results;
            }
        } catch (Exception $e) {
            $this->_elemento = array();
        }
        $this->_elemento['track'] = $this->getTrack();

        //se non ha di track e non è uno scorm
        if (!$this->_elemento['track'] && $this->_elemento['tipologia'] != 3) {
            $this->initializeTrack();
            $this->_elemento['track'] = $this->getTrack();
        }

        FB::log($this->_elemento, " return getElemento ");


        return $this->_elemento;
    }

    /*
      Carica i parametri del contentuo
     */

      public function getParametri() {

        try {

            $query = '
            SELECT
            m.idcontenuto,
            m.idparametro,
            p.parametro, 
            p.alias
            FROM
            vxvos_gg_param_map AS m
            Inner Join vxvos_gg_param AS p ON p.id = m.idparametro
            WHERE
            m.idcontenuto = ' . $this->_id_elemento . '
            ORDER BY
            p.ordinamento ASC';

            FB::log($query, "getParametri");

            $this->_db->setQuery($query);
            if (false === ($results = $this->_db->loadAssocList()))
                throw new RuntimeException($this->_db->getErrorMsg(), E_USER_ERROR);
        } catch (Exception $e) {

            $results = array();
        }

        return $results;
    }

    public function getAllegati() {

        try {

            $query = '
            select map.idcontenuto as id, files.id as idfile, files.name as nome, files.filename as filename from 
            vxvos_gg_files as files
            join vxvos_gg_files_map as map on files.id = map.idfile
            WHERE
            map.idcontenuto = ' . $this->_id_elemento . '
            ORDER BY
            files.name  ASC';

            FB::log($query, "getAllegati");

            $this->_db->setQuery($query);
            if (false === ($results = $this->_db->loadAssocList()))
                throw new RuntimeException($this->_db->getErrorMsg(), E_USER_ERROR);
        } catch (Exception $e) {

            $results = array();
        }

        return $results;
    }

    /**
     * Legge il quiz da file XML.
     * Cerca il file in  "$content_path/$itemid/$itemid.xml".
     * 
     * @param int $itemid ID del contenuto di cui si vogliono i jumper. 
     * @param string $content_path Percorso dove cercare il file XML.
     * @return array 
     */
    public function getQuizXML($path) {
        try {
            $path .='/quiz.xml';
            $filepath = JPATH_BASE . "/" . $path;


            if (!file_exists($filepath)) {
                FB::warn("Il file QUIZ.XML non esiste, ma nessun problema, procedo senza di lui!");
                return array();
            }

            $domande = array();
            $risposte = array();
            $xml = new DOMDocument();
            $xml->load($path);
            $quiz = $xml->getElementsByTagName('quiz');
            $i = 0;

            foreach ($quiz as $point) {
                foreach ($point->childNodes as $node) {
                    $domande[$i]['id'] = $i;
                    if ('Time' == $node->nodeName)
                        $domande[$i]['time'] = $node->nodeValue;
                    elseif ('domanda' == $node->nodeName)
                        $domande[$i]['domanda'] = ($node->nodeValue);
                    elseif ('corretta' == $node->nodeName)
                        $corretta = $node->nodeValue;
                    elseif ('risposta1' == $node->nodeName) {
                        $domande[$i]['risposte'][1]['r'] = ($node->nodeValue);
                        $domande[$i]['risposte'][1]['c'] = (1 == $corretta) ? r : w;
                    } elseif ('risposta2' == $node->nodeName) {
                        $domande[$i]['risposte'][2]['r'] = ($node->nodeValue);
                        $domande[$i]['risposte'][2]['c'] = (2 == $corretta) ? r : w;
                    } elseif ('risposta3' == $node->nodeName) {
                        $domande[$i]['risposte'][3]['r'] = ($node->nodeValue);
                        $domande[$i]['risposte'][3]['c'] = (3 == $corretta) ? r : w;
                    }
                    shuffle($domande[$i]['risposte']);
                }
                $i++;
            }
            unset($xml);
            unset($quiz);

            return $domande;
        } catch (Exception $e) {
            FB::error($e);
        }
        return 0;
    }

    /**
     * Legge i jumper da file XML.
     * Cerca il file in  "$content_path/$itemid/$itemid.xml".
     * 
     * @param int $itemid ID del contenuto di cui si vogliono i jumper. 
     * @param string $content_path Percorso dove cercare il file XML.
     * @return array 
     */
    public function getJumperXML($path) {
        try {


            $id = JRequest::getInt('id', 0);

            $path .='/' . $id . '.xml';

            FB::log($path, "path");


            if (!file_exists($path)) {
                FB::ERROR("Il file CUE_POINTS.XML non esiste! Nessun jumper verrà visualizzato e le eventuali slide non saranno sincronizzate.");
                return array();
            }

            // if (empty($itemid) || !filter_var($itemid, FILTER_VALIDATE_INT))
            //     throw new BadMethodCallException('Parametro non valido, atteso un intero valido - Jumper', E_USER_ERROR);
            // if (empty($jumpers) || !is_array($jumpers))
            //     throw new BadMethodCallException('Parametro non valido, atteso un array valido - Jumper', E_USER_ERROR);


            $jumpers = array();
            $xml = new DOMDocument();
            $xml->load($path);
            $cue_points = $xml->getElementsByTagName('CuePoint');
            $i = 0;


            foreach ($cue_points as $point) {
                foreach ($point->childNodes as $node) {
                    if ('Time' == $node->nodeName)
                        $jumpers[$i]['tstart'] = $node->nodeValue;
                    elseif ('Name' == $node->nodeName)
                        $jumpers[$i]['titolo'] = $node->nodeValue;
                }
                $i++;/** @todo se il nodo non contiene time e name incremento i e non faccio nessun controllo se il jumper abbia 2 elementi tstart e titolo */
            }
            unset($xml);
            unset($cue_points);
            return $jumpers;
        } catch (Exception $e) {
            FB::error($e, "error");
        }
        return 0;
    }

    /**
     * Crea il file VTT.
     * 
     * @param int $itemid
     * @param array $jumpers 
     */
    public function createVTT_slide($itemid, $path, $jumpers) {

        // if (empty($itemid) || !filter_var($itemid, FILTER_VALIDATE_INT))
        //     throw new BadMethodCallException('Parametro non valido, atteso un intero valido - VTT', E_USER_ERROR);
        // if (empty($jumpers) || !is_array($jumpers))
        //     throw new BadMethodCallException('Parametro non valido, atteso un array valido - VTT', E_USER_ERROR);

        $filepath = JPATH_BASE . "/" . $path . "/";


        //if (!file_exists($filepath . "vtt_slide.vtt")) {
        $values = array();
        $i = 0;
        $vtt = "";
        $pathimmagini = "../../../../" . $path;

        foreach ($jumpers AS $jumper) {
            //$values[] = '(' . $itemid . ', ' . $jumper['tstart'] . ', \'' . $jumper['titolo'] . '\')';
            $values[$i]['a'] = "$i\n";
            $values[$i]['b'] = $this->convertiDurata($jumper['tstart']);
            $values[$i]['c'] = NULL;
            $shift = $i + 1;
            $values[$i]['d'] = $pathimmagini . "/slide/Slide" . $shift . ".jpg\n\n";
            echo $pathimmagini . "/slide/Slide" . $shift . ".jpg\n\n";
            $i++;
        }

        for ($i = 0; $i < count($values); $i++) {
            if ($i == 0)
                $values[$i]['b'] = "00:00:00";

            if ($i != count($values))
                $values[$i]['c'] = $values[$i + 1]['b'] . "\n";

            if ($i == count($values) - 1)
                $values[$i]['c'] = "99:00:00\n";

            $vtt.=$values[$i]['a'] . $values[$i]['b'] . " --> " . $values[$i]['c'] . $values[$i]['d'];
        }



        $file = $filepath . "vtt_slide.vtt";

        $var = fopen($file, "w");
        fwrite($var, $vtt);
        fclose($var);
        //}
        return 0;
    }

    /**
     * Crea il file VTT.
     * 
     * @param int $itemid
     * @param array $jumpers 
     */
    public function createVTT_capitoli($itemid, $path, $jumpers) {


        // if (empty($itemid) || !filter_var($itemid, FILTER_VALIDATE_INT))
        //     throw new BadMethodCallException('Parametro non valido, atteso un intero valido', E_USER_ERROR);
        // if (empty($jumpers) || !is_array($jumpers))
        //     throw new BadMethodCallException('Parametro non valido, atteso un array valido', E_USER_ERROR);

        $filepath = JPATH_BASE . "/" . $path . "/";



        //if (!file_exists($filepath . "vtt_capitoli.vtt")) {
        $values = array();
        $i = 0;
        $vtt = "";
        $pathimmagini = "../../../.." . JURI::base(true) . "/" . $path;

        foreach ($jumpers AS $jumper) {
            //$values[] = '(' . $itemid . ', ' . $jumper['tstart'] . ', \'' . $jumper['titolo'] . '\')';
            $values[$i]['a'] = "$i\n";
            $values[$i]['b'] = $this->convertiDurata($jumper['tstart']);
            $values[$i]['c'] = NULL;
            $shift = $i + 1;
            $values[$i]['d'] = $this->convertiDurata($jumper['titolo']) . "\n\n";

            $i++;
        }

        for ($i = 0; $i < count($values); $i++) {
            if ($i == 0)
                $values[$i]['b'] = "00:00:00";

            if ($i != count($values))
                $values[$i]['c'] = $values[$i + 1]['b'] . "\n";

            if ($i == count($values) - 1)
                $values[$i]['c'] = "99:00:00\n";

            $vtt.=$values[$i]['a'] . $values[$i]['b'] . " --> " . $values[$i]['c'] . $values[$i]['d'];
        }



        $file = $filepath . "vtt_capitoli.vtt";

        $var = fopen($file, "w");
        fwrite($var, $vtt);
        fclose($var);
        // }
        return 0;
    }

    public function convertiDurata($durata) {
        $h = floor($durata / 3600);
        $m = floor(($durata % 3600) / 60);
        $s = ($durata % 3600) % 60;
        $result = sprintf('%02d:%02d:%02d', $h, $m, $s);

        return $result;
    }

    public function initializeTrack() {

        FB::log("->initializeTrack");
        $query = $this->_db->getQuery(true);
        $query->select('varValue ');
        $query->from('#__gg_scormvars as s');
        $query->where("s.SCOInstanceID = " . $this->_id_elemento);
        $query->where("s.UserID = " . $this->_user_id);
        $query->where("s.varName = 'cmi.core.lesson_status'");
        $this->_db->setQuery((string) $query);
        $res = $this->_db->loadResult();

        if (!$res) {

            $mod_track = array(1 => "completed", 2 => "init");

            $start_data = array(
                'cmi.core.lesson_status' => $mod_track[$this->_elemento["mod_track"]],
                'cmi.core.total_time' => NULL
                );


            FB::log($start_data, "InitializeTRack -> Start_data");

            foreach ($start_data as $key => $value) {
                $query = "INSERT IGNORE INTO #__gg_scormvars (SCOInstanceID, UserID, varName, varValue) VALUES ($this->_id_elemento ,  $this->_user_id , '$key', '$value' )";
                FB::log($query, "initializeTrack -> res");
                $this->_db->setQuery($query);
                $this->_db->query();
            }
        }
        return true;
    }

    /*
     * Aggiunge il recordo nella tabella Track impostando lo stato di superamento a 0
     *  
     * 
     */

    public function setTrack($varName = NULL, $varValue = NULL) {
        /*
          TRASFERITO TUTTO SUL CONTROLLER
          try {

          $ip = $_SERVER['REMOTE_ADDR'];


          $query = $this->_db->getQuery(true);
          $query->select('SELECT varValue ');
          $query->from('#__gg_scormvars as s');
          $query->where("s.SCOInstanceID = " . $this->_id_elemento);
          $query->where("s.UserID = " . $this->_user_id);
          $query->where("s.varName = 'cmi.core.lesson_status'");
          $this->_db->setQuery((string) $query, 0);
          $res = $this->_db->loadResult();

          if(!$res){


          $start_data = array('cmi.core.lesson_status' => 'NULL',
          'cmi.core.total_time' => 'NULL');


          foreach ($start_data as $key => $value) {
          $query = '
          INSERT INTO #__gg_scormvars (SCOInstanceID, UserID, varName, varValue, ip_address )
          VALUES
          (' . $this->_id . ', ' . $this->_user_id . ', 1, NOW(), "' . $info . '" )';            }

          }


          // $query = '
          // INSERT IGNORE INTO #__gg_track
          // (id_elemento, id_utente, stato, data, ip_address )
          // VALUES
          // (' . $this->_id . ', ' . $this->_user_id . ', 1, NOW(), "' . $info . '" )';


          $query = '
          Update #__gg_scormvars set varValue = "passed"
          Where
          SCOInstanceID = $this->_id_elemento
          and UserID = $this->_user_id
          and varName = "cmi.core.lesson_status"
          ';


          FB::log($query, "setTrack");


          $this->_db->setQuery($query);
          if (false === ($results = $this->_db->query()))
          throw new RuntimeException($this->_db->getErrorMsg(), E_USER_ERROR);
          } catch (Exception $e) {

          } */
      }

      public function getTrack() {
        try {

            $user = & JFactory::getUser();
            $user_id = $user->get('id');

            $query = '
            SELECT 
            varName, varValue
            FROM
                #__gg_scormvars
            WHERE SCOInstanceID= ' . $this->_id_elemento . ' AND 
            UserID=' . $this->_user_id;

            FB::log($query, "getTrack");

            $this->_db->setQuery($query);
            if (false === ($results = $this->_db->loadAssocList('varName', 'varValue')))
                throw new RuntimeException($this->_db->getErrorMsg(), E_USER_ERROR);
        } catch (Exception $e) {

            $results = array();
        }

        return $results;
    }

    private function _add_ending_slash($path) {
        return $path . ((substr($path, strlen($path) - 1, 1) != '/') ? '/' : '');
    }

    public function checkIscrizione() {

        try {
            $query = '
            SELECT
            i.id_utente,
            i.id_corso
            FROM
                        #__gg_corsi_versione AS v
            Inner Join #__gg_moduli AS m ON m.id_corso = v.id
            Inner Join #__gg_elementi AS e ON e.id_modulo = m.id
            Inner Join #__gg_iscrizioni AS i ON v.id_corso = i.id_corso
            WHERE 
            e.id = ' . $this->_id . '
            AND
            i.id_utente =  ' . $this->_user_id . '
            ';



            $this->_db->setQuery($query);
            if (false === ($results = $this->_db->loadAssoc()))
                throw new RuntimeException($this->_db->getErrorMsg(), E_USER_ERROR);
            $this->_iscrizione = empty($results) ? array() : $results;
        } catch (Exception $e) {

            $this->_iscrizione = array();
        }
        if (!$this->_iscrizione) {
            //TODO Personalizzare il messaggio per i non registrati
            $msg = "Non sei iscritto a questo corso. Se disponi di un coupon puoi riscattarlo qui sotto.";

            $this->_japp->redirect(JRoute::_('index.php?option=com_gglms&view=coupon'), $msg, 'error');
        }
    }

    private function _chek_prerequisites($user_id, $prerequisites) {
        try {
            $query = 'SELECT id, path, tipologia FROM #__gg_elementi WHERE id IN (' . $prerequisites . ')';
            $this->_db->setQuery($query);
            if (false === ($results = $this->_db->loadAssocList()))
                throw new RuntimeException($this->_db->getErrorMsg(), E_USER_ERROR);
            if (empty($results))
                throw new RuntimeException('Impossibile recuperare le informazioni di propedeuticità per gli elementi ' . $prerequisites, E_ERROR);
            foreach ($results as $r) {
                if ($r['tipologia'] == 'contenuto') {
                    $query = 'SELECT stato FROM #__gg_track WHERE id_elemento=' . $r['id'] . ' AND id_utente=' . $user_id . ' AND stato=1 LIMIT 1';
                    $this->_db->setQuery($query);
                    if (false === ($check = $this->_db->loadAssoc()))
                        throw new RuntimeException($this->_db->getErrorMsg(), E_ERROR);
                    if (empty($check))
                        return 0;
                } elseif ($r['tipologia'] == 'quiz') {
                    $query = 'SELECT c_passed FROM #__quiz_r_student_quiz WHERE c_student_id=' . $user_id . ' AND c_quiz_id=' . $r['path'] . ' AND c_passed=1 LIMIT 1';
                    $this->_db->setQuery($query);
                    if (false === ($check = $this->_db->loadAssoc()))
                        throw new RuntimeException($this->_db->getErrorMsg(), E_ERROR);
                    if (empty($check))
                        return 0;
                }
            }
            return 1;
        } catch (Exception $e) {
            return 0;
        }
    }

    private function _checkPermessi() {


        $user_id = $this->_user->get('id');
        $groups = JAccess::getGroupsByUser($user_id, true);

        $query = '
        SELECT
        c.*
        FROM
                        #__gg_contenuti AS c
        Left Join #__gg_contenuti_acl AS acl ON acl.id_contenuto = c.id
        
        WHERE
        
        c.id= ' . $this->_id_elemento . ' and 
        id_group in (' . implode(",", $groups) . ')  and 
        c.pubblicato = 1
        ';


        $this->_db->setQuery($query);
        if (false === ($results = $this->_db->loadAssocList()))
            throw new RuntimeException($this->_db->getErrorMsg(), E_USER_ERROR);


        if (!$results) {
            $msg = "Non sei autorizzato a guardare questo contenuto. ";
            //TODO Sistemare il messaggio di mancato accesso al corso e di inserimento coupon
            //echo $msg;
            //echo $query;
            $this->_japp->redirect(JRoute::_('index.php?option=com_gglms'), $msg, 'error');
        }
    }


  public function getSku() {

        try {
            $user_id = $this->_user->get('id');


            $query = "  SELECT
                            sku
                        FROM  #__gg_coupon AS u
                        WHERE
                        u.id_utente = " . $user_id . "
                        and 
                        u.sku is not null
                        LIMIT 1 ";

                        

                        $this->_db->setQuery($query);
                        if (false === ($results = $this->_db->loadResult()))
                            throw new RuntimeException($this->_db->getErrorMsg(), E_USER_ERROR);
                        
                        // FB::log($query, "querygetSku");
                        // FB::log($results, "getSku");
                      
                        return $results;
                    } catch (Exception $e) {
                        FB::log($e);
                    }
                }





    public function _findmyunit($idunit) {

        try {
            $query = "  SELECT
            id, categoriapadre
                        FROM  #__gg_unit AS u
                        WHERE
                        u.id = " . $idunit . "
                        LIMIT 1 ";

                        FB::log($query, "Query _findmyunit");

                        $this->_db->setQuery($query);
                        if (false === ($results = $this->_db->loadAssocList()))
                            throw new RuntimeException($this->_db->getErrorMsg(), E_USER_ERROR);

                        $resunit;
                        if ($results) {
                            foreach ($results as $key => $value) {
                                $resunit.= "," . $value['id'];
                                if ($value['categoriapadre'] != 1)
                                    $resunit .= $this->_findmyunit($value['categoriapadre']);
                            }
                        }
                        return $resunit;
                    } catch (Exception $e) {
                        FB::log($e);
                    }
                }

                private function _checkCoupon() {
                    try {

                        $user_id = $this->_user->get('id');

            //A QUALI UNITA APPARTIENE QUESTO CONTENUTO 
                        $query = "  SELECT idunita 
                        FROM #__gg_unit_map
                        WHERE idcontenuto = " . $this->_id_elemento;

                        $this->_db->setQuery($query);
                        $results = $this->_db->loadColumn();

            //TROVO TUTTE LE UNIT PADRE DI QUELLA A CUI APPARTIENE QUESTO CONTENUTO
                        $myunits = "";
                        foreach ($results as $key => $value) {
                            $myunits .= $this->_findmyunit($value);
                        }

                        $myunits = explode(",", $myunits);
                        FB::log($myunits, "albero unit");

            //ESISTE UN COUPON CON CF DELL UTENTE (valido)?
                        $query = 'SELECT 
                        DATE_ADD(c.data_utilizzo, INTERVAL c.durata DAY) < NOW() AS scaduto,
                        c.coupon,
                        c.id_utente,
                        c.corsi_abilitati
                FROM #__gg_coupon AS c
                INNER JOIN #__comprofiler AS u ON u.cb_codicefiscale like c.coupon
                WHERE u.id=' . $user_id . ' AND c.data_abilitazione < NOW()';
                
                
            //$where =  ' AND c.corsi_abilitati REGEXP \'[[:<:]]' . $this->_id_corso . '[[:>:]]\'' ;
                $where .= " AND (1=0 ";
                    foreach ($myunits as $unit) {
                        $where .= ' OR corsi_abilitati REGEXP \'[[:<:]]' . $unit . '[[:>:]]\'';
                    }
                    $where .= ") ";
$query .=$where;

$query .= ' LIMIT 1';

FB::log($query, "Query coupon <-> CF");

$this->_db->setQuery($query);
$results = $this->_db->loadAssoc();
            if (!empty($results['coupon'])) { // si' esiste
                if (1 == $results['scaduto']) { // ma e' scaduto
                $this->_japp->redirect(JRoute::_('index.php?option=com_gglms&view=coupon'), $msg, 'error');
                return 0;
                } elseif (empty($results['id_utente'])) { // non e' scaduto, ed e' il primo accesso
                $query = 'UPDATE #__gg_coupon SET id_utente=' . $user_id . ', data_utilizzo=NOW() WHERE coupon=\'' . $results['coupon'] . '\' LIMIT 1';
                $this->_db->setQuery($query);
                $this->_db->query();

                return 1;
                } else { // non e' scaduto e non e' al primo accesso
                return 1;
            }
        }



            //VERIFICO SE ESISTE UN COUPON CHE MI FA ACCEDERE A UNA DELLE UNIT PADRE DEL CONTENUTO 

        $query = 'SELECT
        DATE_ADD(data_utilizzo,INTERVAL durata DAY) < NOW() AS scaduto
                                FROM #__gg_coupon
                                WHERE id_utente=' . $user_id . '
                                AND abilitato=1 ';

                                $where = "AND (1=0 ";
                                    foreach ($myunits as $unit) {
                                        $where .= ' OR corsi_abilitati REGEXP \'[[:<:]]' . $unit . '[[:>:]]\'';
                                    }
                                    $where .= ") ";
$query .=$where;
FB::log($query, "Query coupon");
$this->_db->setQuery($query);
if (false === ($results = $this->_db->loadAssocList())) {
    FB::log($results, "results");
    throw new RuntimeException($this->_db->getErrorMsg(), E_USER_ERROR);
}

$not_go_corso = 0;
$a_morte_i_coupon_scaduti = 0;
FB::log($not_go_corso, "not_go_corso");

            //CONTROLLO I RISULTATI
foreach ($results as $row) {
                $a_morte_i_coupon_scaduti |= $row['scaduto']; // almeno 1 a 1
                $not_go_corso |=!$row['scaduto']; // almeno 1 a 0
            }


            //SE NON C'E' ALMENO UN COUPON VALIDO REDIRECT A COUPON 
            if (!$not_go_corso && !$this->_dbg) {
                $msg = "Il coupon che utilizzi è scaduto o non hai ancora inserito un coupon.";
                // echo $query;
                $this->_japp->redirect(JRoute::_('index.php?option=com_gglms&view=coupon'), $msg, 'error');
                FB::error("NON PUOI VEDERE QUESTO CONTENUTO");
            }
        } catch (Exception $e) {
            if ($this->_dbg)
                $this->_japp->enqueueMessage($e->getMessage(), 'error');
        }
    }

    private function _checkCoupon_OLD() {
        try {
            $user_id = $this->_user->get('id');


            // esiste un coupon con cf dell'utente (valido)?
            $query = 'SELECT 
            DATE_ADD(c.data_utilizzo, INTERVAL c.durata DAY) < NOW() AS scaduto,
            c.coupon,
            c.id_utente,
            c.corsi_abilitati
                FROM #__gg_coupon AS c
                INNER JOIN #__comprofiler AS u ON u.cb_codicefiscale like c.coupon
                WHERE u.id=' . $user_id . ' AND c.data_abilitazione < NOW()
                AND c.corsi_abilitati REGEXP \'[[:<:]]' . $this->_id_corso . '[[:>:]]\'
                LIMIT 1';

                FB::log($query, "_checkCoupon - CF");
                $this->_db->setQuery($query);
                $results = $this->_db->loadAssoc();
            if (!empty($results['coupon'])) { // si' esiste
                if (1 == $results['scaduto']) { // ma e' scaduto
                $this->_japp->redirect(JRoute::_('index.php?option=com_gglms&view=coupon'), $msg, 'error');
                return 0;
                } elseif (empty($results['id_utente'])) { // non e' scaduto, ed e' il primo accesso
                $query = 'UPDATE #__gg_coupon SET id_utente=' . $user_id . ', data_utilizzo=NOW() WHERE coupon=\'' . $results['coupon'] . '\' LIMIT 1';
                $this->_db->setQuery($query);
                $this->_db->query();

                return 1;
                } else { // non e' scaduto e non e' al primo accesso
                return 1;
            }
            } else { // esiste un coupon?
                $query = 'SELECT
                DATE_ADD(data_utilizzo,INTERVAL durata DAY) < NOW() AS scaduto
                    FROM #__gg_coupon
                    WHERE id_utente=' . $user_id . '
                    AND abilitato=1
                    AND corsi_abilitati REGEXP \'[[:<:]]' . $this->_id_corso . '[[:>:]]\'';
                    if ($this->_dbg)
                        $this->_japp->enqueueMessage($query);


                    $this->_db->setQuery($query);
                    if (false === ($results = $this->_db->loadAssocList()))
                        throw new RuntimeException($this->_db->getErrorMsg(), E_USER_ERROR);
                    $go_corso = 0;
                    $a_morte_i_coupon_scaduti = 0;
                    foreach ($results as $row) {
                    $a_morte_i_coupon_scaduti |= $row['scaduto']; // almeno 1 a 1
                    $go_corso |=!$row['scaduto']; // almeno 1 a 0
                }

                if (!$go_corso) {
                    $msg = "Il coupon che utilizzi è scaduto";
                    //TODO Sistemare il messaggio di mancato accesso al corso e di inserimento coupon
                    //echo $msg;
                    //echo $query;
                    // $this->_japp->redirect(JRoute::_('index.php?option=com_gglms&view=coupon'), $msg, 'error');
                }
            }
        } catch (Exception $e) {
            if ($this->_dbg)
                $this->_japp->enqueueMessage($e->getMessage(), 'error');
        }
    }

    public function getTemplates() {
        try {

            // esiste un coupon con cf dell'utente (valido)?
            $query = '  SELECT  *
            FROM #__gg_contenuti_tipology ';

            $this->_db->setQuery($query);
            $results = $this->_db->loadAssocList("id");
            return $results;
        } catch (Exception $e) {
            return 0;
        }
    }

    public function getFiles() {

        $query = $this->_db->getQuery(true);
        try {
            $query->select('f.* , t.tipologia');
            $query->from('#__gg_files as f');
            $query->join('left', '#__gg_files_map as m on f.id=m.idfile');
            $query->join('left', '#__gg_files_type as t on t.id = f.type');
            $query->where("m.idcontenuto = " . $this->_id_elemento);
            $query->order("t.id");

            $this->_db->setQuery((string) $query, 0);
            $res = $this->_db->loadAssocList();

            FB::log($res, "listaFileAssociati");
        } catch (Exception $e) {
            //debug::exception($e);
        }

        return $res;
    }

}
