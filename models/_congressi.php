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
class webtvModelCongressi extends JModel {
    const TYPE_VIRTUALFORUM = 1;
    const TYPE_SPECIALECONGRESSI = 2;

    public function __construct($config = array()) {
        parent::__construct($config);
    }

    public function __destructor() {
    }

    public function get_congressi($type) {
        try {
            $type = filter_var($type, FILTER_VALIDATE_INT, array('options' => array('min_range' => self::TYPE_VIRTUALFORUM, 'max_range' => self::TYPE_SPECIALECONGRESSI)));
            if (empty($type))
                throw new BadMethodCallException('Virtual Forum o Speciale Congressi?', E_USER_ERROR);
            $query = 'SELECT 
                    id,
                	congresso,
                	alias
                FROM psn_gg_congressi   
                WHERE pubblicato = 1
                AND tipologia=' . $type . '
                ORDER BY ordinamento DESC';
            $this->_db->setQuery($query);
            debug::msg($query);
            return $this->_db->loadAssocList();
        } catch (Exception $e) {
            debug::exception($e);
            return array();
        }
    }
}
// ~@:-]
