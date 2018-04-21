// drop everything here and refactor later

var websocket;

$('#join').on('click', function(e) {
    $('.login').css({display:'none'});
    connect();
});

$('#user').keyup(function(e) {
    $join_btn = $('#join');
    if (this.value !== '') {
        $join_btn.prop('disabled', false);
    } else {
        $join_btn.prop('disabled', true);
    }
});

$('#message').keypress(function(e) {
    if(e.keyCode == 13) {
        $('#send').click();
        e.preventDefault();
    }
});

$('#send').on('click', function(e) {
    sendData();
});


function connect() {
    websocket = new WebSocket('ws://localhost:3030/php_playground/server.php');

    websocket.onopen = function(event) { 
        var data = {
            username: $('#user').val()
        };
        websocket.send(JSON.stringify(data));
        generateMessage('<div class="text-success font-weight-bold">## Connection established!</div>');		
    }

    websocket.onmessage = function(event) {
        var data = JSON.parse(event.data);
        generateMessage('<div class="col-md-12 mt-3">' +data.message+ '</div>');
        $('#message').val('');
    };
    
    websocket.onerror = function(event){
        generateMessage('<div class="text-danger font-weight-bold"># Problem due to some Error</div>');
    };

    websocket.onclose = function(event){
        generateMessage('<div class="text-danger font-weight-bold">## Connection Closed</div>');
    }; 
};

function generateMessage(html) {
    $('#chat-box').append(html);
}

function sendData() {
    var data = {
        username: $('#user').val(),
        message: $('#message').val()
    };

    websocket.send(JSON.stringify(data));
}
