document.addEventListener('DOMContentLoaded', function () {

    const deleteModal = document.getElementById('deleteSlideModal');

    deleteModal?.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const slideId = button.getAttribute('data-id');

        document.getElementById('deleteSlideId').value = slideId;
    });
});