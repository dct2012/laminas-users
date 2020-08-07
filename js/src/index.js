import $ from 'jquery';
import 'bootstrap/js/dist/alert';
import '../../scss/style.scss';
import hljs from 'highlight.js/lib/core';
import xml from 'highlight.js/lib/languages/xml';
import 'highlight.js/styles/monokai.css';


(() => {
    $('#show-password').on('click', () => {
        const $password = $('.toggle-password')
        if ($password.attr('type') === 'password') {
            $password.attr('type', 'text');
        } else {
            $password.attr('type', 'password');
        }
    });

    hljs.registerLanguage('xml', xml);
    hljs.highlightBlock(document.querySelector('#sitemap_code_block'));
})();