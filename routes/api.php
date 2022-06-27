<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//Api Login
Route::prefix('login')->group( function () {
    Route::resource('/','Security\LoginController');
    Route::post('checkin','Security\LoginController@login');
    Route::get('checkout','Security\LoginController@logout');
    Route::get('session','Security\LoginController@showSessionLogin');
    Route::post('changepassword','Security\LoginController@changePasswore');
    Route::get('check-exist-session','Security\LoginController@checkSessionExistFontEnd');
});

//API CRM
Route::prefix('crm')->group(function(){
    Route::prefix('product')->group(function(){
        Route::get('list','Api\crmController@getProductList');
        Route::get('group-list','Api\crmController@getProductGroupList');
    });
    Route::prefix('customer')->group(function(){
        Route::get('group-list','Api\crmController@getCustomerGroupList');
    });
    Route::prefix('item-request')->group(function(){
        Route::post('create','Api\crmController@saveCrmJobrequest');
        Route::post('list','Api\crmController@getListCrmItemreq');
        Route::post('detail','Api\crmController@getDataCrmItmreqDetail');
        Route::get('void/{id}','Api\crmController@voidCrmItmreq');
        Route::get('approve/{id}','Api\crmController@approveCrmItmreq');
        Route::get('resend-api/{id}','Api\crmController@recallApiCrmItmreq');
        Route::get('api-json/{id}','Api\crmController@setJsonApiCrmItmreq');
    });
});

//API Admin
Route::prefix('admin')->group( function () {
    Route::prefix('exception')->group( function(){
        Route::get('list','Api\exceptionController@getListLogException');
    });
    //IT
    Route::prefix('it')->group( function () {
        //Dashboard
        Route::prefix('dashboard')->group( function(){
            Route::prefix('count')->group( function(){
                Route::get('ticket-not-done','Api\adminDashboardController@getCountTicketNotDone');
                Route::get('jobreq-not-done','Api\adminDashboardController@getCountJobreqNotDone');
                Route::get('itemreq-not-done','Api\adminDashboardController@getCountItemreqNotDone');
                Route::get('number-employee','Api\adminDashboardController@getCountNumberOfEmployees');
            });
            Route::prefix('graph')->group( function(){
                Route::get('asset-all-status','Api\adminDashboardController@getDataGraphAssetStatus');
                Route::get('ticket-all-status','Api\adminDashboardController@getDataGraphTicketStatus');
                Route::get('jobreq-all-status','Api\adminDashboardController@getDataGraphJobreqStatus');
                Route::get('itmreq-all-status','Api\adminDashboardController@getDataGraphItemreqStatus');
                Route::get('grow-asset-ticket','Api\adminDashboardController@getDataGrowTicketAndAsset');
            });
            Route::get('asset-by-type','Api\adminDashboardController@getDataAssetType');
            Route::get('software-license','Api\adminDashboardController@getDataCountSoftwareLicense');
            Route::get('asset-issue','Api\adminDashboardController@getDataSummaryAssetAndIssue');
        });
        //management
        Route::get('management/aljobreq','Api\itAdminController@getJobreqAlert');
        //Employee
        Route::resource('employee','Api\itEmployeeController');
        Route::post('employee/search','Api\itEmployeeController@getEmployees');
        Route::post('employee/searchhandle','Api\itEmployeeController@searchEmployeesAsstHandle');
        Route::get('employee/assethardware/{id}','Api\itEmployeeController@searchAssetHardware');
        Route::get('employee/search/fixed/{id}','Api\itEmployeeController@searchEmployeeFixedID');
        Route::get('employee/software-license/{id}','Api\itSoftwareLicenseController@getLicenseUserHandle');
        //Department
        Route::resource('department','Api\itDepartmentController');
        //Assettype
        Route::resource('assettype','Api\itHardwareTypeController');
        //Location
        Route::resource('location','Api\itLocationController');
        //Asset
        Route::resource('asset','Api\itHardwareController');
        Route::post('asset/serchkeyword','Api\itHardwareController@searchAssetByKeyword');
        Route::post('asset/upload','Api\itHardwareController@uploadAssetFile');
        Route::post('asset/removefile','Api\itHardwareController@deleteAssetFile');
        Route::get('asset/file/{id}','Api\itHardwareController@searchAssetFile');
        Route::get('asset/assethandle/{id}','Api\itHardwareController@searchAssetHandel');
        Route::post('asset/paper','Api\itHardwareController@searchAssetPaper');
        //Software License Control
        Route::prefix('softwarelicense')->group( function () {
            Route::post('save','Api\itSoftwareLicenseController@addSoftwareLicense');
            Route::post('update','Api\itSoftwareLicenseController@updateSoftwareLicense');
            Route::get('delete/{id}','Api\itSoftwareLicenseController@deleteSoftwareLicense');
            Route::post('assetsw','Api\itSoftwareLicenseController@getAssetSoftware');
            Route::get('list/{id}','Api\itSoftwareLicenseController@getSoftwareLicense');
            Route::get('handle/list/{id}','Api\itSoftwareLicenseController@getSoftwareLicenseHandle');
            Route::post('handle/save','Api\itSoftwareLicenseController@saveSoftwareLicenseHandle');
            Route::post('handle/update','Api\itSoftwareLicenseController@updateSoftwareLicenseHandle');
            Route::get('handle/delete/{id}','Api\itSoftwareLicenseController@deleteSoftwareLicenseHandle');
        });
        //Asset Handle
        Route::resource('assethandle','Api\itAssetHardwareController');
        Route::post('assethandle/paper','Api\itAssetHardwareController@getAssetHandlePaper');
        Route::get('assethandle/paper/handle/{id}','Api\itAssetHardwareController@dataPaperHandle');
        Route::get('assethandle/paper/borrow/{id}','Api\itAssetHardwareController@dataPaperBorrow');
        Route::post('assethandle/create/number/handle','Api\itAssetHardwareController@createAssethandleNumber');
        Route::post('assethandle/create/number/borrow','Api\itAssetHardwareController@createAssetBorrowNumber');
        Route::post('assethandle/upload','Api\itAssetHardwareController@uploadHandleFile');
        Route::post('assethandle/file','Api\itAssetHardwareController@getAssetHandleFile');
        Route::post('assethandle/removefile','Api\itAssetHardwareController@deleteHandleFile');
        //Job Request
        Route::resource('jobrequest','Api\itJobRequestController');
        Route::post('jobrequest/list','Api\itJobRequestController@getJobrequestPagin');
        Route::get('jobrequest/detail/{id}','Api\itJobRequestController@getJobrequestDetail');
        Route::post('jobrequest/udstatus','Api\itJobRequestController@updateJobreqStauts');
        Route::get('jobrequest/file/{id}','Api\itJobRequestController@getJobreqFile');
        Route::post('jobrequest/file/upload','Api\itJobRequestController@uploadJobreqFile');
        Route::post('jobrequest/file/remove','Api\itJobRequestController@removeJobreqFile');
        Route::post('jobrequest/update/remark','Api\itJobRequestController@updateJobreqRemark');
        Route::post('jobrequest/list/non-upload-file','Api\itJobRequestController@getJobrequestNonUpFile');
        //Item Request
        Route::resource('itemrequest','Api\itItemRequestController');
        Route::post('itemrequest/list','Api\itItemRequestController@getAdminListItmreq');
        Route::post('itemrequest/detail','Api\itItemRequestController@getItmreqDetail');
        Route::post('itemrequest/udstatus','Api\itItemRequestController@updateStsItmReq');
        Route::get('itemrequest/file/{id}','Api\itItemRequestController@getItemreqFile');
        Route::post('itemrequest/file/upload','Api\itItemRequestController@uploadItemreqFile');
        Route::post('itemrequest/file/remove','Api\itItemRequestController@removeItemreqFile');
        Route::post('itemrequest/list/non-upload-file','Api\itItemRequestController@getItemRequestNonUpFile');
        //Ticket
        Route::prefix('ticket')->group( function(){
            Route::get('subject/all','Api\itTicketController@getAllTicketSubject');
            Route::post('subject/save','Api\itTicketController@saveTicketSubject');
            Route::post('subject/update/active','Api\itTicketController@updateStatusTicketSubject');
            Route::get('subject/delete/{id}','Api\itTicketController@deleteTicketSubject');
            Route::get('subject','Api\itTicketController@getAllTicketSubjectActive');
            Route::post('list','Api\itTicketController@getTicketList');
            Route::get('detail/{id}','Api\itTicketController@setTicketDetailJson');
            Route::post('approve','Api\itTicketController@setTicketLevel');
            Route::post('assign','Api\itTicketController@setTicketAssign');
            Route::post('endjob','Api\itTicketController@setTicketEndJob');
            Route::post('subject/change','Api\itTicketController@setChangeSubjectTicket');
            Route::post('survey','Api\itSurveyController@getAdminListTicketSurvey');
            Route::get('survey/detail/{id}','Api\itSurveyController@setTicketSurveyDetailJson');
            Route::prefix('message')->group( function(){
                Route::get('list/{id}','Api\itTicketController@getTicketMessage');
                Route::post('save','Api\itTicketController@setTicketMessage');
                Route::get('remove/{id}','Api\itTicketController@removeTicketMessage');
            });
        });
        //Role
        Route::prefix('role')->group( function(){
            Route::prefix('menus')->group( function(){
                Route::get('list','Api\manageRoleController@getListMenu');
                Route::post('save','Api\manageRoleController@saveNewMenu');
                Route::post('update','Api\manageRoleController@updateNewMenu');
                Route::get('delete/{id}','Api\manageRoleController@deleteMenu');
                Route::prefix('role')->group( function(){
                    Route::get('list/{id}','Api\manageRoleController@getListMenuRole');
                    Route::post('save','Api\manageRoleController@saveMenuRole');
                    Route::post('remove','Api\manageRoleController@removeMenuRole');
                });
            });
            Route::prefix('pages')->group( function(){
                Route::get('list','Api\manageRoleController@getListPage');
                Route::post('save','Api\manageRoleController@saveNewPage');
                Route::post('update','Api\manageRoleController@updatePage');
                Route::get('delete/{id}','Api\manageRoleController@deletePage');
                Route::prefix('role')->group( function(){
                    Route::get('list/{id}','Api\manageRoleController@getListPageRole');
                    Route::post('save','Api\manageRoleController@savePageRole');
                    Route::post('remove','Api\manageRoleController@removePageRole');
                    Route::post('update','Api\manageRoleController@updatePageRole');
                });
            });
            Route::prefix('user')->group( function(){
                Route::get('/{id}','Api\manageRoleController@getUserData');
                Route::post('takeaction','Api\manageRoleController@takeActionUser');
                Route::post('reset-password','Api\manageRoleController@resetPassword');
            });
        });
        //User
        Route::prefix('user')->group( function(){
            Route::get('resetpassword/{id}','Api\UserController@resetPassword');
            Route::get('list/admin-it','Api\UserController@getUserAdminMIS');
        });
        //Report
        Route::prefix('report')->group( function(){
            Route::post('summary-support','Api\itReportController@getSummarySupportJson');
        });
        //IT Memo
        Route::prefix('it-memo')->group( function(){
            Route::post('list','Api\itMemoController@getMemoAdminList');
            Route::get('detail/{id}','Api\itMemoController@adminGetMemoDetail');
            Route::post('approve','Api\itMemoController@approveMemo');
            Route::post('change-status','Api\itMemoController@changStatusMemo');
            Route::post('non-upload-file','Api\itMemoController@getMemoNonUpFile');
            Route::prefix('files')->group( function(){
                Route::get('list/{id}','Api\itMemoController@getMemoFile');
                Route::post('upload','Api\itMemoController@uploadMemoFile');
                Route::post('remove','Api\itMemoController@removeMemoFile');
            });
        });
        //External Problem
        Route::prefix('external-pb')->group( function(){
            Route::post('list','Api\itExternalProblem@getListExternalProblem');
            Route::post('save','Api\itExternalProblem@saveExternalProblem');
            Route::post('update','Api\itExternalProblem@updateExternalProblem');
            Route::get('delete/{id}','Api\itExternalProblem@removeExternalProblem');
            Route::prefix('system-status')->group( function(){
                Route::post('detail','Api\itExternalProblem@searchSystemStatus');
                Route::post('save','Api\itExternalProblem@saveSystemStatus');
                Route::post('update','Api\itExternalProblem@updateSystemStatus');
            });
        });
    });

    Route::prefix('role')->group( function(){
        Route::prefix('show')->group( function() {
            Route::post('employee','Security\RoleController@getRoleMenuUser');
        });
    });
});

//Api Report
Route::prefix('report')->group( function(){
    //Report IT
    Route::prefix('it')->group( function(){
        Route::Post('license/export','Api\itReportController@exportReportLicenseHandle');
        Route::post('asset/export','Api\itReportController@exportReportAssetHandle');
        Route::post('nonup-paper/export','Api\itReportController@exportListNonupPaper');
    });
});

//Api User
Route::prefix('user')->group( function(){
    Route::prefix('dashboard')->group( function(){
        Route::prefix('charts')->group( function(){
            Route::get('pie-all','Api\userDashboardController@getPieAllData');
            Route::get('bar-all-per-month','Api\userDashboardController@getBarAllDataPerMonth');
        });
        Route::get('list-jobreq','Api\userDashboardController@getDataJobreqList');
        Route::get('list-itemreq','Api\userDashboardController@getDataItemreqList');
        Route::get('list-ticket','Api\userDashboardController@getDataTicketList');
    });
    //Index
    Route::prefix('index')->group( function(){
        Route::get('jobreq','Api\HomeController@userIndexGetJobreq');
    });
    //Job Request
    Route::prefix('jobreq')->group( function(){
        Route::post('history','Api\itJobRequestController@getJobRequestUserHistory');
        Route::post('history/search','Api\itJobRequestController@searchJobRequestUserHistory');
        Route::post('udstatus','Api\itJobRequestController@updateJobreqStauts');
        Route::get('detail/{id}','Api\itJobRequestController@getJobrequestDetail');
        Route::prefix('survey')->group( function(){
            Route::post('list','Api\itJobRequestController@getJobRequestSurveyList');
            Route::post('detail','Api\itSurveyController@getJobreqSurveyDetail');
            Route::post('save','Api\itSurveyController@saveJobreqSurvey');
        });
    });
    //Item Request
    Route::prefix('itmreq')->group( function(){
        Route::post('history/list','Api\itItemRequestController@getUserListItmreq');
        Route::post('udstatus','Api\itItemRequestController@updateStsItmReq');
        Route::prefix('approve')->group( function(){
            Route::post('list','Api\itItemRequestController@getAccApproveListItmreq');
        });
    });
    //Ticket
    Route::prefix('ticket')->group( function(){
        Route::get('subject/all','Api\itTicketController@getAllTicketSubjectActive');
        Route::post('create','Api\itTicketController@generateTicket');
        Route::post('history','Api\itTicketController@getTicketUserHistory');
        Route::get('history/detail/{id}','Api\itTicketController@setTicketDetailJson');
        Route::post('void','Api\itTicketController@updateStsTicket');
        Route::post('survey','Api\itSurveyController@getAdminListTicketSurvey');
        Route::get('survey/didnothing','Api\itTicketController@countUserTicketNonSurvey');
        Route::get('survey/detail/{id}','Api\itSurveyController@setTicketSurveyDetailJson');
        Route::post('survey/assessment','Api\itSurveyController@saveAssessmentSurvey');
        Route::prefix('message')->group( function(){
            Route::get('list/{id}','Api\itTicketController@getUserTicketMessage');
        });
    });
    //Memo
    Route::prefix('memo')->group( function(){
        Route::post('history','Api\itMemoController@getMemoUserHistory');
        Route::get('detail/{id}','Api\itMemoController@userGetMemoDetailToPage');
        Route::post('create','Api\itMemoController@saveMemo');
        Route::post('void','Api\itMemoController@voidMemo');
    });
});

Route::prefix('ad')->group( function(){
    Route::prefix('items')->group( function(){
        Route::get('list','Api\administrativeController@getAllItems');
        Route::get('active','Api\administrativeController@getActiveItems');
        Route::post('create','Api\administrativeController@saveItems');
        Route::post('update','Api\administrativeController@updateItems');
        Route::get('remvoe/{id}','Api\administrativeController@deleteItems');
    });
    Route::prefix('equip-req')->group( function(){
        Route::post('create','Api\administrativeController@saveEquipReq');
        Route::post('history','Api\administrativeController@getListEquipReq');
        Route::get('detail/{id}','Api\administrativeController@setJsonEquipReqDetail');
        Route::post('approve','Api\administrativeController@approveEquipReq');
        Route::prefix('update')->group( function(){
            Route::post('status','Api\administrativeController@updateStatusEquipReq');
            Route::post('detail','Api\administrativeController@updateEquipReqDetail');
        });
        Route::prefix('files')->group( function(){
            Route::get('list/{id}','Api\administrativeController@getEquipReqFiles');
            Route::post('upload','Api\administrativeController@uploadEquipReqFiles');
            Route::post('remove','Api\administrativeController@removeEquipReqFiles');
        });
        Route::prefix('remark')->group( function(){
            Route::post('save','Api\administrativeController@saveRemarkEquipReq');
        });
    });
    Route::prefix('job-req')->group( function(){
        Route::post('create','Api\admJobRequestController@store');
        Route::get('detail/{id}','Api\admJobRequestController@getJobrequestDetail');
        Route::prefix('history')->group( function(){
            Route::post('list','Api\admJobRequestController@getJobRequestUserHistory');
            Route::post('pagin','Api\admJobRequestController@getJobrequestPagin');
            Route::post('search','Api\admJobRequestController@searchJobRequestUserHistory');
        });
        Route::prefix('update')->group( function(){
           Route::post('status','Api\admJobRequestController@updateJobreqStauts');
           Route::post('remark','Api\admJobRequestController@updateJobreqRemark');
        });
        Route::prefix('files')->group( function(){
           Route::get('list/{id}','Api\admJobRequestController@getJobreqFile');
           Route::post('upload','Api\admJobRequestController@uploadJobreqFile');
           Route::post('remove','Api\admJobRequestController@removeJobreqFile');
        });
    });
    Route::prefix('report')->group( function(){
        Route::post('summary-eqreq','Api\admReportController@summary_report_eqreq_json');
        Route::post('summary-eqreq/export','Api\admReportController@export_summary_report_eqreq');
        Route::post('summary-budget-eqreq','Api\admReportController@summary_report_budget_eqreq_json');
        Route::post('summary-budget-eqreq/export','Api\admReportController@export_summary_report_budget_eqreq');
        Route::post('summary-budget-eqreq-dept','Api\admReportController@summary_report_budget_eqreq_dept_json');
        Route::get('employees','Api\admReportController@export_employees_list');
    });
});
