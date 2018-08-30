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
 Route::get('/', function () {
   return view('welcome');
 });

//  Route::get('/', function () {
//    return redirect('/login');
//  });
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

});


// Accessible route only by admin 
Route::group(['middleware' => ['auth', 'role:administrator']], function(){ 
    // DRDR routes
    // Count all submitted drdr 
    Route::get('/admin/drdr-count', 'DrdrController@countDrdr');
    Route::get('/admin/drdrs', 'DrdrController@drdrAdminPage');
    Route::get('/admin/drdrs-all', 'DrdrController@getAllDrdrs');
    Route::get('/admin/ddrs', 'DdrController@ddrAdminPage');
    Route::get('/admin/ddrs-all', 'DdrController@getAllDdrs');


    // DRDR routes
    // Count all submitted ddr 
    Route::get('/admin/ddr-count', 'DdrController@countDdr');
    
    // NCN routes
    // Count all submitted ncn 
    Route::get('/admin/ncn-count', 'NcnController@countNcn');

    // CCIR routes
    // Count all submitted ncn 
    Route::get('/admin/ccir-count', 'CcirController@countCcir');
});
 
Route::group(['middleware' => ['auth', 'role:administrator,reviewer,approver']], function () {
  

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
  Route::get('/companies', 'CompanyController@index');
  Route::get('/company/{id}', 'CompanyController@show');
  Route::post('/company', 'CompanyController@store');
  Route::patch('/company/{company}', 'CompanyController@update');
  Route::delete('/company/{id}', 'CompanyController@destroy');

  // Department routes
  Route::get('/departments', 'DepartmentController@index');
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
  Route::get('/add-drdr', 'DrdrController@create');
  Route::get('/edit-drdr/{id}', 'DrdrController@edit');
  Route::get('/drdrs-pending-reviews', 'DrdrController@pendingReviews');
  Route::get('/drdrs-reviewed-forms', 'DrdrController@reviewedForms');
  Route::get('/drdrs-pending-approvals', 'DrdrController@pendingApprovals');
  Route::get('/drdrs-approved-forms', 'DrdrController@approvedForms');
  Route::post('/drdr', 'DrdrController@store');
  Route::get('/drdr/{id}', 'DrdrController@show');
  Route::post('/drdr/{drdr}', 'DrdrController@update');
  Route::delete('/drdr/{id}', 'DrdrController@destroy');
  Route::get('/getReviewer/{id}', 'DrdrController@getCompanyReviewers');
  Route::get('/drdr-submitted', 'DrdrController@submitted');
  Route::get('/drdrs-by/{category}', 'DrdrController@category');
  Route::get('/drdr-review/{id}', 'DrdrController@show');
  Route::get('/drdr-data/{id}', 'DrdrController@data');
  Route::get('/drdr-approve/{id}', 'DrdrController@showApprovedForm');
  Route::post('/drdr-approved', 'DrdrController@approved');
  Route::post('/drdr-reviewed', 'DrdrController@reviewed');
  // Get specific approver per company
  Route::get('/drdr-approver/{company_id}', 'DrdrController@approver');
  // Return details form of approved DRDR
  Route::get('/drdr-view-approved/{drdr_id}', 'DrdrController@showDetailsDrdr');


   // DDR routes
   Route::get('/add-ddr', 'DdrController@create');
   Route::get('/ddrs', 'DdrController@index');
   Route::get('/ddrs-approved-forms', 'DdrController@approvedForms');
   Route::post('/ddr', 'DdrController@store');
   Route::get('/ddr/{id}', 'DdrController@show');
   Route::patch('/ddr/{ddr}', 'DdrController@update');
   Route::delete('/ddr/{id}', 'DdrController@destroy');
   Route::get('/ddrs-submitted', 'DdrController@submitted');
   Route::get('/ddrs-by/{category}', 'DdrController@category');

  // CCIR routes
  Route::get('/add-ccir', 'CcirController@create');
  Route::get('/ccirs', 'CcirController@index');
  Route::post('/ccir', 'CcirController@store');
  Route::get('/ccir/{id}', 'CcirController@show');
  Route::patch('/ccir/{ccir}', 'CcirController@update');
  Route::delete('/ccir/{id}', 'CcirController@destroy');
  Route::get('/getApprover/{id}', 'CcirController@getCompanyApprovers');
  Route::get('/ccirs-submitted', 'CcirController@submitted');
  Route::get('/ccirs-approved-forms', 'CcirController@approvedForms');
  
  // NCN routes
  Route::get('/add-ncn', 'NcnController@create');
  Route::get('/ncns', 'NcnController@index');
  Route::get('/ncns-approved-forms', 'NcnController@approvedForms');
  Route::post('/ncn', 'NcnController@store');
  Route::get('/ncn/{id}', 'NcnController@show');
  Route::patch('/ncn/{ncn}', 'NcnController@update');
  Route::delete('/ncn/{id}', 'NcnController@destroy');
  Route::get('/getNcnApprovers/{company}/{department}', 'NcnController@getNcnApprovers');
  Route::get('/ncns-submitted', 'NcnController@submitted');
  Route::get('/ncns-by/{category}', 'NcnController@category');
});


