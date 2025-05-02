<main class="archive-container">
    <div class="photos" id="photos-container">

    </div>
    <a href="/controllers/change-profile">
        <button class="btn archive">Back</button>
    </a>
</main>

<template id="photo_template">
    <div class="photo" data-id="${id}">
        <img src="${src}" alt="Photo">
        <div class="buttons">
            <button class="delete-photo btn" data-id="${id}">Delete</button>
            <button class="restore-photo btn" data-id="${id}">Restore</button>
        </div>
    </div>
</template>



