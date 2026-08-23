// <==== API ====>
import { getProfile } from '../../api/profile.js';
import { getAllSocialMedia } from '../../api/socialMedia.js';
import { getAllLanguages } from "../../api/language.js"
// <==== API ====>
import { renderProfile } from './renderer.js';
import { initProfileEvents } from './events.js';
import { profileState, socialMedia, languages } from './state.js';

export function initProfile() {
    loadProfile()
    loadAllSocialMedia()
    loadAllanguages()
    initProfileEvents();
}

async function loadProfile() {
    const profileId = window.appConfig.userId;

    if (!profileId) {
        console.error('Profile ID missing');
        return;
    }

    profileState.loading = true;

    try {
        const response = await getProfile(profileId);
        profileState.data = response.data;

        console.log("Data", profileState.data);
        renderProfile();

    } catch (error) {
        console.error('Failed to load profile:',error);

    } finally {
        profileState.loading = false;
    }
}

async function loadAllSocialMedia(){

    socialMedia.loading = true;

    try {
        const response = await getAllSocialMedia();
        socialMedia.data = response.data;
        // console.log("Data", socialMedia.data);

    } catch (error) {
        console.error('Failed to load Social Media:', error);

    } finally {
        socialMedia.loading = false;
    }
}

async function loadAllanguages() {
    languages.loading = true;

    try {
        const response = await getAllLanguages();
        languages.data = response.data;
        console.log("Data", languages.data);

    } catch (error) {
        console.error('Failed to load All languages:', error);

    } finally {
        languages.loading = false;
    }
}


