import {ProfileManager} from "../../src/classes/js/ProfileManager.js";
import {Fetcher} from "../../src/classes/js/Fetcher.js";

const fetcher = new Fetcher('/controllers/');

document.addEventListener('DOMContentLoaded', function () {
    const profile = new ProfileManager(fetcher);

    profile.init();
});