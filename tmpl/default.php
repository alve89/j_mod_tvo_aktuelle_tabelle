<?php
// No direct access
defined('_JEXEC') or die;

require_once(Joomla\CMS\Uri\Uri::root() . 'modules' . DS . $module->module . DS . 'helper.php');

//ModTvoAktuelletabelleHelper::varDump($tableData);




if( $contentToDisplay['league'] ) {
?>
<span id="league">
	<h3><?=str_replace('/ ', '', $tableData->lvTypeLabelStr);?></h3>
</span>
<?php
}

if( $contentToDisplay['lastUpdated'] ) {
?>
<span class="StandLetzteAenderung">Letzte Aktualisierung: <?=date("d.m.Y, H:i", $lastUpdated) . " Uhr";?></span>
<?php
}
?>



<table class="table_tabelle <?=$params->get('moduleclass_sfx');?>">
  <tr>
    <?php
    if( $contentToDisplay['score'] )        { ?><th>Platzierung</th><?php }
    if( $contentToDisplay['teamname'] )     { ?><th>Mannschaft</th><?php }
    if( $contentToDisplay['gamesPlayed'] )  { ?><th>Spiele (S/U/N)</th><?php }
    if( $contentToDisplay['points'] )       { ?><th>Punkte</th><?php }
    if( $contentToDisplay['goals'] )        { ?><th>Torverhältnis</th><?php }
    ?>
  </tr>

	<?php

		foreach($tableData->dataList as $team)
		{
			?>
			<tr style="font-weight: <?= (strpos($team->tabTeamname, 'Oberflockenbach') !== false) ? ('bold') : ('normal');?>">
				<td><?=$team->tabScore;?></td>
				<td><?=$team->tabTeamname;?></td>
        <td><?=$team->numWonGames + $team->numEqualGames + $team->numLostGames;?> (<?=$team->numWonGames;?>/<?=$team->numEqualGames;?>/<?=$team->numLostGames;?>)</td>
				<td><?=$team->pointsPlus;?> : <?=$team->pointsMinus;?></td>
				<td><?=$team->numGoalsShot;?> : <?=$team->numGoalsGot;?></td>

			</tr>
			<?php
		}
	?>

</table>









<?php
// Code für Komponente
//
//
//
//
// Daten von Schnittstelle => muss in Tabelle eingelesen werden
//ModTvoAktuelletabelleHelper::varDump(ModTvoAktuelletabelleHelper::getTable(68361));
