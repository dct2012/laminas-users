(() => {
    document.querySelector('#show-password').addEventListener('click', () => {
        document.querySelectorAll('.toggle-password').forEach(element => {
            if (element.getAttribute('type') === 'password') {
                element.setAttribute('type', 'text');
            } else {
                element.setAttribute('type', 'password');
            }
        });
    });
})();