import { getProfile } from '../../api/profile.js';
import { renderProfile } from './renderer.js';
import { initProfileEvents } from './events.js';
import { profileState } from './state.js';

export function initProfile() {
    initProfileEvents();
    loadProfile();
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
