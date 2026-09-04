import $ from 'jquery';

window.$ = $;
window.jQuery = $;

await import('jquery-ui/ui/widgets/datepicker');
await import('select2');
await import('owl.carousel');
await import('bootstrap-datepicker');

const { default: DataTable } = await import('datatables.net-dt');

import * as bootstrap from 'bootstrap';
import Swal from 'sweetalert2';

window.bootstrap = bootstrap;
window.DataTable = DataTable;
window.Swal = Swal;

await import('./api.js');

await import('./layout/newLayout.js');

import * as shortListTemplate from './templates/shortList/shortList.js';
import * as shortListRender from './render/shortList.js';
import * as salert from './utils/alert.js';
import * as invitation from './templates/invitation.js';
import * as interview from './templates/Interview.js';
import * as talent from './templates/talent.js';
import * as jobOffer from './templates/jobOffer.js';
import * as format from './utils/format.js';
import * as debug from './utils/debug.js';

window.shortListTemplate = shortListTemplate;
window.shortListRender = shortListRender;
window.invitation = invitation;
window.talent = talent;
window.intervieww = interview;
window.salert = salert;
window.format = format;
window.debug = debug;
window.jobOffer = jobOffer;
window.bootstrap = bootstrap;
