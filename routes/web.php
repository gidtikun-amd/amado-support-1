<?php
//Pond

// Login
Route::get('/','Security\LoginController@index');
Route::get('/login','Security\LoginController@index');
Route::get('change-password','UserController@changePassword');
Route::get('logout','Security\LoginController@logout')->name('logout');

//Bill
//Page

Route::prefix('admin')->group( function () {
    Route::resource('/','itAdminController');
    Route::resource('employee','itEmployeeController');
    Route::resource('department','itDepartmentController');
    Route::resource('type-asset','itHardwareTypeController');
    Route::resource('location','itLocationController');
    Route::resource('asset','itHardwareController');
    Route::prefix('sofware-license')->group( function () {
        Route::get('/','itSoftwareLicenseController@index');
        Route::get('/{id}','itSoftwareLicenseController@edit');
    });
    Route::resource('assethandle','itAssetHardwareController');
    Route::get('jobrequest','itJobRequestController@index');
    Route::get('jobrequest/dont-upload-files','itJobRequestController@recheckFileUploadJobReqView');
    Route::get('jobrequest/survey','itJobRequestController@adminSurveyJobReqView');
    Route::get('itemrequest','itItemRequestController@index');
    Route::get('itemrequest/dont-upload-files','itItemRequestController@recheckFileUploadItemReqView');
    Route::prefix('ticket')->group( function (){
        Route::get('list','itTicketController@index');
        Route::get('survey','itSurveyController@adminSurveyTicketListView');
        Route::get('subject','itTicketController@adminSubjectTicketView');
    });
    Route::prefix('role')->group( function () {
        Route::get('management','manageRoleController@index');
        Route::get('pages-menus','manageRoleController@PagesAndMenu');
        Route::get('user','manageRoleController@userIdeneityAndRole');
    });
    Route::prefix('report')->group( function () {
        Route::get('asset','ReportController@viewReportAdminAssetHandel');        
        Route::get('license','ReportController@viewReportAdminLicenseHandel');
        Route::post('asset/export','ReportController@exportReportAssetHandle');
        Route::get('problemticket','ReportController@viewReportAdminProblemTicket');
        Route::get('transfer-asset','ReportController@viewReportAdminProblemTransferAsset');
        Route::get('summary-support','ReportController@viewReportAdminSummarySupport');
        Route::get('exception','ReportController@viewReportItException');
        Route::get('files-papers','ReportController@viewReportAdmminPaperFiles');
    });
    Route::prefix('paper')->group( function () {
        Route::get('asset','itHardwareController@paper');
    });
    Route::prefix('it-memo')->group( function () {
        Route::get('list','itMemoController@adminHostoryMemoView');
        Route::get('dont-upload-files','itMemoController@recheckFileUploadMemoView');
    });
    Route::prefix('it-external-problem')->group( function () {
        Route::get('main','itExternalProblem@index');
        Route::get('system-status','itExternalProblem@getSystemStatusView');
    });
    Route::prefix('crm')->group( function () {
        Route::get('item-request','itItemRequestController@adminHistoryItemRequestCRMView');
    });
});

Route::prefix('administrative')->group( function () {
    Route::prefix('items')->group( function () {
        Route::get('admin/management','administrativeController@adminManagementItems');
    });
    Route::prefix('equipment-req')->group( function () {
        Route::get('user/create','administrativeController@userCreateEquReq');
        Route::get('user/history','administrativeController@userHistoryEquReq');
        Route::get('admin/management','administrativeController@adminManagementEquReq');
    });
    Route::prefix('job-req')->group( function () {
        Route::get('admin/management','administrativeController@adminManagementJobReq');
        Route::get('user/create','administrativeController@userCreateJobReq');
        Route::get('user/history','administrativeController@userHistoryJobReq');
    });
    Route::prefix('report')->group( function(){
        Route::get('summary-eqreq','administrativeController@reportSummaryEqreq');
        Route::get('summary-budget-eqreq','administrativeController@reportSummaryBudgetEqreq');
    });
});

Route::prefix('user')->group( function () {
    Route::get('/','HomeController@index');
    Route::get('send_jobrequest','itJobRequestController@userCreateJobReqView');
    Route::get('history_jobrequest','itJobRequestController@uerHistoryJobReqView');
    Route::get('send_itemrequest','itItemRequestController@userCreateItemRequestView');
    Route::get('send_itemrequest_crm','itItemRequestController@userCreateItemRequestCRMView');
    Route::get('history_itemrequest','itItemRequestController@userHistoryItemRequestView');
    Route::get('history_itemrequest_crm','itItemRequestController@userHistoryItemRequestCRMView');
    Route::get('approve_itemrequest','itItemRequestController@accApproveItemRequestView');
    Route::prefix('it-jobrequest')->group( function () {
        Route::get('survey','itJobRequestController@uerSurveyJobReqView');
    });
    Route::prefix('ticket')->group( function () {
        Route::get('create','itTicketController@userCreateTicketView');
        Route::get('history','itTicketController@userHistoryTicketView');
        Route::get('survey','itSurveyController@userSurveyTicketListView');
    });
    Route::prefix('memo')->group( function () {
        Route::get('create','itMemoController@userOpemMemoView');
        Route::get('history','itMemoController@userHistoryMemoView');
    });
    Route::prefix('asset')->group( function(){
        Route::get('handle','itAssetHardwareController@index');
    });
});

Route::prefix('pdf')->group( function () {
    Route::get('jobrequest/{id}','JobRequestFormController@pdf_index');
    Route::get('itemrequest/{id}','ItemRequestFormController@pdf_index');
    Route::get('memo/{id}','paperController@pdf_memo');
    Route::get('equipreq/{id}','paperController@pdf_ad_equipment_req');
    Route::prefix('asset')->group( function () {
        Route::get('handle/{id}','AssetHandleFromController@pdf_handle');
        Route::get('borrow/{id}','AssetHandleFromController@pdf_borrow');
    });
    Route::prefix('survey')->group( function () {
        Route::get('ticket/{id}','SurveyFormController@getTicketSurveyPDF');
    });
    Route::prefix('report')->group( function () {
        Route::get('ticket/{month}','ReportController@paperReportProblemTicket');
        Route::get('transfer-asset/{month}','ReportController@paperReportAssetHandle');
    });
    Route::prefix('ad')->group( function () {
       Route::get('job-req/{id}','paperController@pdf_ad_job_request');
    });
    Route::prefix('crm')->group( function () {
        Route::get('itemrequest/{id}','paperController@pdf_crm_item_request');
    });
});

Route::prefix('word')->group( function () {
    Route::prefix('report')->group( function () {
        Route::get('ticket','winWordController@getReportTicket');
        Route::get('asset-handle','winWordController@getReportAssetHandle');
    });
});
