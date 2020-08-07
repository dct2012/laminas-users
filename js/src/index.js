import $ from 'jquery';
import 'bootstrap/js/dist/alert';
import '../../scss/style.scss';

(() => {
    $('#show-password').on('click', () => {
        const $password = $('.toggle-password')
        if ($password.attr('type') === 'password') {
            $password.attr('type', 'text');
        } else {
            $password.attr('type', 'password');
        }
    });
})();