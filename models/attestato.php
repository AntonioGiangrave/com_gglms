<?php

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');

/**
 * GGlms Attestato Model
 *
 * @package    Joomla.Components
 * @subpackage GGLms
 * @author Diego Brondo <diego@ggallery.it>
 * @version 0.9
 */
class gglmsModelattestato extends JModelLegacy {

    private $_user_id;
    //    private $_user;
    private $_quiz_id;
    private $_item_id;

    public function __construct($config = array()) {
        parent::__construct($config);

        $this->id_elemento= JRequest::getInt('content', 0);
        FB::log($this->id_elemento, "content");

        $user = JFactory::getUser();
        $this->_user_id = $user->get('id');
    }

    public function __destruct() {
        unset($this->_item_id);
        unset($this->_quiz_id);
        unset($this->_user_id);
    }

    /**
     * Ritorna il certificato dell'utente
     * La funzione riceve in ingresso l'identificativo dell'utente e dell'elemento di tipo
     * attestato di cui si vuole il certificato.
     * Un ulteriore controllo sul superamento del corso viene effettuato.
     * @param int userid Identificativo dell'utente
     * @param int $itemid Elemento collegato all'attestato.
     * @param string $template Template dell'attestato.
     */
    public function certificate() {
        try {

//            $userid = filter_var($userid, FILTER_VALIDATE_INT, array('options' => array('min_range' => 1)));
//            if (empty($userid))
//                throw new DomainException('Parametro non valido: "' . $userid . '" non sembra un identificativo valido per un utente.', E_USER_ERROR);
//            $itemid = filter_var($itemid, FILTER_VALIDATE_INT, array('options' => array('min_range' => 1)));
//            if (empty($itemid))
//                throw new DomainException('Parametro non valido: "' . $itemid . '" non sembra un identificativo valido per un elemento.', E_USER_ERROR);


            $this->_generate_pdf();
        } catch (Exception $e) {
            FB::log($e);
        }
    }

    private function _generate_pdf() {
        try {
            require_once('libs/pdf/certificatePDF.class.php');
            $pdf = new certificatePDF();

            if (null === ($datetest = $this->_certificate_datetest()))
                throw new RuntimeException('L\'utente non ha superato l\'esame o lo ha fatto in data ignota', E_USER_ERROR);

            $pdf->set_data(date("d-m-Y"));


            $info['datali'] = $datetest;
            $info['path_id'] = JRequest::getInt('content', 0);
            $info['path'] = $_SERVER['DOCUMENT_ROOT'] . "/mediagg/contenuti/";

            
            FB::log($info['path']);

            $info['content_path'] = $info['path'] . $info['path_id'];

            $template = "file:" . $info['path'] . $info['path_id'] . "/" . $info['path_id'] . ".tpl";

            $user_info = $this->_certificate_user_info();

            $pdf->add_data($user_info);
            $pdf->add_data($info);

            $nomefile = "attestato_" . $user_info['firstname'] . "_" . $user_info['lastname'] . ".pdf";

            //Deprecated perchè è scritto tutto dentro al tpl
            //$pdf->add_data($certificate_info);
            //$this->_set_track();
            $pdf->fetch_pdf_template($template, null, true, false, 0);
            $pdf->Output($nomefile, 'D');

            return 1;
        } catch (Exception $e) {
            FB::log($e);
        }
        return 0;
    }

    private function _certificate_user_info() {
        try {
            $query = '
                SELECT
                    *
                FROM `#__comprofiler`

              WHERE user_id = ' . $this->_user_id;

            // FB::log($query, "query user info");
            $this->_db->setQuery($query);


            if (false === ($results = $this->_db->loadAssoc()))
                throw new RuntimeException($this->_db->getErrorMsg(), E_USER_ERROR);


//            FB::log($results, "Profilo utente");

            return $results;
        } catch (Exception $e) {
//            debug::exception($e);
        }
        return array();
    }

        private function _certificate_datetest() {
        try {
            $query = '
                    SELECT
                        s.varValue
                    FROM
                        #__gg_contenuti AS c
                        Inner Join #__gg_scormvars AS s ON s.SCOInstanceID = c.id_completed_data
                    WHERE
                        s.UserID = '.$this->_user_id .' AND
                        s.varName = "cmi.core.completed_date" AND 
                        c.id= '.$this->id_elemento .'
                    LIMIT 1';

              

             FB::log($query, "_certificate_datetest");
            $this->_db->setQuery($query);


            if (false === ($results = $this->_db->loadResult()))
                throw new RuntimeException($this->_db->getErrorMsg(), E_USER_ERROR);


           FB::log($results, "data superamento  ");

            return $results;
        } catch (Exception $e) {
//            debug::exception($e);
        }
        return array();
    }

    private function _certificate_user_info_PROFILE() {
        try {
            $query = 'SELECT
              p.profile_key,
              p.profile_value
              FROM
              #__user_profiles AS p
              WHERE p.user_id = ' . $this->_user_id;

            // FB::log($query, "query user info");
            $this->_db->setQuery($query);


            if (false === ($results = $this->_db->loadRowlist()))
                throw new RuntimeException($this->_db->getErrorMsg(), E_USER_ERROR);


            foreach ($results as $v) {
                $k = str_replace('profile.', '', $v[0]);

                $profile[$k] = json_decode($v[1], true);
                if ($k == 'datadinascita') {
                    $tmp = explode("-", $profile[$k]);
                    $profile[$k] = $tmp[2] . "-" . $tmp[1] . "-" . $tmp[0];
                }
            }
            FB::log($profile, "profile");
            return $profile;
        } catch (Exception $e) {
            debug::exception($e);
        }
        return array();
    }

    private function _set_track() {
        try {
            $query = sprintf('INSERT IGNORE INTO #__gg_track VALUES (%d, %d, 1, NOW(), 0, "%s")', $this->_item_id, $this->_user_id, $_SERVER['REMOTE_ADDR']);
            $this->_db->setQuery($query);
            $results = $this->_db->query();
        } catch (Exception $e) {
            FB::log($e);
        }
        return null;
    }

}