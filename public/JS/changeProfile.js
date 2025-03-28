const fileInput = document.getElementById('avatar');
const fileName = document.querySelector('.file-name');
fileInput.addEventListener('change', function() {
    if (this.files && this.files.length > 0) {
    fileName.textContent = this.files[0].name;
} else {
    fileName.textContent = 'No file chosen';
}
});

document.addEventListener('DOMContentLoaded', function () {

    const deleteButtons = document.querySelectorAll('.delete-photo');

    deleteButtons.forEach(button => {
        button.addEventListener('click', function (e) {
            e.preventDefault();

            const photoId = this.getAttribute('data-id');
            const photoElement = this.closest('.photo');

            fetch('../../delete.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `id=${photoId}`
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! Status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        photoElement.remove();
                    } else {
                        alert('Error deleting photo: ' + (data.error || 'Unknown error'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error deleting photo. Please try again.');
                });
        });
    });
});