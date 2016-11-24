<?php

/**
 * WebTVContenuto Model
 * 
 * @package    Joomla.Components
 * @subpackage WebTV
 */
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');
require_once('libs/contenuto.lib.php');

/**
 * WebTVContenuto Model
 * 
 * @package    Joomla.Components
 * @subpackage WebTV
 */
class gglmsModelUnita extends JModelLegacy {

	private $_dbg;
	private $_japp;
	private $_id;
	private $_userid;

	public function __construct($config = array()) {
		parent::__construct($config);
		$this->_dbg = JRequest::getBool('dbg', 0);
		$this->_japp = & JFactory::getApplication();
		$this->_content = array();
		$this->_id = JRequest::getInt('id', 0);


		$this->_db = & JFactory::getDbo();


		$user = JFactory::getUser();

		$this->_userid = $user->get('id');


    //  $this->_user = & JFactory::getUser();
    //  if ($this->_user->guest) {
    //         //TODO Personalizzare il messaggio per i non registrati
    //     $msg = "Per accedere al corso è necessario loggarsi";

    //         //TODO Sistemare per fare in modo che dopo il login torni al corso

    //     $uri      = JFactory::getURI();
    //     $return      = $uri->toString();

    //     $url  = JURI().'home.html'; 
    //     // $url .= '&return='.base64_encode($return);
    //     $this->_japp->redirect(JRoute::_($url), $msg);

    //     FB::error("E' NECESSARIO LOGGARSI");
    // }

        // if (!$this->checkCoupon()) {
        //     $msg = "Inserisci qui il tuo coupon!";
        //     $this->_japp->redirect(JRoute::_('/home/component/webtv/coupon'), $msg, 'error');
        // }
}

public function __destruct() {
  unset($this->_dbg);
  unset($this->_content);
  unset($this->_id);
}

    /**
     * Verifica quali formati sono presenti nella cartella. 
     * L'id del contenuto viene letto da URL e deve essere un intero valido.
     * 
     * @return array
     */
    public function checkMedia() {
    	return get_fs_media($this->_id, 'mediatv/_contenuti/');
    }


 /**
     * Ritorna le informazioni per l'unita richiesto. 
     * L'id dell'unita viene letto da URL e deve essere un intero valido.
     * 
     * @return array
     */
 public function getUnita($where = null, $limit = null) {
 	try {

 		if (!empty($this->_content)) {
 			return $this->_content;
 		}

 		

 		$query = 'SELECT *
        FROM #__gg_unit as u ';

        if(!$where)
            $query .=  'WHERE id = '. $this->_id ;
        else
            $query .= $where;

        FB::log($query, "getUnita -- Principale");

        $this->_db->setQuery($query);
        if (false === ($unita = $this->_db->loadAssoc()))   
        	throw new RuntimeException($this->_db->getErrorMsg(), E_USER_ERROR);
        if (empty($unita))
        	throw new DomainException('Nessun contenuto trovato', E_USER_NOTICE);

//         $unita['unitaFiglio'] = array();
// >        $query = 'SELECT *  FROM #__gg_unit WHERE categoriapadre = '.$this->_id .' order by ordinamento ';

//         FB::log($query, "getUnita - unitaFiglio");
//         $this->_db->setQuery($query);
//         if (false === ($results = $this->_db->loadAssocList()))
//         	throw new RuntimeException($this->_db->getErrorMsg(), E_USER_ERROR);
//         $unita['unitaFiglio'] = $results;

//         $unita['unitaPadre'] = array();
//         $query = 'SELECT * FROM #__gg_unit WHERE id = '.$unita['categoriapadre'] .' order by ordinamento ';

        // echo $query;

        // FB::log($query, "getUnita - unitaPadre");
        // $this->_db->setQuery($query);
        // if (false === ($results = $this->_db->loadAssocList()))
        // 	throw new RuntimeException($this->_db->getErrorMsg(), E_USER_ERROR);
        // $unita['unitaPadre'] = $results;


        // if($unita['tipologia']==2){

        	// $unita['contenutiUnita'] = array();

        	// $unita['contenutiUnita'] = $this->getContenuti($this->_id);
        // }

        // foreach ($unita['unitaFiglio'] as &$item) {

        //     // FB::log($item, "item - unita figlio");

        //     $item['contenutiUnita'] = array();

        //     $item['contenutiUnita'] = $this->getContenuti($item['id']);
        // }




        FB::info($unita, "Unita");
    } catch (Exception $e) {
    	FB::error($e);
    }
    return $unita;
}






    /**
     * Cerca i jumper del contenuto corrente prima su database e poi su file XML.
     * Se i jumper si trovano solo su XML questi vengono importati anche su database.
     * Il metodo ritorna un array contenente i jumper per il video: ogni jumper è caratterizzato
     * da un 'tstart' tempo di inizio del jumper (in secondi dall'inizio del video) e 
     * da 'titolo' nome del jumper.
     * Esempio:
     * <code>
     * array (
     *      0 => array (
     *          'tstart' => '0',
     *          'titolo' => 'The UK, Britain and Ireland - English Version',
     *      ),
     *      1 => array (
     *          'tstart' => '6',
     *          'titolo' => 'Introduction',
     *      )
     * )
     * </code>
     * 
     * @return array
     */
    public function getJumper() {
    	try {
    		$this->_id = $this->_get_content_id();
            // prelevo da DB
            //$jumpers = $this->_getJumperDB($this->_id);
            //if (empty($jumpers)) {
                // leggo XML
    		debug::msg("Leggo l'xml");
    		$jumpers = $this->_getJumperXML($this->_id, '/var/www/vhosts/e-taliano.tv/httpdocs/home/mediatv/_contenuti/');
                if (empty($jumpers)) // lancio il sasso e nascondo la mano
                throw new DomainException('Impossibile trovare alcun jumper del contenuto ' . $this->_id);
                else // aggionro DB
                $this->_insertJumperDB($this->_id, $jumpers);
            //}
                if ($this->_dbg)
                	$this->_japp->enqueueMessage('<pre>' . var_export($jumpers, true) . '</pre>');
                return $jumpers;
            } catch (Exception $e) {
            	debug::exception($e);
            }
            return array();
        }










    /**
     * Ritorna l'id del contenuto letto da URL.
     * 
     * @return int
     */
    private function _get_content_id() {
    	if (isset($this->_id))
    		return $this->_id;
    	$id = JRequest::getInt('id', 0);
    	$id = filter_var($id, FILTER_VALIDATE_INT, array('min_range' => 1));
    	if (is_null($id))
    		throw new BadMethodCallException('ID errato, atteso un intero valido', E_USER_ERROR);
    	return $id;
    }

    /**
     * Si appoggia alla tabella #__gg_contenuti_jumper per recuerare i jumper legati al contenuto
     * 
     * <code>
     * CREATE TABLE #__gg_contenuti_jumper (
     *      id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
     *      id_contenuto INT(10) NOT NULL,
     *      tstart SMALLINT UNSIGNED NOT NULL DEFAULT 0,
     *      titolo VARCHAR(255) NOT NULL,
     *      INDEX(id_contenuto)
     * ) ENGINE INNODB;
     * 
     * @param int $itemid ID del contenuto di cui si vogliono i jumper 
     * @return array
     */
    private function _getJumperDB($itemid) {
    	try {
    		if (empty($itemid) || !filter_var($itemid, FILTER_VALIDATE_INT))
    			throw new BadMethodCallException('Parametro non valido, atteso un intero valido', E_USER_ERROR);
    		$query = 'SELECT
    		tstart,
    		titolo
                FROM #__gg_contenuti_jumper
                WHERE id_contenuto=' . $itemid .
                ' ORDER BY tstart ASC';
                if ($this->_dbg)
                	$this->_japp->enqueueMessage($query);
                $this->_db->setQuery($query);
                if (false === ($results = $this->_db->loadAssocList()))
                	throw new RuntimeException($this->_db->getErrorMsg(), E_USER_ERROR);
                return $results;
            } catch (Exception $e) {
            	jimport('joomla.error.log');
            	$log = &JLog::getInstance('com_gglms.log.php');
            	$log->addEntry(array('comment' => $e->getMessage(), 'status' => $e->getCopde));
            	if ($this->_dbg)
            		$this->_japp->enqueueMessage($e->getMessage(), 'error');
            }
            return 0;
        }

    /**
     * Inserisce i jumper su database.
     * 
     * @param int $itemid
     * @param array $jumpers 
     */
    private function _insertJumperDB($itemid, $jumpers) {
    	if (empty($itemid) || !filter_var($itemid, FILTER_VALIDATE_INT))
    		throw new BadMethodCallException('Parametro non valido, atteso un intero valido', E_USER_ERROR);
    	if (empty($jumpers) || !is_array($jumpers))
    		throw new BadMethodCallException('Parametro non valido, atteso un array valido', E_USER_ERROR);

    	$values = array();
    	foreach ($jumpers AS $jumper) {
    		$values[] = '(' . $itemid . ', ' . $jumper['tstart'] . ', \'' . $jumper['titolo'] . '\')';
    	}

    	$query = 'INSERT INTO #__gg_contenuti_jumper (id_contenuto, tstart, titolo) VALUES ' . join(', ', $values);
    	if ($this->_dbg)
    		$this->_japp->enqueueMessage($query);
    	$this->_db->setQuery($query);
    	$this->_db->query();
    }

    /**
     * Legge i jumper da file XML.
     * Cerca il file in  "$content_path/$itemid/$itemid.xml".
     * 
     * @param int $itemid ID del contenuto di cui si vogliono i jumper. 
     * @param string $content_path Percorso dove cercare il file XML.
     * @return array 
     */
    private function _getJumperXML($itemid, $content_path = 'mediatv/') {
    	try {
    		if (empty($itemid) || !filter_var($itemid, FILTER_VALIDATE_INT))
    			throw new BadMethodCallException('Parametro non valido, atteso un intero valido', E_USER_ERROR);

    		$path = $this->_add_ending_slash($content_path) . $itemid . '/' . $itemid . '.xml';
    		debug::msg($path);
    		if (!is_readable($path))
    			throw new DomainException('Impossibile leggere il file ' . $path, E_USER_ERROR);

    		if ($this->_dbg)
    			$this->_japp->enqueueMessage('Read file XML jumper file: ' . $path);

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
    					$jumpers[$i]['titolo'] = filter_var($node->nodeValue, FILTER_SANITIZE_STRING);
    			}
    			$i++;/** @todo se il nodo non contiene time e name incremento i e non faccio nessun controllo se il jumper abbia 2 elementi tstart e titolo */
    		}
    		unset($xml);
    		unset($cue_points);
    		return $jumpers;
    	} catch (Exception $e) {
    		if ($this->_dbg)
    			$this->_japp->enqueueMessage($e->getMessage(), 'error');
    	}
    	return 0;
    }

    /**
     * Aggiunde uno slah ('/') finale a $path se questo ne è sprovvisto.
     * 
     * @param string $path
     * @return string
     */
    private function _add_ending_slash($path) {
    	return $path . ((substr($path, strlen($path) - 1, 1) != '/') ? '/' : '');
    }

    /**
     * Traccio la visualizzazioen 
     */
    public function setTrack($user_id, $content_id, $tpl) {
    	try {

    		$query = "INSERT INTO
                #__gg_log (id_utente, id_contenuto, data_accesso, supporto) 
    		values($user_id, $content_id, now(), '$tpl')
    		";

    		debug::msg($query);

    		$this->_db->setQuery($query);
    		if (false === ($results = $this->_db->query()))
    			throw new RuntimeException($this->_db->getErrorMsg(), E_USER_ERROR);
    		return $results;
    	} catch (Exception $e) {
    		jimport('joomla.error.log');
    		$log = &JLog::getInstance('com_gglms.log.php');
    		$log->addEntry(array('comment' => $e->getMessage(), 'status' => $e->getCopde));
    		if ($this->_dbg)
    			$this->_japp->enqueueMessage($e->getMessage(), 'error');
    	}
    }

    public function checkCoupon() {



    	$query = $this->_db->getQuery(true);
    	try {
    		$query->select('count(coupon)');
    		$query->from('#__gg_coupon as u');
    		$query->where("u.id_utente = $this->_userid");
    		$query->where("(data_scadenza > current_date() OR data_scadenza IS NULL)");
    		$query->where("if(durata is not null, DATEDIFF(DATE_ADD(data_utilizzo, INTERVAL durata DAY), current_date()) > 0, true)");
    		$this->_db->setQuery((string) $query, 0, 1);
    		$res = $this->_db->loadResult();
    	} catch (Exception $e) {
    		debug::exception($e);
    	}

    	return $res;
    }


    public static function outputUnitaOLD($unit) {


        $statoalbero=array(0=>'hidden', 1=>'list-item', 2=>'none');

    // FB::log($unit, "unit");


        if($unit->statoalbero == 0)
            $out = "<ul style='display:none;'>";
        else
            $out = "<ul>";


        if($unit->statoalbero == 1)
            $out .="<li style='display:list-item'>";
        else if($unit->statoalbero == 2)
            $out .="<li style='display:none'>";


        if (isset($unit->titolo)) {

         if($unit->statoalbero == 1)
             $out.="<span class='badge badge-success'><i class='icon-minus-sign'></i>" . $unit->titolo . "</span>";
         if($unit->statoalbero == 2)
            $out.="<span class='badge badge-success'><i class='icon-plus-sign'></i>" . $unit->titolo . "</span>";

    } 


    if($unit->statoalbero == 1)
        $out.="<ul>"; 
    else if($unit->statoalbero == 2)
        $out.="<ul style='display:none'>"; 

        // $out.="<ul>"; 


    $contenuti= modgglmsHelper::getContenuti($unit->id);
    foreach ($contenuti as $contenuto) {
        if($unit->statoalbero == 1)
            $out.="<li style='display:list-item'>"; 
        else if($unit->statoalbero == 2)
            $out.="<li style='display:none'>"; 


        $out.='<a href="component/gglms/contenuto/' . $contenuto['idlink'] . "-" . $contenuto['alias'] . '"><span><i class="icon-facetime-video"></i></span>' . $contenuto['titolo'] . '</a>';
                //   $contenuto['prerequisiti'] = $this->_chek_prerequisiti($contenuto['prerequisiti']);
                //   $contenuto['stato'] = $this->_check_stato($contenuto['idcontenuto']);
        $out.="</li>";
    }


    $out.="</ul>";

    if (isset($unit->sottounita)) {
            // FB::log($unit->titolo, "sono in ");

        foreach ($unit->sottounita as $item) {
            $out.= modgglmsHelper::outputUnita($item);
        }
    }



    $out.="</li></ul>";
        // FB::log($out, "out") ;
    return $out;
}


public static function outputUnita($unit) {


    $statoalbero=array(0=>'hidden', 1=>'list-item', 2=>'none');
    
    // FB::log($unit, "unit");
    
    $out = "<tr class='treegrid-".$unit->id; 

    //aggiungo le classi
    if($unit->categoriapadre != $params->get('unitselected') )
        $out .= " treegrid-parent-".$unit->categoriapadre. " ";
    
    if($unit->statoalbero == 2)
        $out .= " chiuso ";
    else
        $out .= " aperto ";

    $out .= "'>";




    if (isset($unit->titolo)) {
     $out.="<td><a href='".JURI::base()."component/gglms/unita/".$unit->alias."' title='".htmlentities(utf8_decode($unit->descrizione))."' >" . $unit->titolo . "</a></td>";

     if($params->get('show_unit_description'))
        $out.="<td>" . $unit->descrizione . "</td>";
}

$out.="</tr>";


if($params->get('show_content_in_tree')){

 $contenuti= modgglmsHelper::getContenuti($unit->id);


 foreach ($contenuti as $contenuto) {
    $out.="<tr class= 'treegrid-parent-".$unit->id. " '>";
    $out.="<td>"; 
    $out.='<a href="component/gglms/contenuto/' . $contenuto['idlink'] . "-" . $contenuto['alias'] . '"  title="'.htmlentities(utf8_decode($contenuto['abstract'])).'"><span><i class="icon-facetime-video"></i></span>' . $contenuto['titolo'] . '</a>';
//                 //   $contenuto['prerequisiti'] = $this->_chek_prerequisiti($contenuto['prerequisiti']);
//                 //   $contenuto['stato'] = $this->_check_stato($contenuto['idcontenuto']);
    $out.="</td></tr>";
}

// fb::log($out, "out");


}


$sottounita= modgglmsHelper::getUnit($unit->id);

foreach ($sottounita as $item) {
   $out.= modgglmsHelper::outputUnita($item, $params);
}


        // FB::log($out, "out") ;
return $out;
}



}

