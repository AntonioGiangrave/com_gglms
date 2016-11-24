<?php

/**
 * WebTVCategorie Model
 * 
 * @package    Joomla.Components
 * @subpackage WebTV
 */
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');

/**
 * WebTVCategorie Model
 * 
 * @package    Joomla.Components
 * @subpackage WebTV
 */
class webtvModelcategorie extends JModel {

    private $_dbg;
    private $_japp;
    private $_total;
    private $_pagination;
    private $_items;

    public function __construct($config = array()) {
        parent::__construct($config);
        $this->_dbg = JRequest::getBool('dbg', 0);
        $this->_japp = & JFactory::getApplication();
        $this->_db = & JFactory::getDbo();

        $this->_total = null;
        $this->_pagination = null;
        $this->_items = null;

        $mainframe = JFactory::getApplication();
        // ottengo le variabili per la paginazione
        //$limit = $mainframe->getUserStateFromRequest('global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
        $limit = 12;
        $limitstart = JRequest::getVar('limitstart', 0, '', 'int');
        $limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);
        if ($this->_dbg) {
            $this->_japp->enqueueMessage('Limit: ' . $limit);
            $this->_japp->enqueueMessage('Limit Start: ' . $limitstart);
        }

        $this->setState('limit', $limit);
        $this->setState('limitstart', $limitstart);
    }

    public function __destruct() {
        unset($this->_dbg);
    }

    /**
     * Ritorna un array di righe.
     * 
     * @param string $where
     * @param string $orderby
     * @return array Array di risultati
     */
    public function getItemsByCategoria($where = 'pubblicato=1', $orderby = 'datapubblicazione DESC') {
        try {
            if (empty($this->_items)) {
                $this->_items = $this->_getList($this->_build_query($where, $orderby), $this->getState('limitstart'), $this->getState('limit'));
                if ($this->_dbg)
                    $this->_japp->enqueueMessage('Fetched rows :' . count($this->_items));
            }
            return $this->_items;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_gglms.log.php');
            $log->addEntry(array('comment' => $e->getMessage(), 'status' => $e->getCopde));
            if ($this->_dbg)
                $this->_japp->enqueueMessage($e->getMessage(), 'error');
            $this->_content = array();
        }
    }

    /**
     * Riorna il numero totale di riche della query.
     * 
     * @param string $where
     * @param string $orderby
     * @return int
     */
    public function getItemsByCategoria_Total($where = 'pubblicato=1', $orderby = 'datapubblicazione DESC') {
        if (empty($this->_total)) {
            $this->_total = $this->_getListCount($this->_build_query($where, $orderby));
        }
        return $this->_total;
    }

    /**
     * Ritorna un oggetto paginatione per la visualizzazione paginata di risultati
     * 
     * @param string $where
     * @param string $orderby
     * @return JPagination 
     */
    public function getItemsByCategoria_Pagination($where = 'pubblicato=1', $orderby = 'datapubblicazione DESC') {
        if (empty($this->_pagination)) {
            jimport('joomla.html.pagination');
            $this->_pagination = new JPagination($this->getItemsByCategoria_Total($where, $orderby), $this->getState('limitstart'), $this->getState('limit'));
        }
        return $this->_pagination;
    }

    private function _build_query($where = 'pubblicato=1', $orderby = 'datapubblicazione DESC') {
        $id_categoria = JRequest::getInt('id', 0);
        if (empty($id_categoria))
            throw new DomainException('Identificatore di categoria non valido.', E_USER_ERROR);
        $query = 'SELECT
                *
            FROM #__gg_contenuti
            WHERE ';
        if (isset($where))
            $query .= $where . ' AND categoria REGEXP \'[[:<:]]' . $id_categoria . '[[:>:]]\'';
        else
            $query .= 'categoria REGEXP \'[[:<:]]' . $id_categoria . '[[:>:]]\'';
        if (isset($orderby))
            $query .= ' ORDER BY ' . $orderby;
        if ($this->_dbg)
            $this->_japp->enqueueMessage($query);



        return $query;
    }

    
    /**
     * Ritorna un array di righe.
     * 
     * @param string $where
     * @param string $orderby
     * @return array Array di risultati
     */
    
    public function getUnita($where = 'pubblicato=1', $orderby = 'datapubblicazione DESC') {

        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('*');
        $query->from('#__gg_unita as u ');
        $query->where('pubblicata  = 1 ');
        $query->order('ordinamento');

        $db->setQuery((string) $query);
        $res = $db->loadObjectList();

        
        debug::vardump($res);
        
        
        
        
        return $res;
    }
    
    
    /**
     * Ritorna un array di righe.
     * 
     * @param string $where
     * @param string $orderby
     * @return array Array di risultati
     */
    public function getContenutiPerUnita($where = 'pubblicato=1', $orderby = 'datapubblicazione DESC') {

        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('id, titolo');
        $query->from('#__gg_contenuti as c ');
        $query->where($where);
        $query->order('ordinamento');

        $db->setQuery((string) $query);
        $res = $db->loadObjectlist();

        return $res;
    }

}

