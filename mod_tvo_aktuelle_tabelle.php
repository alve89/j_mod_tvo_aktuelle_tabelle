<?php

// No direct access
defined('_JEXEC') or die;

// Include the syndicate functions only once
require_once dirname(__FILE__) . '/helper.php';
$application = JFactory::getApplication();

$db = JFactory::getDbo();
$prefix = $db->getPrefix();
$availableTables = $db->setQuery('SHOW TABLES')->loadColumn();
$tablesNotFound = false;

if(!array_search($prefix.'tvo_teams', $availableTables) ) {
  $tablesNotFound = true;
}

if(!array_search($prefix.'tvo_tables', $availableTables) ) {
  $tablesNotFound = true;
}

// Alle notwendigen Tabellen sind vorhanden
if( !$tablesNotFound ) {
  // Lade alle Teaminformation aus der Datenbank
  $db    = JFactory::getDBO();
  $query = $db->getQuery(true);
  $query->select('*');
  $query->from('#__tvo_teams');
  $query->where('published = 1');
	$query->andwhere($db->quoteName('id') . ' = ' . $db->quote($params->get('team')));
  $db->setQuery((string) $query);
  $teamToShow = $db->loadObject();

//var_dump($teamToShow->teamTableId);

  // Lade Spieldaten von gewählten Teams zur Prüfung, ob Teams gefunden werden
  $db    = JFactory::getDBO();
  $query = $db->getQuery(true);
  $query->select(array('a.teamTableId', 'b.teamTableId', 'b.tablesData'));
  $query->from($db->quoteName('#__tvo_teams', 'a'));
  $query->join('RIGHT', $db->quoteName('#__tvo_tables', 'b') . ' ON ' . $db->quoteName('a.teamTableId') . ' = ' . $db->quoteName('b.teamTableId'));
	$query->where('a.published = 1');
	$query->andwhere($db->quoteName('b.teamTableId') . ' = ' . $db->quote($teamToShow->teamTableId));
  $db->setQuery((string) $query);
  $db->query();

  // Prüfe, ob Spiele im Gesamt-Array vorhanden sind
  if( $db->getNumRows() > 0 ) {
    // Lade Spieldaten von gewählten Teams
    $db    = JFactory::getDBO();
    $query = $db->getQuery(true);
		$query->select(array('a.teamTableId', 'b.teamTableId', 'b.tablesData', 'b.lastUpdated', 'a.teamName', 'a.teamLeague'));
	  $query->from($db->quoteName('#__tvo_teams', 'a'));
	  $query->join('RIGHT', $db->quoteName('#__tvo_tables', 'b') . ' ON ' . $db->quoteName('a.teamTableId') . ' = ' . $db->quoteName('b.teamTableId'));
		$query->where('a.published = 1');
		$query->andwhere($db->quoteName('b.teamTableId') . ' = ' . $db->quote($teamToShow->teamTableId));
    $db->setQuery((string) $query);
    $team = $db->loadObject();
		$tableData = json_decode($team->tablesData)[0];

			//ModTvoAktuelletabelleHelper::varDump($team->tablesData);
  }
  else {
    // Es wurden keine Spiele gefunden
    $application->enqueueMessage(JText::_('MOD_TVO_AKTUELLE_TABELLE_NO_TEAMS_FOUND'), 'error');
  }

  // Datum, an welchem die Tabelle zuletzt durch die API zum Handballserver aktualisiert wurde
  $lastUpdated = 0;

  if( strtotime($team->lastUpdated) != FALSE ) {
    $lastUpdated = strtotime($team->lastUpdated);
  }


} // if $tablesNotFound

// ####################################### Prüfe alle Voraussetzungen und starte Rendering #######################################

// Prüfe, ob die Saison noch läuft oder bereits vorüber ist
if( $params->get('seasonStatusSelector') == 1 ) {
  // Prüfe ob in der Modulkonfiguration Spalten zum Anzeigen ausgewählt wurden
  if( $params->get('columns') == NULL ) {
    // In der Modulkonfiguration wurde nichts angehakt
    $application->enqueueMessage(JText::_('MOD_TVO_AKTUELLE_TABELLE_NO_COLUMNS_CHOSEN'), 'error');
  }

  if( $tablesNotFound ) {
    $application->enqueueMessage(JText::_('MOD_TVO_AKTUELLE_TABELLE_TABLES_NOT_FOUND'), 'error');
  }

  // Erstelle Array mit allen anzuzeigenden Spalten
  $contentToDisplay = $params->get('columns');

  foreach($contentToDisplay as $key => $value)
	{
		$contentToDisplay[$value] = true;
		unset($contentToDisplay[$key]);
	}

	// Render output
	require JModuleHelper::getLayoutPath('mod_tvo_aktuelle_tabelle', $params->get('layout'));
}
else {
	echo 'Die Saison ist vorbei';
}
