import {Fetcher} from "../../src/classes/js/Fetcher.js";
import {AvatarZoom} from "../../src/classes/js/AvatarZoom.js";
import {AvatarPreview} from "../../src/classes/js/AvatarPreview.js";
import {PhotosManager} from "../../src/classes/js/PhotosManager.js";
import {PostsManager} from "../../src/classes/js/PostsManager.js";

const fetcher = new Fetcher('/controllers/');

document.addEventListener('DOMContentLoaded', function () {
    const photos = new PhotosManager(fetcher, 'photo');
    const posts = new PostsManager(fetcher, 'post');

    new AvatarZoom('.avatar img', '.large-avatar-zoom', '#zoomed-avatar');
    new AvatarPreview('avatar', '.avatar-preview', '#avatar-preview-img', '.close-preview');


    posts.initDeletePosts('.delete-post', '.post');
    photos.initDeletePhotos('toArchive.php', '.delete-photo', '.photo', )
});