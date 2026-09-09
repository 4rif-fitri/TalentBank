import * as bootstrap from 'bootstrap';

window.bootstrap = bootstrap;

import * as shortListTemplate from './templates/shortList/shortList.js';
import * as shortListRender from './render/shortList.js';
import * as salert from './utils/alert.js';
import * as invitation from './templates/invitation.js';
import * as interview from './templates/Interview.js';
import * as talent from './templates/talent.js';
import * as jobOffer from './templates/jobOffer.js';
import * as format from './utils/format.js';
import * as debug from './utils/debug.js';
import * as modal from './utils/modal.js'

import * as templateEducation from "./features/education/education.js"
import * as templateImage from "./features/image/image.js"
import * as templateInterview from "./features/interview/interview.js"
import * as templateSkill from "./features/skills/skills.js"
import * as templatelanguage from "./features/language/language.js"
import * as templateSocialMedia from "./features/social-media/social-media.js"
import * as templateJobOffer from "./features/job-offer/job-offer.js"
import * as xvalidate from "./shared/utils/validation.js"
import * as xformat from "./shared/utils/format.js"

window.xvalidate = xvalidate;
window.xformat = xformat;
window.xeducation = templateEducation;
window.ximage = templateImage
window.xalert = salert;
window.xskill = templateSkill;
window.xlanguage = templatelanguage;
window.xlink = templateSocialMedia;
window.xjobOffer = templateJobOffer;
window.xinterview = templateInterview;

window.modal = modal
window.xmodal = modal
window.shortListTemplate = shortListTemplate;
window.shortListRender = shortListRender;
window.invitation = invitation;
window.talent = talent;
window.intervieww = interview;
window.salert = salert;
window.format = format;
window.debug = debug;
window.xdebug = debug;
window.jobOffer = jobOffer;
window.bootstrap = bootstrap;

