<?php

/**
 * WebTVContenuto Model
 * 
 * @package    Joomla.Components
 * @subpackage WebTV
 */
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');
require_once('webtv.conf.php');
require_once('libs/contenuto.lib.php');

/**
 * WebTVContenuto Model
 * 
 * @package    Joomla.Components
 * @subpackage WebTV
 */
class webtvModelreport extends JModel {

    private $_dbg;
    private $_japp;
    private $_content;
    private $_id;

    public function __construct($config = array()) {
        parent::__construct($config);
        $this->_dbg = JRequest::getBool('dbg', 0);
        $this->_japp = & JFactory::getApplication();
        $this->_content = array();
        $this->_id = null;

        $this->_db = & JFactory::getDbo();
    }

    public function __destruct() {
        unset($this->_dbg);
        unset($this->_content);
        unset($this->_id);
    }

    public function getContentStat() {
        $query = '
                SELECT 
                    c.titolo, 
                    c.descrizione,
                    c.id, 
                    COUNT(c.id) as totali
                FROM
                    #__gg_contenuti c
                LEFT JOIN #__gg_log l on c.id = l.id_contenuto
                GROUP BY c.id 
                ORDER BY totali desc, c.id desc';

        $this->_db->setQuery($query);
        if (false === ($results = $this->_db->loadAssocList()))
            throw new RuntimeException($this->_db->getErrorMsg(), E_USER_ERROR);
        
        return $results;
    }

    public function getAccesByDay($where="CURRENT_DATE"){
         $query = "
                SELECT
                    l.id_utente,
                    l.id_contenuto,
                    l.data_accesso,
                    l.supporto,
                    c.titolo,
                    concat(d.cb_nome, ' ' , d.cb_cognome) as utente,
                    CURRENT_DATE

                FROM
                    psn_gg_log AS l
                Inner Join psn_gg_contenuti AS c ON c.id = l.id_contenuto
                Inner Join jos_comprofiler AS d ON d.user_id = l.id_utente
                
                WHERE DATE_FORMAT(data_accesso,'%Y-%m-%d')  = CURRENT_DATE";

        $this->_db->setQuery($query);
        if (false === ($results = $this->_db->loadAssocList()))
            throw new RuntimeException($this->_db->getErrorMsg(), E_USER_ERROR);
        
        return $results;
    }
    
}

// ~@:-]
