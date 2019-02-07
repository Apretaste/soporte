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
            'callback':{'name':'sendMessageCallback','data':{'message':message, 'username':myUsername}}
        });
    }
    else showToast('Minimo 30 caracteres');
}

function messageLengthValidate() {
    let message = $('#message').val().trim();
    if(message.length<=1000) $('.helper-text').html('Restante: '+(1000-message.length));
    else $('.helper-text').html('Limite excedido');
}

function sendMessageCallback(data){
    let message = data.message;
    let username = data.username;
    if($('#main .chat').length==0){
        $('#main p').remove();
        $('#main').append('<h5 class="center-align">Su conversación con el soporte</h5>')
        $('#main').append('<div class="chat"></div>')
    };
    let now = new Date(Date.now()).toLocaleString();
    now = now.replace('p. m.','pm');
    now = now.replace('a. m.','am');
    
    $('.chat').append(`
            <div class="bubble me">
                <small>
                    <b>`+myUsername+`</b> - `+now+` 
                    <div class="chip small white-text blue lighten-1">NEW</div>
                </small>
                <br>
                `+message+`
            </div>
    `);
}

function showToast(text){
    M.toast({html: text});
}