var HAND_UP_TEXT = 'Raise hand up';
var HAND_DOWN_TEXT = 'Raise hand down';
let host = window.location.hostname;
var ws = new WebSocket('ws://'+host+':3012');
ws.onopen = function () {
    subscribe('user_state_changed');
    subscribe('user_offline');
    subscribe('user_online');
    subscribe('user_profile_updated');
    ws.onmessage = function (event) {
        let json = JSON.parse(event.data);
        let message = json.message;
        let channel = json.channel;
        window[channel + '_handler'](message);
    };
    console.log('opened');
    publish('user_online', JSON.stringify({
            id: USER_ID,
            firstName: USER_FIRST_NAME,
            lastName: USER_LAST_NAME,
            email: USER_EMAIL,
            isHandRaised: USER_HAND,
            online: true,
        })
    );
};

window.onclose = function () {
    publish('user_offline', JSON.stringify({
            id: USER_ID,
            isHandRaised: false,
            online: false,
        })
    );
    ws.close();
}

function subscribe(channel) {
    ws.send(JSON.stringify({
        request: 'SUBSCRIBE',
        message: '',
        channel: channel
    }));
}

function publish(channel, message) {
    ws.send(JSON.stringify({
        request: 'PUBLISH',
        message: message,
        channel: channel
    }));
}

function user_state_changed_handler(message) {
    let json = JSON.parse(message);
    let id = json['id'];
    let user_row = $('[data-user-id=' + id + ']');
    let user_hade = user_row.find('[name=user-hade]');
    let user_hand = user_row.find('[name=user-hand]');
    let newHandCss = json['isHandRaised'] ? 'unset' : 'hidden';
    user_hade.css('visibility', newHandCss);
    if (USER_ID === id)
        user_hand.text(json['isHandRaised'] ? HAND_DOWN_TEXT : HAND_UP_TEXT);
}

function user_offline_handler(message) {
    console.log('closed');
    updateMembers();
}

function user_online_handler(message) {
    console.log('online');
    updateMembers();
}

function user_profile_updated_handler(message) {
    let json = JSON.parse(message);
    let id = json['id'];
    let user_row = $('[data-user-id=' + id + ']');
    let user_name = user_row.find('[name=user-name]');
    let newName = json['firstName'] + ' ' + json['lastName'];
    user_name.text(newName.length !== 0 ? newName : json['email']);
    if (USER_ID === id)
        $($('#user-id a')[0]).text(newName);
    updateMembers();
}

function updateMembers() {
    $.ajax({
        method: "GET",
        url: URL_SITE_MEMBERS,
    })
        .done(function (data) {
            $('#members').html(data);
        });
}