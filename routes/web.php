	<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
//  Route::get('/', function () {
//    return view('welcome');
//  });

 Route::get('/', function () {
   return redirect('/login');
 });
// Auth::routes();


// Authentication Routes...
$this->get('login', 'Auth\LoginController@showLoginForm')->name('login');
$this->post('login', 'Auth\LoginController@login');
$this->post('logout', 'Auth\LoginController@logout')->name('logout');
Route::get('logout', function(){
  return redirect('/');
});

// Registration Routes...
// $this->get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
// $this->post('register', 'Auth\RegisterController@register');

// Password Reset Routes...
$this->get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
$this->post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
$this->get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
$this->post('password/reset', 'Auth\ResetPasswordController@reset');


// Authenticated accessible routes
Route::group(['middleware' => 'auth'], function (){
  Route::get('/sampleRoute', 'DrdrController@emailScheduling');

  Route::get('/home', 'HomeController@index')->name('home');
  Route::get('/ccir-page', 'HomeController@ccir_index')->name('ccir');
  Route::get('/companies-page', 'HomeController@companies_index')->name('companies');
  Route::get('/ddr-page', 'HomeController@ddr_index')->name('ddr');
  Route::get('/departments-page', 'HomeController@departments_index')->name('departments');
  Route::get('/drdr-page', 'HomeController@drdr_index')->name('drdr');
  Route::get('/ncn-page', 'HomeController@ncn_index')->name('ncn');
  Route::get('/permissions_page', 'HomeController@permissions_index')->name('permissions');
  Route::get('/roles-page', 'HomeController@roles_index')->name('roles');
  Route::get('/user-page', 'HomeController@users_index')->name('users');

  // Download files upload
  Route::get('/download-attachment/{fileId}', 'DrdrController@downloadAttachment');

  // DRDR routes
  // Show add form of DRDR 
  Route::get('/add-drdr', 'DrdrController@create');
  // Add new DRDR by requester
  Route::post('/drdr', 'DrdrController@store');
  //Show edit form of DRDR
  Route::get('/edit-drdr/{id}', 'DrdrController@edit');
  // Update  specific DRDR By requester
  Route::post('/drdr/{drdr}', 'DrdrController@update');
  // Delete DRDR by requester
  Route::delete('/drdr/{id}', 'DrdrController@destroy');
  // Show specific DRDR by requester
  Route::get('/drdr/{id}', 'DrdrController@show');
  // Fetch Submitted DRDR by user
  Route::get('/drdr-submitted', 'DrdrController@submitted');
  // Viewing of DRDR
  Route::get('/drdr-view/{drdr_id}', 'DrdrController@showDetailsDrdr');
  // Get the specified drdr by id
  Route::get('/drdr-data/{id}', 'DrdrController@data');
  //Get reviewer base in the company
  Route::get('/getReviewer/{id}', 'DrdrController@getCompanyReviewers');
  // Generate drdrs submitted by date
  Route::post('/drdrs-submitted-generate', 'DrdrController@generateSubmitted');
  // Generate drdrs pending review by date
  Route::post('/drdrs-pending-reviews-generate', 'DrdrController@generatePendingReview');
  // Generate drdrs reviewed by date
  Route::post('/drdrs-reviewed-generate', 'DrdrController@generateReviewed');
  // Generate drdrs pending approval by date
  Route::post('/drdrs-pending-approval-generate', 'DrdrController@generatePendingApproval');
  // Generate drdrs approvaed by date
  Route::post('/drdrs-approved-generate', 'DrdrController@generateApproved');

  // DDR routes
  // Show Add form ddr
  Route::get('/add-ddr', 'DdrController@create');
  // Storing of ddr
  Route::post('/ddr', 'DdrController@store');
  // Show specific DRDR by requester
  Route::get('/ddr/{id}', 'DdrController@show');
  // Update specific DDR
  Route::patch('/ddr/{ddr}', 'DdrController@update');
  // Delete DDR by requester
  Route::delete('/ddr/{id}', 'DdrController@destroy');
  // Delete DDRFORMSLISTS by requester
  Route::delete('/ddrformslists/{id}', 'DdrController@deleteDdrList');
  //Show edit form of DRDR
  Route::get('/edit-ddr/{id}', 'DdrController@edit');
  // Fetch Submitted DDR by user
  Route::get('/ddrs-submitted', 'DdrController@submitted');
  // Viewing of DDR
  Route::get('/ddr-view/{ddr_id}', 'DdrController@showDetailsDdr');
  // Get the specified ddr by id
  Route::get('/ddr-data/{id}', 'DdrController@data');
  // Generate ddrs submitted by date
  Route::post('/ddrs-submitted-generate', 'DdrController@generateSubmitted');
  // Generate drs pending approval by date
  Route::post('/ddrs-pending-approval-generate', 'DdrController@generatePendingApproval');
  // Generate ddrs approvaed by date
  Route::post('/ddrs-approved-generate', 'DdrController@generateApproved');

  // CCIR ROUTES
  // Fetch Submitted CCIR by user
  Route::get('/ccirs-submitted', 'CcirController@submitted');
  // Show Add form CCIR
  Route::get('/add-ccir', 'CcirController@create');
  // Storing of ccir
  Route::post('/ccir', 'CcirController@store');
  // Delete CCIR by requester
  Route::delete('/ccir/{id}', 'CcirController@destroy');
  // Viewing of CCIR
  Route::get('/ccir-view/{ccir_id}', 'CcirController@showDetailsCcir');
  // Get the specified ccir by id
  Route::get('/ccir-data/{id}', 'CcirController@data');
  // Generate ccirs submitted by date
  Route::post('/ccirs-submitted-generate', 'CcirController@generateSubmitted');

  // NCN ROUTES
  // Fetch Submitted NCN by user
  Route::get('/ncns-submitted', 'NcnController@submitted');
  // Show Add form CCIR
  Route::get('/add-ncn', 'NcnController@create');
  // Storing of ncn
  Route::post('/ncn', 'NcnController@store');
  // Delete NCN by requester
  Route::delete('/ncn/{id}', 'NcnController@destroy');
  // Viewing of NCN
  Route::get('/ncn-view/{ncn_id}', 'NcnController@showDetailsNcn');
  // Get the specified ncn by id
  Route::get('/ncn-data/{id}', 'NcnController@data');
  // Get Approvers base in the company and department
  Route::get('/getNcnApprovers/{company}/{department}', 'NcnController@getNcnApprovers');
  // Generate ncns submitted by date
  Route::post('/ncns-submitted-generate', 'NcnController@generateSubmitted');
  // Generate ncns pending approval by date
  Route::post('/ncns-pending-approval-generate', 'NcnController@generatePendingApproval');
  // Generate ncns approvaed by date
  Route::post('/ncns-approved-generate', 'NcnController@generateApproved');
  // Generate ncns approvaed by date
  Route::post('/ncns-notified-generate', 'NcnController@generateNotified');


  // COMPANY ROUTES 
  Route::get('/companies', 'CompanyController@index');
  Route::get('/companyLocation', 'CompanyController@companyLocation');

  // DEPARTMENT ROUTES
  Route::get('/departments', 'DepartmentController@index');

  // CHANGE PASSWORD ROUTES

  // Return change password page
  Route::get('/change-password', 'HomeController@changePass_index')->name('user.changePassword');
  // Process for changing of password
  Route::post('/user/change-password', 'UserController@changePassword');

});

// Return page to view details of drdr
Route::get('/admin/drdr-details/{id}', 'DrdrController@drdrDetails');
// Return page to view details of drdr
Route::get('/admin/ddr-details/{id}', 'DdrController@ddrDetails');
// Return page to view details of drdr
Route::get('/admin/ncn-details/{id}', 'NcnController@ncnDetails');
// Return page to view details of ccir
Route::get('/admin/ccir-details/{id}', 'CcirController@ccirDetails');

// Accessible route only by admin 
Route::group(['middleware' => ['auth', 'role:administrator|mr|notified']], function(){ 

  // User routes
  // Show add user form
  Route::get('/add-user', 'UserController@create');
  // Get all users
  Route::get('/users', 'UserController@index');
  Route::get('/user/{id}', 'UserController@show');
  Route::post('/user', 'UserController@store');
  Route::patch('/user/{user}', 'UserController@update');
  Route::delete('/user/{id}', 'UserController@destroy');
  Route::get('/getApprover/{id}', 'UserController@getCompanyReviewers');
  Route::get('/auth-user','UserController@authUser');
  Route::get('/edit-user/{id}','UserController@edit');

  // Company routes
  Route::get('/company/{id}', 'CompanyController@show');
  Route::post('/company', 'CompanyController@store');
  Route::patch('/company/{company}', 'CompanyController@update');
  Route::delete('/company/{id}', 'CompanyController@destroy');

  // Department routes
  Route::get('/department/{id}', 'DepartmentController@show');
  Route::post('/department', 'DepartmentController@store');
  Route::patch('/department/{department}', 'DepartmentController@update');
  Route::delete('/department/{id}', 'DepartmentController@destroy');


  // Role routes
  Route::get('/roles', 'RoleController@index');
  Route::get('/role/{id}', 'RoleController@show');
  Route::post('/role', 'RoleController@store');
  Route::patch('/role/{role}', 'RoleController@update');
  Route::delete('/role/{id}', 'RoleController@destroy');

  // Permission routes
  Route::get('/permissions', 'PermissionController@index');
  Route::get('/permission/{id}', 'PermissionController@show');
  Route::post('/permission', 'PermissionController@store');
  Route::patch('/permission/{permission}', 'PermissionController@update');
  Route::delete('/permission/{id}', 'PermissionController@destroy');

  // DRDR routes
  // Count all submitted drdr 
  Route::get('/admin/drdr-count', 'DrdrController@countDrdr');
  //Return page of drdr
  Route::get('/admin/drdrs', 'DrdrController@drdrAdminPage')->name('admin.drdrs');
  // Ajax call to return all submitted Drdr
  Route::get('/admin/drdrs-all', 'DrdrController@getAllDrdrs');
  // Generate pdf file for drdrP
  Route::get('/admin/drdr-pdf/{id}', 'DrdrController@drdrPdf');
  // Return page to view details of drdr
  Route::get('/admin/drdr-verify/{id}', 'DrdrController@drdrDistributedPage');
  // Mark DRDR as distributed
  Route::post('/admin/drdr-distributed', 'DrdrController@distributed');


  // DDR routes
  // Count all submitted ddr 
  Route::get('/admin/ddr-count', 'DdrController@countDdr');
  //Return page of ddr
  Route::get('/admin/ddrs', 'DdrController@ddrAdminPage')->name('admin.ddrs');
  // Ajax call to return all submitted Ddr
  Route::get('/admin/ddrs-all', 'DdrController@getAllDdrs');
  // Generate pdf file for ddr
  Route::get('/admin/ddr-pdf/{id}', 'DdrController@ddrPdf');
  // Mark DDR as distributed
  Route::post('/admin/ddr-distributed', 'DdrController@distributed');
  // Update DDR by mr or admin
  Route::patch('/admin/ddr/{ddr}', 'DdrController@adminUpdate');
  
  // NCN routes
  // Count all submitted ncn 
  Route::get('/admin/ncn-count', 'NcnController@countNcn');
  //Return page of ncn
  Route::get('/admin/ncns', 'NcnController@ncnAdminPage')->name('admin.ncns');
  // Ajax call to return all submitted ncn
  Route::get('/admin/ncns-all', 'NcnController@getAllNcns');
  // Generate pdf file for ncn
  Route::get('/admin/ncn-pdf/{id}', 'NcnController@ncnPdf');

  // CCIR routes
  // Count all submitted ccir 
  Route::get('/admin/ccir-count', 'CcirController@countCcir');
  //Return page of ccir
  Route::get('/admin/ccirs', 'CcirController@ccirAdminPage')->name('admin.ccirs');
  // Ajax call to return all submitted Ccir
  Route::get('/admin/ccirs-all', 'CcirController@getAllCcirs');
  // Generate pdf file for ccir
  Route::get('/admin/ccir-pdf/{id}', 'CcirController@ccirPdf');
  // Validate ccir
  Route::post('/admin/ccir-validate', 'CcirController@validateCCir');
});
 
Route::group(['middleware' => ['auth', 'role:administrator|mr|reviewer|approver']], function () {

  // DRDR ROUTES
  Route::get('/drdrs-pending-reviews', 'DrdrController@pendingReviews');
  Route::get('/drdrs-reviewed-forms', 'DrdrController@reviewedForms');
  Route::get('/drdrs-pending-approvals', 'DrdrController@pendingApprovals');
  Route::get('/drdrs-approved-forms', 'DrdrController@approvedForms');
  //Show review page of drdr
  Route::get('/drdr-review/{id}', 'DrdrController@show');
  Route::get('/drdr-approve/{id}', 'DrdrController@showApprovedForm');
  // Approve specific ddr
  Route::post('/drdr-approved', 'DrdrController@approved');
  Route::post('/drdr-reviewed', 'DrdrController@reviewed');
  // Get specific approver per company
  Route::get('/drdr-approver/{company_id}', 'DrdrController@approver');
  // Get uploaded files of requester for drdr
  Route::get('/drdr-requester-attachments/{drdrId}/{requesterId}', 'DrdrController@getUploadedFilesRequester');
  // Get uploaded files of reviewer for drdr
  Route::get('/drdr-reviewer-attachments/{drdrId}/{reviewerId}', 'DrdrController@getUploadedFilesReviewer');
  // Get uploaded files of approver for drdr
  Route::get('/drdr-approver-attachments/{drdrId}/{approverId}', 'DrdrController@getUploadedFilesApprover');
  // Generate drdrs by date
  Route::post('/drdrs-generate', 'DrdrController@generate');

  // DDR routes
  Route::get('/ddrs', 'DdrController@index');
  Route::get('/ddrs-approved-forms', 'DdrController@approvedForms');
  //Show approve page of ddr
  Route::get('/ddr-approve/{id}', 'DdrController@show');
  // Approve specific ddr
  Route::post('/ddr-approved', 'DdrController@approved');
  // Generate ddrs by date
  Route::post('/ddrs-generate', 'DdrController@generate');

  // CCIR routes
  Route::get('/ccirs', 'CcirController@index');
  Route::get('/ccir/{id}', 'CcirController@show');
  Route::patch('/ccir/{ccir}', 'CcirController@update');
  Route::get('/getApprover/{id}', 'CcirController@getCompanyApprovers');
  Route::get('/ccirs-approved-forms', 'CcirController@approvedForms');
  // Get uploaded files of requester for ccir
  Route::get('/ccir-requester-attachments/{ccirId}/{requesterId}', 'CcirController@getUploadedFilesRequester');
  // Generate ccir by date
  Route::post('/ccirs-generate', 'CcirController@generate');

  
  // NCN routes
  Route::get('/ncns', 'NcnController@index');
  Route::get('/ncns-approved-forms', 'NcnController@approvedForms');
  //Show approve page of ddr
  Route::get('/ncn-approve/{id}', 'NcnController@show');
  // Approve specific ncn
  Route::post('/ncn-approved', 'NcnController@approved');
  Route::patch('/ncn/{ncn}', 'NcnController@update');
  // Get uploaded files of requester for ncn
  Route::get('/ncn-requester-attachments/{ncnId}/{requesterId}', 'NcnController@getUploadedFilesRequester');
  // Get uploaded files of approver for ncn
  Route::get('/ncn-approver-attachments/{ncnId}/{approverId}', 'NcnController@getUploadedFilesApprover');
  // Get uploaded files of notified for ncn
  Route::get('/ncn-notified-attachments/{ncnId}/{notifiedId}', 'NcnController@getUploadedFilesNotified');
  // Generate ncns by date
  Route::post('/ncns-generate', 'NcnController@generate');
  // Get notified personnel for NCN 
  Route::get('/ncns-notified/{companyId}/{departmentId}', 'NcnController@getNotifiedPersonnel');
});

// Accessible routes for notified person
Route::group(['middleware' => ['auth', 'role:notified']], function () {

  Route::get('/notified-page', 'NcnController@notifiedIndex')->name('notified');
  Route::get('/ncns-notified', 'NcnController@notifiedIndexData');
  Route::post('/notified/ncn-notified', 'NcnController@validateNcn');

});