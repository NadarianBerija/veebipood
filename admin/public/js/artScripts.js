document.addEventListener('DOMContentLoaded', () => {
    const checkbox  = document.getElementById('toggleExtra');
    const extraFields = document.querySelectorAll('.extraFields');
    const requiredFields = document.querySelectorAll('.requiredField');

    const updateFields = () => {
        const show = checkbox.checked;
        extraFields.forEach(f => f.style.display = show ? 'block' : 'none');
        requiredFields.forEach(f => f.required = show);
    };
    if(checkbox) {
        updateFields();
        checkbox.addEventListener('change', updateFields);
    }
});