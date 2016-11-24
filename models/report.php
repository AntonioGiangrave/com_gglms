<?php

/**
 * WebTVContenuto Model
 * 
 * @package    Joomla.Components
 * @subpackage WebTV
 */
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');
jimport('joomla.access.access');

/**
 * WebTVContenuto Model
 * 
 * @package    Joomla.Components
 * @subpackage WebTV
 */
class gglmsModelReport extends JModelLegacy {

    private $_dbg;
    private $_japp;
    private $_id;
    private $_userid;
    private $_mygroups;

    public function __construct($config = array()) {
        parent::__construct($config);
        $this->_japp = & JFactory::getApplication();
        $this->_id = null;
        $this->_db = & JFactory::getDbo();

        $user = JFactory::getUser();
        $this->_userid = $user->get('id');

        $this->_mygroups = implode(",", JAccess::getGroupsByUser($this->_userid, false));
    }

    public function __destruct() {
        unset($this->_dbg);
        unset($this->_content);
        unset($this->_id);
    }

    public function getCoupon($where = null) {
        $query = '
                SELECT
                    c.coupon,
                    c.codiceverifica,
                    c.data_utilizzo,
                    c.id_iscrizione
                FROM
                    #__gg_coupon AS c
                WHERE codiceverifica is not null
                ORDER BY data_utilizzo  desc
                LIMIT 1000
                ';

        $this->_db->setQuery($query);
        if (false === ($results = $this->_db->loadAssocList()))
            throw new RuntimeException($this->_db->getErrorMsg(), E_USER_ERROR);

        return $results;
    }

    public function getReportCoupon($where = null) {

        $query = "  
                SELECT
                    c.coupon,
                    c.codiceverifica,
                    c.daverificare,
                    c.data_utilizzo,
                    c.id_iscrizione, 
                    c.id_utente, 
                    cp.firstname, 
                    cp.lastname, 
                    cp.cb_telefono as telefono,
                    u.email
                FROM
                    #__gg_coupon AS c 
                LEFT JOIN #__users as u ON c.id_utente=u.id
                LEFT JOIN #__comprofiler as cp ON cp.user_id=c.id_utente
                                        ";
        $query .= " WHERE c.gruppo in ($this->_mygroups) ";
        $query .= " AND " . $where;
        $query .= " ORDER BY data_utilizzo  desc
                    LIMIT 1000
                    ";

        
        
        FB::log($query, 'Query getReportCoupon ');




        $this->_db->setQuery($query);
        if (false === ($results = $this->_db->loadAssocList()))
            throw new RuntimeException($this->_db->getErrorMsg(), E_USER_ERROR);

        return $results;
    }

    public function getCodiciDaVerificare() {
        $where = " daverificare >= 1";
        $results = $this->getReportCoupon($where);

        return $results;


    }

    public function getGiorni() {

        $query = " 
                SELECT 
                    DATE_FORMAT(c.data_utilizzo , '%Y-%m-%d') as giorno, 
                    count(*) as tot
                FROM
                    #__gg_coupon AS c
                WHERE codiceverifica is not null
                AND c.gruppo in ($this->_mygroups)
                GROUP BY giorno
                ORDER BY data_utilizzo  desc
                LIMIT 1000"
        ;

        $this->_db->setQuery($query);
        if (false === ($results = $this->_db->loadAssocList()))
            throw new RuntimeException($this->_db->getErrorMsg(), E_USER_ERROR);
        return $results;
    }

    public function getTotCouponPerCorso() {
        $query = "
            SELECT
                u.titolo,
                Count(c.coupon) AS tot
                
            FROM
                #__gg_coupon AS c
            INNER JOIN gg_gg_unit AS u ON u.id = c.corsi_abilitati
            WHERE
                c.data_utilizzo IS NOT null
            AND
                c.gruppo in ($this->_mygroups)
            GROUP BY
                c.corsi_abilitati
            ORDER BY tot DESC
        ";

        $this->_db->setQuery($query);
        if (false === ($results = $this->_db->loadAssocList()))
            throw new RuntimeException($this->_db->getErrorMsg(), E_USER_ERROR);

        return $results;
    }

    public function getAccesByDay($where = "CURRENT_DATE") {
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

    public function setBadCode($where = NULL) {
        $query = "
                UPDATE
                    #__gg_coupon
                SET daverificare = 1
                WHERE 
                    1 = 0 ";
        if ($where)
            $query .= $where;

        $this->_db->setQuery($query);
        if (false === ($results = $this->_db->loadResult()))
            throw new RuntimeException($this->_db->getErrorMsg(), E_USER_ERROR);

        return $results;
    }

}
