var player = 1;
$(document).ready(function () {
    
	$('.js-move').on('click', function (e) {
		e.preventDefault();
		e.stopPropagation();

		var field = $(this),
                    fieldParent = field.parent(),
                    x = fieldParent.data('x'),
                    y = fieldParent.data('y'),
                    currentPlayer = $('p.js-step > span').attr('id');
                    current = currentPlayer.match(/\d+$/)[0];
                    formAction = $('form[name="frm_table"]').attr('action');
                    gameID = formAction.substring(formAction.lastIndexOf('/') + 1);
                                               
		$.ajax({
                        url: formAction,
			type: 'POST',
			dataType: 'json',
			data: {
				x: x,
				y: y,
                                currentPlayer: current
			},
			success: function (data) {
				if (!data) {
					return;
				}

				var step = $('.js-step');
                                                                                                                               
				fieldParent.addClass('player' + player);

				if (data['winner']) {
					step.html('<p>Winner is player: <span class="icon player' + player + '">' + data['winner'] + ' - </span>!</p><p><a href="/game/create/">New game</a></p>');
					var x = 0, y = 0;
					if (Object.keys(data['winnerCells']).length == 1) {
						x = Object.keys(data['winnerCells'])[0];
						$.each(data['winnerCells'][x], function (index) {
							y = index;
							setWinnerFields(x, y)
						});
					} else {
						$.each(data['winnerCells'], function (index, value) {
							x = index;
							y = Object.keys(value)[0];
							setWinnerFields(x, y)
						});
					}
                                        
                                    if ($('ul#ul_listing li').length > 0){
                                        $('ul#ul_listing li:last').after('<li class="list-group-item"><span>'+$('#ul_details li:first').text()+', Status: finished</span><br><a href="/game/view/'+gameID+'">'+$('#ul_details li:last').text()+'</a></li>');
                                    } else {
                                        $('ul#ul_listing').append('<li class="list-group-item"><span>'+$('#ul_details li:first').text()+', Status: finished</span><br><a href="/game/view/'+gameID+'">'+$('#ul_details li:last').text()+'</a></li>');
                                    }  
                                    
                                                                                
				} else if (!data['winner'] && !data['playerID']) {
					step.html('<p>Draw!</p>');
				} else if (data['playerID']) {
					step.html('<p class="js-step">Player <span class="icon player' + data['playerSymbol'] + '" id="pl_step_'+data['playerSymbol']+'">' + data['playerName'] + ' - </span></p>');
					player = data['playerSymbol'];
				}

				field.remove();
			},
                        error:function (xhr, status){
                                        alert(JSON.stringify(xhr));                                      
                         }   
		});
	});
        
        var setWinnerFields = function(x,y){
		$('[data-x=' + x + '][data-y=' + y + ']').addClass('winner');
	};       
        
});