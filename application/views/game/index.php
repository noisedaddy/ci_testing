<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 <?php echo $listings_class; ?>">


    <ul  class="nav nav-tabs">
        <li class="active">
            <a  href="#1a" data-toggle="tab">Finished</a>
        </li>
        <li><a href="#2a" data-toggle="tab">Draw</a>
        </li>
    </ul>

    <div class="tab-content clearfix">
        <div class="tab-pane active" id="1a">
                <ul id="ul_listing" class="list-group">           
                    <?php if ($empty_flag) : ?>
                        <p>List is empty!</p>
                        <?php echo anchor(base_url() . 'game/create', 'Create New Game'); ?>
                    <?php endif ?>    
                    <?php foreach ($games as $game): ?>
                        <li class="list-group-item lgi_custom">
                            <span><?php echo "Started: " . $game['game_start']; ?></span>
                            <br>
                            <?php $winner = ( $game['player_one_nick'] == $game['player_winner']) ? "<b>" . $game['player_one_nick'] . "</b> Vs " . $game['player_two_nick'] : $game['player_one_nick'] . " Vs <b>" . $game['player_two_nick'] . "</b>"; ?>
                            <?php echo anchor(base_url() . 'game/view/' . $game['game_id'], $winner); ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
        </div>
        <div class="tab-pane" id="2a">
                <ul id="ul_listing_draw" class="list-group">           
                    <?php if ($empty_flag) : ?>
                        <p>List is empty!</p>
                        <?php echo anchor(base_url() . 'game/create', 'Create New Game'); ?>
                    <?php endif ?>    
                    <?php foreach ($games_draw as $game_draw): ?>
                        <li class="list-group-item lgi_custom">
                            <span><?php echo "Started: " . $game_draw['start_on']; ?></span>
                            <br>                            
                            <?php echo anchor(base_url() . 'game/view/' . $game_draw['id'], $game_draw['player_one_nick']." Vs ".$game_draw['player_two_nick']); ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
        </div>
    </div>
</div>