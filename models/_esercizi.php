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
class webtvModelEsercizi extends JModel {

    private $_dbg;
    private $_japp;
    private $_id;

    public function __construct($config = array()) {
        parent::__construct($config);
        $this->_dbg = JRequest::getBool('dbg', 0);
        $this->_japp = & JFactory::getApplication();
        $this->_id = null;

        $this->_db = & JFactory::getDbo();
    }

    public function __destruct() {
        unset($this->_dbg);
        unset($this->_id);
    }

    public function getEsercizi() {

        $unita = array();

        for ($i = 1; $i <= 10; $i++) {
            $unita[$i] = $this->getEserciziUnita($i);
        }

        return $unita;
    }
   

    public function getEserciziUnita($idUnita) {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);

        try {
            $query->select('c.esercizi');
            $query->from('#__gg_unit_map AS m');
            $query->join('inner', '#__gg_contenuti AS c ON c.id = m.idcontenuto');
            $query->join('inner', '#__gg_unit AS u1 ON u1.id = m.idunita');
            $query->join('inner', '#__gg_unit AS u2 ON u2.id = u1.categoriapadre');
            $query->where('u2.categoria=' . $idUnita);

            $db->setQuery((string) $query);
            $res = $db->loadColumn();
        } catch (Exception $e) {
            debug::exception($e);
        }

        $res = implode(",",$res);
        return $res;
    }

}