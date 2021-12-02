<?php
// No direct access
defined('_JEXEC') or die;

require_once(JPATH_SITE . '/modules' . DS . $module->module . DS . 'helper.php');

//ModTvoAktuelletabelleHelper::varDump($tableData);


echo $params->get('header');

if( isset($contentToDisplay['league']) && $contentToDisplay['league']) {
?>
<span id="league">
	<h4><?=$team->teamLeague;?></h4>
</span>
<?php
}

if( $contentToDisplay['lastUpdated'] ) {
	?>
	<span class="StandLetzteAenderung" style="font-size: 10px">Letzte Aktualisierung: <?=date("d.m.Y, H:i", $lastUpdated) . " Uhr";?></span>
	<?php
}
?>



<table class="table_tabelle <?=$params->get('moduleclass_sfx');?>" style="font-size: 12px">
  <tr>
    <?php
    if( isset($contentToDisplay['score']) && $contentToDisplay['score'] )        { ?><th>Platzierung</th><?php }
    if( isset($contentToDisplay['teamname']) && $contentToDisplay['teamname'] )     { ?><th>Mannschaft</th><?php }
    if( isset($contentToDisplay['gamesPlayed']) && $contentToDisplay['gamesPlayed'] )  { ?><th>Spiele (S/U/N)</th><?php }
    if( isset($contentToDisplay['points']) && $contentToDisplay['points'] )       { ?><th>Punkte</th><?php }
    if( isset($contentToDisplay['goals']) && $contentToDisplay['goals'] )        { ?><th>Torverhältnis</th><?php }
    ?>
  </tr>

	<?php

		foreach($tableData->dataList as $team)
		{
			?>
			<tr style="font-weight: <?= (strpos($team->tabTeamname, 'Oberflockenbach') !== false) ? ('bold') : ('normal');?>">
				<?php
				if(isset($contentToDisplay['score']) && $contentToDisplay['score']) { ?>
					<td><?=$team->tabScore;?></td>
				<?php }

				if(isset($contentToDisplay['teamname']) && $contentToDisplay['teamname'] ) { ?>
					<td><?=$team->tabTeamname;?></td>
				<?php }

				if(isset($contentToDisplay['gamesPlayed']) && $contentToDisplay['gamesPlayed']) { ?>
					<td><?=$team->numWonGames + $team->numEqualGames + $team->numLostGames;?> (<?=$team->numWonGames;?>/<?=$team->numEqualGames;?>/<?=$team->numLostGames;?>)</td>
				<?php }

				if(isset($contentToDisplay['points']) && $contentToDisplay['points']) { ?>
					<td><?=$team->pointsPlus;?> : <?=$team->pointsMinus;?></td>
				<?php }

				if(isset($contentToDisplay['goals']) && $contentToDisplay['goals']) { ?>
					<td><?=$team->numGoalsShot;?> : <?=$team->numGoalsGot;?></td>
				<?php }
				?>
			</tr>
			<?php
		}
	?>

</table>
<span style="font-size: 10px; line-height: 6px"><?=$params->get('disclaimer');?></span>
