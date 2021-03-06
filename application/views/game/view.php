<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
    
    <h3><?php echo $title; ?></h3>
    <div class="pl_check">      
            <?php if ($currentPlayer) : ?>
                <p class="js-step">Player <span class="icon playerStep<?php echo $currentPlayer ?>" id="pl_step_<?php echo $currentPlayer; ?>"><?php echo $playerNick ?> - </span></p>
            <?php endif ?>       
    </div>   
    <?php echo form_open('game/makeMove/'.$id, array('name'=> 'frm_table')); ?>
                    <?php for ($y = 1; $y < $height; $y++) : ?>
                            <div class="row">
                                    <?php for ($x = 1; $x < $width; $x++) :
                                            $player = isset($field[$x][$y]) ? $field[$x][$y] : null;
                                            $winner = isset($winnerCells[$x][$y]);
                                            $class = ($player ? ' player' . $player : '') . ($winner ? ' winner' : '');                                        
                                            ?>
                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 ticTacCell<?= $class ?>" data-x="<?=$x?>" data-y="<?=$y?>">
                                                    <?php if (!$player) : ?>
                                                            <a href="#" class="js-move"></a>
                                                    <?php endif ?>
                                            </div>
                                    <?php endfor ?>
                            </div>
                    <?php endfor ?>
    <?php echo form_close(); ?>
    
</div> 