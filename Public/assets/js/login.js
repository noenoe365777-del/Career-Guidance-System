<script>
const togglePass = document.getElementById('togglePass');
const passwordInput = document.getElementById('password');

if (togglePass && passwordInput) {
    togglePass.addEventListener('click', () => {
        const icon = togglePass.querySelector('i');
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);

        if (type === 'text') {
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    })
}



</script>