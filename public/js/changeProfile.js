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
import {AvatarZoom} from "./AvatarZoom.js";
import {AvatarPreview} from "./AvatarPreview.js";

const fetcher = new Fetcher('/controllers/');

document.addEventListener('DOMContentLoaded', function () {

    new AvatarZoom('.avatar img', '.large-avatar-zoom', '#zoomed-avatar');
    new AvatarPreview('avatar', '.avatar-preview', '#avatar-preview-img', '.close-preview');

    initDeleteImages();

    initDeletePosts();

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
