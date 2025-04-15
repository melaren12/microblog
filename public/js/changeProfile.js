import {Fetcher} from "../../src/classes/js/Fetcher.js";
import {AvatarZoom} from "../../src/classes/js/AvatarZoom.js";
import {AvatarPreview} from "../../src/classes/js/AvatarPreview.js";
import {DeleteManager} from "../../src/classes/js/DeleteManager.js";

const fileInput = document.getElementById('avatar');
const fileName = document.querySelector('.file-name');
fileInput.addEventListener('change', function () {
    if (this.files && this.files.length > 0) {
        fileName.textContent = this.files[0].name;
    } else {
        fileName.textContent = 'No file chosen';
    }
});

const fetcher = new Fetcher('/controllers/');

document.addEventListener('DOMContentLoaded', function () {

    new AvatarZoom('.avatar img', '.large-avatar-zoom', '#zoomed-avatar');
    new AvatarPreview('avatar', '.avatar-preview', '#avatar-preview-img', '.close-preview');

    const deleteManager = new DeleteManager(fetcher);

    deleteManager.initDeleteItems('.delete-photo', 'photo', '.photo');
    deleteManager.initDeleteItems('.delete-post', 'post', '.post');

});