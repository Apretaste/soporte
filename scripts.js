$(document).ready(function () {
	$('.modal').modal();
});

function sendMessage() {
	let message = $('#message').val().trim();
	if(message.length>=30){
		apretaste.send({
			'command':'SOPORTE ESCRIBIR',
			'data':{'message':message},
			'redirect':false,
			'callback':{'name':'sendMessageCallback','data':message}
		});
	}
	else M.toast({html: 'Mínimo 30 caracteres'});
}

function messageLengthValidate() {
	let message = $('#message').val().trim();
	if(message.length<=1000) $('.helper-text').html('Restante: '+(1000-message.length));
	else $('.helper-text').html('Limite excedido');
}

function sendMessageCallback(message) {
	// if first time, change headers
	if($('#main .chat').length == 0) {
		$('#main h1').remove();
		$('#main p').remove();
		$('#main').append('<h1>Su conversación con el soporte</h1>');
		$('#main').append('<div class="chat"></div>');
	};

	// create bubble date
	let now = new Date(Date.now()).toLocaleString();
	now = now.replace('p. m.','pm');
	now = now.replace('a. m.','am');

	// append the bubble to teh screen
	$('.chat').append(`
		<div class="bubble me">
			<small>
				<b>`+myUsername+`</b> - `+now+` 
				<div class="chip small white-text blue lighten-1">NEW</div>
			</small>
			<br>
			`+message+`
		</div>`);

	// scroll to the last bubble
	var lastBubble = $(".bubble:last-of-type");
	if(lastBubble) {
		$('html, body').animate({
			scrollTop: $(".bubble:last-of-type").offset().top
		}, 1000);
	}
}