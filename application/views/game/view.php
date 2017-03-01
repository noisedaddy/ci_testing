<h3><?php echo $title; ?></h3>
<h3><?php echo $status; ?></h3>
<?php echo validation_errors(); ?>
<?php echo form_open('game/makeMove/'.$id, array('name'=> 'frm_table')); ?>
	<div class="ticTacField">
		<?php for ($y = 1; $y < $height; $y++) : ?>
			<div class="ticTacRow">
				<?php for ($x = 1; $x < $width; $x++) :
					$player = isset($field[$x][$y]) ? $field[$x][$y] : null;
					$winner = isset($winnerCells[$x][$y]);
					$class = ($player ? ' player' . $player : '') . ($winner ? ' winner' : '');
					?>
					<div class="ticTacCell<?= $class ?>" data-x="<?=$x?>" data-y="<?=$y?>">
						<?php if (!$player) : ?>
							<a href="#" class="js-move"></a>
						<?php endif ?>
					</div>
				<?php endfor ?>
			</div>
		<?php endfor ?>
	</div>
<?php echo form_close(); ?>
