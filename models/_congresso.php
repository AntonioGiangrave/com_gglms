<?php
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');
//require_once('webtv.conf.php');

/**
 * WebTV Model
 *
 * @package    Joomla.Components
 * @subpackage WebTV
 */
class webtvModelCongresso extends JModel {

    public function __construct($config = array()) {
        parent::__construct($config);
    }

    public function __destructor() {
    }

    public function get_congresso($id) {
        try {
            debug::vardump($id, 'id'); 
            $id = filter_var($id, FILTER_VALIDATE_INT, array('options' => array('min_range' => 1)));
            if (empty($id))
                throw new BadMethodCallException('Identificativo congresso non specificato o non valido', E_USER_ERROR);
            $query = 'SELECT 
                    c.congresso,
                    c.banner,
                    c.abstract AS abstract_congresso,
                    c.alias AS alias_congresso,
                    i.*
                FROM #__gg_congressi AS c
                LEFT JOIN #__gg_contenuti AS i ON i.id_congresso = c.id
                WHERE c.pubblicato = 1 AND c.id = ' . $id;
            $this->_db->setQuery($query);
            debug::msg($query);
            return $this->_db->loadAssocList();            
        } catch (Exception $e) {
            debug::exception($e);
        }
    } 
}
// ~@:-]
