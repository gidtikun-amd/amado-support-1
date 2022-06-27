require('./bootstrap');

window.Vue = require('vue');
window.axios = require('axios');

Vue.component('login', require('./components/login.vue').default);
Vue.component('change-password', require('./components/changepassword.vue').default);

Vue.component('it-emplyee-index', require('./components/admin/employee/index.vue').default);
Vue.component('it-department-index', require('./components/admin/department/index.vue').default);
Vue.component('it-assettype-index', require('./components/admin/assettype/index.vue').default);
Vue.component('it-location-index', require('./components/admin/location/index.vue').default);
Vue.component('it-asset-index', require('./components/admin/asset/index.vue').default);
Vue.component('it-asset-detail', require('./components/admin/asset/detail.vue').default);
Vue.component('it-asset-paper', require('./components/admin/asset/paper.vue').default);
Vue.component('it-asset-swlicense', require('./components/admin/asset/swlicense.vue').default);
Vue.component('it-asset-swldetail', require('./components/admin/asset/swldetail.vue').default);
Vue.component('it-jobreq-index', require('./components/admin/jobreq/index.vue').default);
Vue.component('it-jobreq-files', require('./components/admin/jobreq/files.vue').default);
Vue.component('it-jobreq-survey', require('./components/admin/jobreq/survey.vue').default);
Vue.component('it-report-asset', require('./components/admin/report/asset.vue').default);
Vue.component('it-report-license', require('./components/admin/report/license.vue').default);
Vue.component('it-report-problemticket', require('./components/admin/report/problemticket.vue').default);
Vue.component('it-report-tranfer-asset', require('./components/admin/report/problemtransferasset.vue').default);
Vue.component('it-report-summary-support', require('./components/admin/report/supportsummary.vue').default);
Vue.component('it-admin-index', require('./components/admin/index.vue').default);
Vue.component('it-itmreq-index', require('./components/admin/itemreq/index.vue').default);
Vue.component('it-itmreq-index-crm', require('./components/admin/itemreq/historycrm.vue').default);
Vue.component('it-itmreq-files', require('./components/admin/itemreq/files.vue').default);
Vue.component('it-ticket-list', require('./components/admin/ticket/index.vue').default);
Vue.component('it-ticket-survey', require('./components/admin/ticket/survey.vue').default);
Vue.component('it-ticket-subject', require('./components/admin/ticket/subject.vue').default);
Vue.component('it-role-management', require('./components/admin/role/management.vue').default);
Vue.component('it-role-page-menu', require('./components/admin/role/pageandmenu.vue').default);
Vue.component('it-role-user', require('./components/admin/role/user.vue').default);
Vue.component('it-itmemo-list', require('./components/admin/memo/index.vue').default);
Vue.component('it-itmemo-files', require('./components/admin/memo/files.vue').default);
Vue.component('it-extpb-index', require('./components/admin/externalpb/index.vue').default);
Vue.component('it-extpb-syssts', require('./components/admin/externalpb/sysstatus.vue').default);
Vue.component('it-exception', require('./components/admin/report/exception.vue').default);
Vue.component('it-report-nouppaper', require('./components/admin/report/nonuppaperfiles.vue').default);

Vue.component('admin-equ-management', require('./components/administrative/equipmentreq/management.vue').default);
Vue.component('admin-equ-items', require('./components/administrative/equipmentreq/items.vue').default);
Vue.component('admin-jobreq-management', require('./components/administrative/jobreq/management.vue').default);
Vue.component('admin-report-summary-budget-eqreq', require('./components/administrative/report/summaryBudgetEqreq.vue').default);
Vue.component('admin-report-summary-eqreq', require('./components/administrative/report/summaryEqreq.vue').default);

Vue.component('user-jobreq-create', require('./components/user/jobreq/send.vue').default);
Vue.component('user-jobreq-history', require('./components/user/jobreq/history.vue').default);
Vue.component('user-jobreq-survey', require('./components/user/jobreq/survey.vue').default);
Vue.component('user-index', require('./components/user/index.vue').default);
Vue.component('user-itemreq-create', require('./components/user/itemreq/send.vue').default);
Vue.component('user-itemreq-create-crm', require('./components/user/itemreq/sendcrm.vue').default);
Vue.component('user-itemreq-history', require('./components/user/itemreq/history.vue').default);
Vue.component('user-itemreq-history-crm', require('./components/user/itemreq/historycrm.vue').default);
Vue.component('user-itemreq-approve', require('./components/user/itemreq/approve.vue').default);
Vue.component('user-ticket-create', require('./components/user/ticket/send.vue').default);
Vue.component('user-ticket-history', require('./components/user/ticket/history.vue').default);
Vue.component('user-ticket-survey', require('./components/user/ticket/survey.vue').default);
Vue.component('user-memo-create', require('./components/user/memo/send.vue').default);
Vue.component('user-memo-history', require('./components/user/memo/history.vue').default);
Vue.component('user-ad-equ-create', require('./components/administrative/equipmentreq/send.vue').default);
Vue.component('user-ad-equ-history', require('./components/administrative/equipmentreq/history.vue').default);
Vue.component('user-ad-jobreq-create', require('./components/administrative/jobreq/send.vue').default);
Vue.component('user-ad-jobreq-history', require('./components/administrative/jobreq/history.vue').default);
Vue.component('user-asset-index', require('./components/user/asset/index.vue').default);




