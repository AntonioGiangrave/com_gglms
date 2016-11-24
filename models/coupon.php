<?php

/**
 * WebTVContenuto Model
 *
 * @package    Joomla.Components
 * @subpackage WebTV
 */
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');

/**
 * WebTVContenuto Model
 *
 * @package    Joomla.Components
 * @subpackage WebTV
 */
class gglmsModelcoupon extends JModelLegacy {

    private $_dbg;
    private $_japp;
    private $_coupon;
    private $_ausind_confindustria_option;
    protected $_db;
    protected $_db2;
    private $_abilitato;
    private $_userid;
    private $_user;

    public function __construct($config = array()) {
        parent::__construct($config);
        $this->_dbg = JRequest::getBool('dbg', 0);
        $this->_japp = & JFactory::getApplication();
        $this->_db = & JFactory::getDbo();
        $this->_user = & JFactory::getUser();
        $this->_userid = $this->_user->get('id');

        if($this->_userid == 0) {  //RS if ($user->id == 0) {
            $msg = "Per accedere al corso è necessario loggarsi";

            $uri      = JFactory::getURI();
            $return      = $uri->toString();

            $url  = JURI::base().'/accedi.html';
            $url .= '&return='.base64_encode($return);
            $this->_japp->redirect(JRoute::_($url), $msg);

        }
    }

    public function __destruct() {

    }

    public function check_Coupon($coupon) {
        try {
            $query = '
                SELECT
                    *
                FROM 
                    #__gg_coupon as c
                WHERE 
                    c.coupon = "' . $coupon . '"
                        AND 
                    c.id_utente IS NULL
                ';



            FB::log($query, "check_Coupon");
            $this->_db->setQuery($query);
            if (false === ($results = $this->_db->loadAssoc()))
                throw new RuntimeException($this->_db->getErrorMsg(), E_USER_ERROR);
            $this->_coupon = empty($results) ? array() : $results;
        } catch (Exception $e) {

            $this->_coupon = array();
        }
        return $this->_coupon;
    }

    /**
     * Inserisce l'utente negli stessi gruppi cui è iscritta la società di appartenenza.
     * Operazione necessaria per l'accesso ai form di discussione.
     *
     * @param string $coupon
     * @return bool
     */
    public function set_user_groups($coupon) {
        try {
            if (empty($coupon))
                throw new BadMethodCallException('Parametro non valido: coupon non è impostato', E_USER_ERROR);

            // ottendo l'id della società
            $query = 'SELECT id_societa FROM #__gg_coupon WHERE coupon=\'' . $coupon . '\' LIMIT 1';
            if ($this->_dbg)
                $this->_japp->enqueueMessage($query);
            $this->_db->setQuery($query);
            if (false === ($results = $this->_db->loadAssoc()))
                throw new RuntimeException($this->_db->getErrorMsg(), E_USER_ERROR);
            $company_id = filter_var($results['id_societa'], FILTER_VALIDATE_INT);
            if (empty($company_id))
                throw RuntimeException('Cannot get company ID from database', E_USER_ERROR);

            // aggiorno i gruppi dell'utente
            $query = 'INSERT INTO #__user_usergroup_map (user_id, group_id)
                SELECT ' . $this->_userid . ', g.id AS group_id
                    FROM #__usergroups AS g
                    INNER JOIN #__users AS u ON u.name=g.title
                    WHERE u.id=' . $company_id;
            if ($this->_dbg)
                $this->_japp->enqueueMessage($query);
            $this->_db->setQuery($query);
            if (false === ($results = $this->_db->query()))
                throw new RuntimeException($this->_db->getErrorMsg(), E_USER_ERROR);
        } catch (Exception $e) {
            return false;
        }
    }

    /*
     * Aggiorno la tabella coupon inserendo l'id dell'utente che sta utilizzando quel coupon
     */

    public function assegnaCoupon($coupon, $codiceverifica) {

        try {
            $query = '
                UPDATE
                    #__gg_coupon 
                SET id_utente = ' . $this->_userid . ', 
                data_utilizzo = NOW(), 
                codiceverifica = "'.$codiceverifica.'"
                WHERE 
                    coupon = "' . $coupon . '"
                ';

            fb::log($query, "query");

            if ($this->_dbg)
                $this->_japp->enqueueMessage($query);



            $this->_db->setQuery($query);
            if (false === ($results = $this->_db->query()))
                throw new RuntimeException($this->_db->getErrorMsg(), E_USER_ERROR);
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_gglms.log.php');
            $log->addEntry(array('comment' => $e->getMessage(), 'status' => $e->getCopde));
        }
        return true;
    }


    /*
       * Aggiorno la tabella abilitando il coupon
       */

    public function abilitaCoupon($coupon) {

        try {
            $query = '
                UPDATE
                    #__gg_coupon 
                SET abilitato = 1  
                WHERE 
                    coupon = "' . $coupon . '"
                ';

            FB::log($query, "query");

            if ($this->_dbg)
                $this->_japp->enqueueMessage($query);

            $this->_db->setQuery($query);
            if (false === ($results = $this->_db->query()))
                throw new RuntimeException($this->_db->getErrorMsg(), E_USER_ERROR);

        } catch (Exception $e) {

        }
        return true;
    }





    /*
     * Iscrive l'utente loggato ai relativi corsi 
     * 
     * paramento id_corsi specificato nella tabella coupon : stringa di id corsi separati da virgola 
     * 
     */

    public function iscriviUtente($id_corsi) {

        //id_corsi potrebbe essere una stringa di id separati da virgola.
        $id_corsi_array = explode(",", $id_corsi);

        foreach ($id_corsi_array as $id_corso) {

            try {
                $query = '
                INSERT IGNORE INTO
                    #__gg_iscrizioni
                    (id_corso,id_utente) 
                VALUE
                    (' . $id_corso . ',' . $this->_userid . ')
                ';

                if ($this->_dbg)
                    $this->_japp->enqueueMessage($query);

                $this->_db->setQuery($query);
                if (false === ($results = $this->_db->query()))
                    throw new RuntimeException($this->_db->getErrorMsg(), E_USER_ERROR);
            } catch (Exception $e) {
                jimport('joomla.error.log');
                $log = &JLog::getInstance('com_tvlms.log.php');
                $log->addEntry(array('comment' => $e->getMessage(), 'status' => $e->getCopde));
            }
        }
        return true;
    }

    /*
     * Gli passo una stringa di id corsi e mi restituisce la lista dei corsi con relativi link.
     * 
     */

    public function get_listaCorsiFast($id_corsi) {

        $id_corsi_array = explode(",", $id_corsi);
        if (count($id_corsi_array) > 1)
            $report = "<p><h3>Sei iscritto ai seguenti corsi: </h3></p> <ul>";
        else
            $report = "<p><h3>Sei iscritto al seguente corso: </h3></p> <ul>";

        foreach ($id_corsi_array as $id_corso) {
            try {
                $query = '
                SELECT
                     alias,
                     titolo
                FROM
                    #__gg_unit as c
                WHERE 
                    c.id=' . $id_corso . '
                ';

                if ($this->_dbg)
                    $this->_japp->enqueueMessage($query);

                $this->_db->setQuery($query);
                if (false === ($results = $this->_db->loadAssoc()))
                    throw new RuntimeException($this->_db->getErrorMsg(), E_USER_ERROR);
                $corso = empty($results) ? array() : $results;
                FB::log($corso, "corso");
            } catch (Exception $e) {
            }

            $report.='<li><a href="'.JURI::base().'component/gglms/unita/' . $corso['alias'] . '">' . $corso['titolo'] . '</a></li>';
        }
        $report.='</ul>';
        return $report;
    }


    public function get_datiutente(){

        try {
            $query = "
                      SELECT
                        c.id,
                        c.firstname,
                        c.lastname,
                        u.email, 
                        c.cb_telefono,
                        c.cb_tipofatturazione,
                        c.cb_ragionesociale,
                        c.cb_indirizzofatturazione,
                        c.cb_cittafatturazione,
                        c.cb_capfatturazione,
                        c.cb_partitaivacf
                      FROM
                        #__comprofiler AS c
                      INNER JOIN gg_users as u on u.id = c.id
                      WHERE c.id= $this->_userid
                      ";

            if ($this->_dbg)
                $this->_japp->enqueueMessage($query);

            $this->_db->setQuery($query);
            if (false === ($results = $this->_db->loadAssoc()))
                throw new RuntimeException($this->_db->getErrorMsg(), E_USER_ERROR);
            $data= empty($results) ? array() : $results;
//            FB::log($data, "dati utente");
        } catch (Exception $e) {
        }
        return $data;
    }
}
