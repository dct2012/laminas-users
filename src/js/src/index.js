import '../../scss/style.scss';

(() => {
    document.querySelectorAll('.navbar-burger').forEach(el => {
        el.addEventListener('click', () => {
            const $target = document.getElementById(el.dataset.target);

            el.classList.toggle('is-active');
            $target.classList.toggle('is-active');
        });
    });

    document.querySelectorAll('.message-header > button.delete').forEach(
        B => B.addEventListener('click',
            () => B.parentNode.parentNode.parentNode.remove()
        )
    );
})();