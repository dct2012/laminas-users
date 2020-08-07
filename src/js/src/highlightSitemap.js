import hljs from 'highlight.js/lib/core';
import xml from 'highlight.js/lib/languages/xml';
import 'highlight.js/styles/monokai.css';

(() => {
    hljs.registerLanguage('xml', xml);
    const $block = document.querySelector('#sitemap_code_block');
    if ($block) {
        hljs.highlightBlock($block);
    }
})();