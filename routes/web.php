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

  Route::get('/home', 'HomeController@index')->name('home');
  Route::get('/ccir_page', 'HomeController@ccir_index')->name('ccir');
  Route::get('/companies_page', 'HomeController@companies_index')->name('companies');
  Route::get('/ddr-page', 'HomeController@ddr_index')->name('ddr');
  Route::get('/departments_page', 'HomeController@departments_index')->name('departments');
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
  // Get the specified drdr by id
  Route::get('/ddr-data/{id}', 'DdrController@data');

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


  // COMPANY ROUTES 
  Route::get('/companies', 'CompanyController@index');

  // DEPARTMENT ROUTES
  Route::get('/departments', 'DepartmentController@index');
});


// Accessible route only by admin 
Route::group(['middleware' => ['auth', 'role:administrator|mr']], function(){ 

  // User routes
  Route::get('/add-user', 'UserController@create');
  Route::get('/users', 'UserController@index');
  Route::get('/user/{id}', 'UserController@show');
  Route::post('/user', 'UserController@store');
  Route::patch('/user/{user}', 'UserController@update');
  Route::delete('/user/{id}', 'UserController@destroy');
  Route::get('/getApprover/{id}', 'UserController@getCompanyReviewers');
  Route::get('/auth-user','UserController@authUser');

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
  Route::post('/role/{role}', 'RoleController@update');
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
  Route::get('/admin/drdrs', 'DrdrController@drdrAdminPage');
  // Ajax call to return all submitted Drdr
  Route::get('/admin/drdrs-all', 'DrdrController@getAllDrdrs');
  // Return page to view details of drdr
  Route::get('/admin/drdr-details/{id}', 'DrdrController@drdrDetails');
  // Generate pdf file for drdr
  Route::get('/admin/drdr-pdf/{id}', 'DrdrController@drdrPdf');


  // DRDR routes
  // Count all submitted ddr 
  Route::get('/admin/ddr-count', 'DdrController@countDdr');
  //Return page of ddr
  Route::get('/admin/ddrs', 'DdrController@ddrAdminPage');
  // Ajax call to return all submitted Ddr
  Route::get('/admin/ddrs-all', 'DdrController@getAllDdrs');
  // Return page to view details of drdr
  Route::get('/admin/ddr-details/{id}', 'DdrController@ddrDetails');
  // Generate pdf file for ddr
  Route::get('/admin/ddr-pdf/{id}', 'DdrController@ddrPdf');
  
  // NCN routes
  // Count all submitted ncn 
  Route::get('/admin/ncn-count', 'NcnController@countNcn');
  //Return page of ncn
  Route::get('/admin/ncns', 'NcnController@ncnAdminPage');
  // Ajax call to return all submitted ncn
  Route::get('/admin/ncns-all', 'NcnController@getAllNcns');
  // Return page to view details of drdr
  Route::get('/admin/ncn-details/{id}', 'NcnController@ncnDetails');
  // Generate pdf file for ncn
  Route::get('/admin/ncn-pdf/{id}', 'NcnController@ncnPdf');

  // CCIR routes
  // Count all submitted ccir 
  Route::get('/admin/ccir-count', 'CcirController@countCcir');
  //Return page of ccir
  Route::get('/admin/ccirs', 'CcirController@ccirAdminPage');
  // Ajax call to return all submitted Ccir
  Route::get('/admin/ccirs-all', 'CcirController@getAllCcirs');
  // Return page to view details of ccir
  Route::get('/admin/ccir-details/{id}', 'CcirController@ccirDetails');
  // Generate pdf file for ccir
  Route::get('/admin/ccir-pdf/{id}', 'CcirController@ccirPdf');
});
 
Route::group(['middleware' => ['auth', 'role:administrator|mr|reviewer|approver']], function () {

  // DRDR ROUTES
  Route::get('/drdrs-pending-reviews', 'DrdrController@pendingReviews');
  Route::get('/drdrs-reviewed-forms', 'DrdrController@reviewedForms');
  Route::get('/drdrs-pending-approvals', 'DrdrController@pendingApprovals');
  Route::get('/drdrs-approved-forms', 'DrdrController@approvedForms');
  Route::get('/drdrs-by/{category}', 'DrdrController@category');
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
  Route::get('/drdrs-generate/{startDate}/{endDate}', 'DrdrController@generate');


  // DDR routes
  Route::get('/ddrs', 'DdrController@index');
  Route::get('/ddrs-approved-forms', 'DdrController@approvedForms');
  Route::get('/ddrs-by/{category}', 'DdrController@category');
  //Show approve page of ddr
  Route::get('/ddr-approve/{id}', 'DdrController@show');
  // Approve specific ddr
  Route::post('/ddr-approved', 'DdrController@approved');
  // Generate ddrs by date
  Route::get('/ddrs-generate/{startDate}/{endDate}', 'DdrController@generate');

  // CCIR routes
  Route::get('/ccirs', 'CcirController@index');
  Route::get('/ccir/{id}', 'CcirController@show');
  Route::patch('/ccir/{ccir}', 'CcirController@update');
  Route::get('/getApprover/{id}', 'CcirController@getCompanyApprovers');
  Route::get('/ccirs-approved-forms', 'CcirController@approvedForms');
  // Get uploaded files of requester for ccir
  Route::get('/ccir-requester-attachments/{ccirId}/{requesterId}', 'CcirController@getUploadedFilesRequester');
  // Get uploaded files of verifier for ccir
  Route::get('/ccir-verifier-attachments/{ccirId}/{verifierId}', 'CcirController@getUploadedFilesApprover');
  // Generate ccir by date
  Route::get('/ccirs-generate/{startDate}/{endDate}', 'CcirController@generate');

  
  // NCN routes
  Route::get('/ncns', 'NcnController@index');
  Route::get('/ncns-approved-forms', 'NcnController@approvedForms');
  Route::get('/ncn/{id}', 'NcnController@show');
  Route::patch('/ncn/{ncn}', 'NcnController@update');
  Route::get('/ncns-by/{category}', 'NcnController@category');
  // Get uploaded files of requester for ncn
  Route::get('/ncn-requester-attachments/{ncnId}/{requesterId}', 'NcnController@getUploadedFilesRequester');
  // Get uploaded files of approver for ncn
  Route::get('/ncn-approver-attachments/{ncnId}/{approverId}', 'NcnController@getUploadedFilesApprover');
  // Generate ncns by date
  Route::get('/ncns-generate/{startDate}/{endDate}', 'NcnController@generate');
});


