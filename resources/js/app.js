import * as bootstrap from 'bootstrap';
import "../scss/app.scss";
import * as templateEducation from "./features/education/template.js"
import * as templateImage from "./features/image/template.js"
import * as templateInterview from "./features/interview/template.js"
import * as templateSkill from "./features/skills/template.js"
import * as templatelanguage from "./features/language/template.js"
import * as templateSocialMedia from "./features/social-media/template.js"
import * as templateJobOffer from "./features/job-offer/template.js"
import * as templateInvitation from "./features/invitation/template.js"
import * as templateCommon from "./features/common/template.js"
import * as templateShortList from "./features/shortList/template.js"
import * as templateProfile from "./features/profile/template.js"
import * as templateResume from "./features/resume/template.js"
import * as templateResult from "./features/result/template.js"

import * as xvalidate from "./shared/utils/validation.js"
import * as xformat from "./shared/utils/format.js"
import * as xmodal from "./shared/ui/modal.js"
import * as xalert from "./shared/ui/alert.js"

import * as xApiInvite from "./features/invitation/api.js"
import * as xApiInterview from "./features/interview/api.js"
import * as xApiJobOffer from "./features/job-offer/api.js"
import * as xApiPosition from "./features/position/api.js"
import * as xApiEducation from "./features/education/api.js"
import * as xApiProfile from "./features/profile/api.js"

import * as measureBlocks from "../js/features/resume/engine/measurement.js";
import * as paginateColumn from "../js/features/resume/engine/pagination.js";
import * as  paginateTwoColumns from "../js/features/resume/engine/pagination.js";
import * as renderPages from "../js/features/resume/engine/renderer.js";
import * as modern from "../js/features/resume/templates/modern.js";
import * as professional from "../js/features/resume/templates/professional.js";

window.xApiProfile = xApiProfile
window.xApiInvite = xApiInvite
window.xApiInterview = xApiInterview
window.xApiJobOffer = xApiJobOffer
window.xApiPosition = xApiPosition
window.xApiEducation = xApiEducation

window.xvalidate = xvalidate;
window.xformat = xformat;
window.xmodal = xmodal
window.xalert = xalert

window.xcommon = templateCommon
window.xeducation = templateEducation;
window.xshortList = templateShortList
window.ximage = templateImage
window.xskill = templateSkill;
window.xlanguage = templatelanguage;
window.xlink = templateSocialMedia;
window.xjobOffer = templateJobOffer;
window.xinterview = templateInterview;
window.xinvitation = templateInvitation;
window.xprofile = templateProfile
window.xresume = templateResume
window.xresult = templateResult

window.bootstrap = bootstrap;

window.measureBlocks = measureBlocks;
window.paginateTwoColumns = paginateTwoColumns;
window.paginateColumn = paginateColumn;
window.renderPages = renderPages;
window.Modern = modern;
window.Professional = professional;

window.toggle = () => document.body.classList.toggle('filter-open')
