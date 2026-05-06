document.querySelectorAll('.toggle-delete, .toggle-delete-user').forEach(btn => {
    btn.addEventListener('click', function() {

        const url = this.classList.contains('toggle-delete-user') ? 'toggleDeleteUser' : 'toggleDeleteArt';

        fetch(url, {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: 'id=' + this.dataset.id
        })
        .then(res => res.json())
        .then(data => {
            if (!data.success) return;

            const icon = this.querySelector('i');

            if (data.is_deleted == 1) {
                this.classList.remove('btn-warning');
                this.classList.add('btn-success');

                icon.classList.remove('bi-trash');
                icon.classList.add('bi-arrow-counterclockwise');

            } else {
                this.classList.remove('btn-success');
                this.classList.add('btn-warning');

                icon.classList.remove('bi-arrow-counterclockwise');
                icon.classList.add('bi-trash');
            }
        });
    });
});