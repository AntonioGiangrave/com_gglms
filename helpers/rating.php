<?php

/**
 * @package		Joomla.Tutorials
 * @subpackage	Components
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		License GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access to this file
defined('_JEXEC') or die;

class ratingHelper {

	public static function getRating($id_elemento ) {
		$db = & JFactory::getDbo();

		$user = JFactory::getUser();
		$user_id = $user->get('id');

		// $id_elemento = $_REQUEST['id_elemento'];
		// $rating = $_REQUEST['rating'];

		$query = "
		select rating from #__gg_rating 
		where 
		id_utente = ".$user_id."
		and id_contenuto = ".$id_elemento."
		";

        // FB::log($query, "getRating");

		$db->setQuery($query);
		$results = $db->loadResult();
		return $results;
	}

	public static function setRating($id_elemento, $rating) {
		$db = & JFactory::getDbo();

		$user = JFactory::getUser();
		$user_id = $user->get('id');

		// $id_elemento = $_REQUEST['id_elemento'];
		// $rating = $_REQUEST['rating'];

		$query = "
		INSERT IGNORE INTO #__gg_rating (id_utente, id_contenuto, rating)
		VALUE (".$user_id." ,  ".$id_elemento.", ".$rating.")
		";

        // FB::log($query, "setTrack");

		$db->setQuery($query);
		$results = $db->query();
		return $results;
	}

	public static function avgRating($id_elemento) {
		$db = & JFactory::getDbo();

		// $id_elemento = $_REQUEST['id_elemento'];

		$query = "
		select avg(rating) from #__gg_rating 
		where 
		id_contenuto = ".$id_elemento."
		";

        // FB::log($query, "getRating");

		$db->setQuery($query);
		$results = $db->loadResult();

		$results = round($results , 1);
		return $results;
	}

	public static function totRating($id_elemento, $star = null) {
		$db = & JFactory::getDbo();

		// $id_elemento = $_REQUEST['id_elemento'];
		// $star = $_REQUEST['star'];

		$query = "
		select count(rating) from #__gg_rating 
		where 
		id_contenuto = ".$id_elemento."
		";

		if($star > 0)
			$query .= "and rating= $star";

        // FB::log($query, "getRating");

		$db->setQuery($query);
		$results = $db->loadResult();
		return $results;
	}


}