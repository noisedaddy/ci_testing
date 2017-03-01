<h2><?php echo $title; ?></h2>

<?php foreach ($games as $game): ?>

        <div class="main">
                <?php echo "Started: ".$game['start_on']; ?>
        </div>
        <p>
            <?php echo $game['player_one_nick']." Vs ".$game['player_two_nick']; ?>
        </p>

<?php endforeach; ?>