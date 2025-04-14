

const fileInput = document.getElementById('avatar');
const fileName = document.querySelector('.file-name');
fileInput.addEventListener('change', function () {
    if (this.files && this.files.length > 0) {
        fileName.textContent = this.files[0].name;
    } else {
        fileName.textContent = 'No file chosen';
    }
});
import {Fetcher} from "./Fetcher.js";
const fetcher = new Fetcher('/controllers/');

document.addEventListener('DOMContentLoaded', function () {
    initDeleteImages();

    initDeletePosts();

    const avatarInput = document.getElementById('avatar');
    const avatarPreview = document.querySelector('.avatar-preview');
    const avatarPreviewImg = document.getElementById('avatar-preview-img');
    const avatarCloseButton = avatarPreview.querySelector('.close-preview');

    avatarInput.addEventListener('change', function (event) {
        const file = event.target.files[0];
        if (file) {
            if (!file.type.startsWith('image/')) {
                alert('Please select an image file (JPG, PNG, etc.)');
                avatarInput.value = '';
                avatarPreview.style.display = 'none';
                return;
            }

            const reader = new FileReader();
            reader.onload = function (e) {
                avatarPreviewImg.src = e.target.result;
                avatarPreview.style.display = 'block';
            };
            reader.onerror = function () {
                alert('Error reading the file. Please try again.');
                avatarPreview.style.display = 'none';
            };
            reader.readAsDataURL(file);
        } else {
            avatarPreview.style.display = 'none';
        }
    });

    avatarCloseButton.addEventListener('click', function () {
        avatarPreview.style.display = 'none';
        avatarInput.value = '';
    });
});

function initDeleteImages() {
    const deletePhotoButtons = document.querySelectorAll('.delete-photo');
    deletePhotoButtons.forEach(button => {
        button.addEventListener('click', function (e) {
            e.preventDefault();
            const photoId = this.getAttribute('data-id');
            const photoElement = this.closest('.photo');

            deleteImage(photoId, photoElement);
        });
    });
}

async function deleteImage(photoId, photoElement) {
    try {
        const data = await fetcher.post('delete.php', {
            id: photoId,
            type: 'photo',
        });
        if (data.success) {
            photoElement.remove();
        } else {
            alert('Error deleting photo: ' + (data.error || 'Unknown error'));
        }
    } catch (error) {
        alert('Error deleting photo. Please try again.');
    }
}

function initDeletePosts() {
    const deletePostButtons = document.querySelectorAll('.delete-post');
    deletePostButtons.forEach(button => {
        button.addEventListener('click', async function (e) {
            e.preventDefault();
            const postId = this.getAttribute('data-id');
            const postElement = this.closest('.post');

            deletePost(postId, postElement);
        });
    });
}

async function deletePost(postId, postElement) {
    try {
        const data = await fetcher.post('delete.php', {
            id: postId,
            type: 'post',
        });
        if (data.success) {
            postElement.remove();
        } else {
            alert('Error deleting post: ' + (data.error || 'Unknown error'));
        }
    } catch (error) {
        alert('Error deleting post. Please try again.');
    }
}