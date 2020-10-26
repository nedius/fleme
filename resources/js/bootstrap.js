window._ = require('lodash');

/**
 * We'll load jQuery and the Bootstrap jQuery plugin which provides support
 * for JavaScript based Bootstrap features such as modals and tabs. This
 * code may be modified to fit the specific needs of your application.
 */

// try {
//     window.Popper = require('popper.js').default;
//     window.$ = window.jQuery = require('jquery');

//     require('bootstrap');
// } catch (e) {}

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = require('axios');

window.axios.defaults.headers.common = {//'Authorization': `Bearer ${localStorage.getItem('token')}`,
                                        'X-Requested-With': 'XMLHttpRequest'};

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

// console.log('bootstrap loaded');

import Echo from 'laravel-echo';

window.Pusher = require('pusher-js');

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: process.env.MIX_PUSHER_APP_KEY,
    // cluster: process.env.MIX_PUSHER_APP_CLUSTER,
    encrypted: true,
    wsHost: window.location.hostname,
    wsPort: 6001,
    wssPort: 6001,
    disableStats: true,
    disabledTransports: ['sockjs', 'xhr_polling', 'xhr_streaming'],
    // enabledTransports: ['ws'],
    forceTLS: false,
    auth: {
        headers: {
            Authorization: 'Bearer ' + localStorage.getItem('token')
        },
    },
});

window.isEmpty = (str) => {
    return (!str || 0 === str.length);
}
window.isBlank = (str) => {
    return (!str || /^\s*$/.test(str));
}
String.prototype.isEmpty = function() {
    return (this.length === 0 || !this.trim());
};

window.clog = (obj) => {
    console.log(obj);
}