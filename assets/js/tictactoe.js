var player = 1;

$(document).ready(function () {
	var setWinnerCell = function(x,y){
		$('[data-x=' + x + '][data-y=' + y + ']').addClass('winner');
	};

	$('.js-move').on('click', function (e) {
		e.preventDefault();
		e.stopPropagation();

		var field = $(this),
			fieldParent = field.parent(),
			x = fieldParent.data('x'),
			y = fieldParent.data('y');
                
                console.log($('form[name="frm_table"]').attr('action'));                
                console.log("x: " + x + "y: "+y);  
                
		$.ajax({
			url: $('form[name="frm_table"]').attr('action'),
			type: 'POST',
			dataType: 'json',
			data: {
				x: x,
				y: y
			},
			success: function (data) {
				if (!data) {
					return;
				} else {
                                    alert(JSON.stringify(data));
                                }

				var step = $('.js-step'), endGameBlock = $('.js-end-game');
				fieldParent.addClass('player' + player);

				if (data['winner']) {
					step.remove();
					endGameBlock.html('<p>Win player <span class="icon player' + player + '">' + player + ' - </span>!</p>');
					var x = 0, y = 0;
					if (Object.keys(data['winnerCells']).length == 1) {
						x = Object.keys(data['winnerCells'])[0];
						$.each(data['winnerCells'][x], function (index) {
							y = index;
							setWinnerCell(x, y)
						});
					} else {
						$.each(data['winnerCells'], function (index, value) {
							x = index;
							y = Object.keys(value)[0];
							setWinnerCell(x, y)
						});
					}
				} else if (!data['winner'] && !data['player']) {
					step.remove();
					endGameBlock.html('<p>Draw!</p>');
				} else if (data['player']) {
					step.html('<p class="js-step">Step makes the player <span class="icon player' + data['player'] + '">' + data['player'] + ' - </span></p>');
					player = data['player'];
				}

				field.remove();
			}
		});
	});
});