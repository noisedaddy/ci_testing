<h2><?php echo $title; ?></h2>
<?php foreach ($games as $game): ?>
        <div class="main">
                <?php echo "Started: ".$game['game_start'].", Status: ".$game['game_status']; ?>
        </div>
        <p>
            <a href="view/<?php echo $game['game_id']; ?>"><?php echo $game['player_one_nick']." Vs ".$game['player_two_nick']; ?></a>
        </p>
<?php endforeach; ?>