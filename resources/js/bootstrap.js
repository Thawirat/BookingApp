import _ from 'lodash';
window._ = _;

import './bootstrap';
import 'bootstrap';

import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

import Echo from 'laravel-echo';
import Reverb from 'laravel-reverb';

window.Echo = new Echo({
    broadcaster: Reverb,
    key: 'm8kofo8l4flxihzwkhip',
    wsHost: window.location.hostname,
    wsPort: 8080,
    wssPort: 8080,
    forceTLS: false,
    enabledTransports: ['ws', 'wss'],
});
