<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 <?php echo $listings_class; ?>">
    <h2><?php echo $title_main; ?></h2>
     <ul id="ul_listing" class="list-group">           
            <?php if ($empty_flag) : ?>
                <p>List is empty!</p>
                <?php echo anchor(base_url().'game/create', 'Create New Game'); ?>
            <?php endif ?>    
    <?php foreach ($games as $game): ?>
           <li class="list-group-item">
                    <span><?php echo "Started: ".$game['game_start'].", Status: ".$game['game_status']; ?></span>
                    <br>
                    <?php echo anchor(base_url().'game/view/'.$game['game_id'], $game['player_one_nick']." Vs ".$game['player_two_nick']); ?>
            </li>
    <?php endforeach; ?>
     </ul>
</div>