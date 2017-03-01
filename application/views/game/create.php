
<h2><?php echo $title; ?></h2>
<div class="well">
    
<?php echo validation_errors(); ?>
<?php echo form_open('game/create', array('name'=> 'create_game_form')); ?>

    <label for="player_one">Player One</label>
    <div class="form-group">
        <input type="input" name="player_one" class="form-control" />
    </div>

    <label for="player_two">Player Two</label>
    <div class="form-group">
        <input type="input" name="player_two" class="form-control" />
    </div>
        
    <div class="form-group">
        <input type="submit" name="submit" value="Start" class="btn btn-primary" />
    </div>
    

<?php echo form_close(); ?>
</div>