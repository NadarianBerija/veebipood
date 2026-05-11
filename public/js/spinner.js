document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('form');
    const submitBtn = document.getElementById('submitBtn');
    const spinner = document.getElementById('btnSpinner');
    const btnText = document.getElementById('btnText');

    if (!form) return;

    form.addEventListener('submit', function () {
        submitBtn.disabled = true;

        spinner.classList.remove('d-none');

        btnText.textContent = submitBtn.dataset.sendingText;
    });
});