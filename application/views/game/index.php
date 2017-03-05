<h2><?php echo $title; ?></h2>
 <ul class="list-group">
<?php foreach ($games as $game): ?>
       <li class="list-group-item">
                <span><?php echo "Started: ".$game['game_start'].", Status: ".$game['game_status']; ?></span>
                <br>
                <a href="game/view/<?php echo $game['game_id']; ?>"><?php echo $game['player_one_nick']." Vs ".$game['player_two_nick']; ?></a>
        </li>
<?php endforeach; ?>
 </ul>