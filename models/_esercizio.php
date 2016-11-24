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
class webtvModelContenuto extends JModel {

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

}