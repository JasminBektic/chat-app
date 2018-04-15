// drop everything here and refactor later

function showMessage(html) {
    $('#chat-box').append(html);
}

$(document).ready(function(){
    var websocket = new WebSocket('ws://localhost:3030/php_playground/server.php'); 
    console.log(websocket);
    
    websocket.onopen = function(event) { 
        console.log(event);
        
        showMessage('<div class="text-success font-weight-bold">## Connection established!</div>');		
    }
    websocket.onmessage = function(event) {
        console.log('onmessage');
        console.log(event);
        var data = JSON.parse(event.data);
        showMessage('<div class="col-md-12 mt-3">' +data.message+ '</div>');
        $('#chat-message').val('');
    };
    
    websocket.onerror = function(event){
        console.log(event);
        showMessage('<div>Problem due to some Error</div>');
    };
    websocket.onclose = function(event){
        console.log(event);
        showMessage('<div>Connection Closed</div>');
    }; 


    // $(document).keypress(function(e) {
    //     var keycode = e.keyCode || e.which;
    //     if(keycode === '13') {
    //         sendData(websocket);
    //     }
    // });

    $('#btn-send').on('click', function(e) {
        sendData(websocket);
    });
});


function sendData(websocket) {
    var data = {
        username: $('#chat-user').val(),
        message: $('#chat-message').val()
    };

    websocket.send(JSON.stringify(data));
}
