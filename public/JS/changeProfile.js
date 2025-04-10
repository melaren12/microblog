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

    const deletePhotoButtons = document.querySelectorAll('.delete-photo');
    deletePhotoButtons.forEach(button => {
        button.addEventListener('click', function (e) {
            e.preventDefault();
            const photoId = this.getAttribute('data-id');
            const photoElement = this.closest('.photo');

            fetch('/controllers/delete.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `id=${photoId}&type=photo`
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


    const deletePostButtons = document.querySelectorAll('.delete-post');
    deletePostButtons.forEach(button => {
        button.addEventListener('click', function (e) {
            e.preventDefault();
            const postId = this.getAttribute('data-id');
            const postElement = this.closest('.post');

            fetch('/controllers/delete.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `id=${postId}&type=post`
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! Status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        postElement.remove();
                    } else {
                        alert('Error deleting post: ' + (data.error || 'Unknown error'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error deleting post. Please try again.');
                });
        });
    });
});