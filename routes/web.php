<?php

use App\Worksheet;
use GuzzleHttp\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;




// Route url
Route::get('/', function(){
  return redirect('admin/dashboard');
});

/*
|--------------------------------------------------------------------------
| MAIN APP
|--------------------------------------------------------------------------
*/

// Admin
Route::group([
  'as' => 'admin.',
  'prefix' => 'admin',
  'namespace' => 'app',
  'middleware' => 'admin'
], function() {
  Route::view('dashboard', 'app.admin.dashboard')->name('dashboard');

  //User MS
  Route::resource('users', 'UserController');

  // WorkSheet Object
  Route::resource('worksheetobject', 'WorksheetObjectController');

  // Worksheet
  Route::resource('worksheets', 'WorksheetController');
  Route::get('vehicle-worksheets', 'WorksheetController@vehicle_data');
  Route::get('all-timeline', 'WorksheetController@all_timeline');
  Route::get('timeline-by-worksheet', 'WorksheetController@timeline_by_worksheet');
  Route::get('timeline-by-operator', 'WorksheetController@timeline_by_operator');

  // Chat
  Route::resource('chat', 'ChatController');
  Route::get('chat_status/{id}', 'ChatController@chat_status');

  // Task
  Route::resource('task', 'TaskController');

  // Timeline
  Route::get('timeline', 'TimelineController@index');
  Route::post('timeline', 'TimelineController@update')->name('update');
});

//Acceptor
Route::group([
  'as' => 'acceptor.',
  'prefix' => 'acceptor',
  'namespace' => 'app',
  'middleware' => 'acceptor'
], function() {
  Route::view('dashboard', 'app.acceptor.dashboard')->name('dashboard');

  // WorkSheet Object
  Route::resource('worksheetobject', 'WorksheetObjectController');

  // Worksheet
  Route::resource('worksheets', 'WorksheetController');
  Route::get('vehicle-worksheets', 'WorksheetController@vehicle_data');
  Route::get('all-timeline', 'WorksheetController@all_timeline');
  Route::get('timeline-by-worksheet', 'WorksheetController@timeline_by_worksheet');
  Route::get('timeline-by-operator', 'WorksheetController@timeline_by_operator');

  //Chat
  Route::resource('chat', 'ChatController');
  Route::get('chat_status/{id}', 'ChatController@chat_status');
});

//Operator
Route::group([
  'as' => 'operator.',
  'prefix' => 'operator',
  'namespace' => 'app',
  'middleware' => 'operator'
], function() {
  Route::view('dashboard', 'app.operator.dashboard')->name('dashboard');

  // Jobs
  Route::get('worksheets', 'OperatorController@worksheets')->name('worksheets.index');
  Route::post('start-timer', 'OperatorController@timer')->name('timer');

  //Chat
  Route::resource('chat', 'ChatController');
  Route::get('chat_status/{id}', 'ChatController@chat_status');
});

//Customer
Route::group([
  'as' => 'customer.',
  'prefix' => 'customer',
  'namespace' => 'app',
  'middleware' => 'customer'
], function() {
  Route::get('dashboard', function(){
    // return Worksheet::with('user_customer')->get();;
    return view('app.customer.dashboard')->with([
      'worksheets' => Worksheet::with('user_customer')->where('customer_accepted', '0')->get()
    ]);
  })->name('dashboard');

  Route::get('submit', function(Request $req){
    Worksheet::where('id', $req->wid)->update([
      'customer_accepted' => 1,
      // 'customer_accept_at' =>
    ]);

    return redirect()->back();
  });
});

/*
|--------------------------------------------------------------------------
| THEME ROUTES
|--------------------------------------------------------------------------
*/

Route::group([
  // 'middleware' => 'mainpage' //enable this middleware in production server
],function(){

  Route::get('theme', function() {
    return redirect('dashboard-analytics');
  });

  // Route Dashboards
  Route::get('/dashboard-analytics', 'DashboardController@dashboardAnalytics');
  Route::get('/dashboard-ecommerce', 'DashboardController@dashboardEcommerce');

  // Route Apps
  Route::get('/app-email', 'EmailAppController@emailApp');
  Route::get('/app-chat', 'ChatAppController@chatApp');
  Route::get('/app-todo', 'ToDoAppController@todoApp');
  Route::get('/app-calender', 'CalenderAppController@calenderApp');
  Route::get('/app-ecommerce-shop', 'EcommerceAppController@ecommerce_shop');
  Route::get('/app-ecommerce-details', 'EcommerceAppController@ecommerce_details');
  Route::get('/app-ecommerce-wishlist', 'EcommerceAppController@ecommerce_wishlist');
  Route::get('/app-ecommerce-checkout', 'EcommerceAppController@ecommerce_checkout');

  // Users Pages
  Route::get('/app-user-list', 'UserPagesController@user_list');
  Route::get('/app-user-view', 'UserPagesController@user_view');
  Route::get('/app-user-edit', 'UserPagesController@user_edit');

  // Route Data List
  Route::resource('/data-list-view', 'DataListController');
  Route::resource('/data-thumb-view', 'DataThumbController');


  // Route Content
  Route::get('/content-grid', 'ContentController@grid');
  Route::get('/content-typography', 'ContentController@typography');
  Route::get('/content-text-utilities', 'ContentController@text_utilities');
  Route::get('/content-syntax-highlighter', 'ContentController@syntax_highlighter');
  Route::get('/content-helper-classes', 'ContentController@helper_classes');

  // Route Color
  Route::get('/colors', 'ContentController@colors');

  // Route Icons
  Route::get('/icons-feather', 'IconsController@icons_feather');
  Route::get('/icons-font-awesome', 'IconsController@icons_font_awesome');

  // Route Cards
  Route::get('/card-basic', 'CardsController@card_basic');
  Route::get('/card-advance', 'CardsController@card_advance');
  Route::get('/card-statistics', 'CardsController@card_statistics');
  Route::get('/card-analytics', 'CardsController@card_analytics');
  Route::get('/card-actions', 'CardsController@card_actions');

  // Route Components
  Route::get('/component-alert', 'ComponentsController@alert');
  Route::get('/component-buttons', 'ComponentsController@buttons');
  Route::get('/component-breadcrumbs', 'ComponentsController@breadcrumbs');
  Route::get('/component-carousel', 'ComponentsController@carousel');
  Route::get('/component-collapse', 'ComponentsController@collapse');
  Route::get('/component-dropdowns', 'ComponentsController@dropdowns');
  Route::get('/component-list-group', 'ComponentsController@list_group');
  Route::get('/component-modals', 'ComponentsController@modals');
  Route::get('/component-pagination', 'ComponentsController@pagination');
  Route::get('/component-navs', 'ComponentsController@navs');
  Route::get('/component-navbar', 'ComponentsController@navbar');
  Route::get('/component-tabs', 'ComponentsController@tabs');
  Route::get('/component-pills', 'ComponentsController@pills');
  Route::get('/component-tooltips', 'ComponentsController@tooltips');
  Route::get('/component-popovers', 'ComponentsController@popovers');
  Route::get('/component-badges', 'ComponentsController@badges');
  Route::get('/component-pill-badges', 'ComponentsController@pill_badges');
  Route::get('/component-progress', 'ComponentsController@progress');
  Route::get('/component-media-objects', 'ComponentsController@media_objects');
  Route::get('/component-spinner', 'ComponentsController@spinner');
  Route::get('/component-toast', 'ComponentsController@toast');

  // Route Extra Components
  Route::get('/ex-component-avatar', 'ExtraComponentsController@avatar');
  Route::get('/ex-component-chips', 'ExtraComponentsController@chips');
  Route::get('/ex-component-divider', 'ExtraComponentsController@divider');

  // Route Forms
  Route::get('/form-select', 'FormsController@select');
  Route::get('/form-switch', 'FormsController@switch');
  Route::get('/form-checkbox', 'FormsController@checkbox');
  Route::get('/form-radio', 'FormsController@radio');
  Route::get('/form-input', 'FormsController@input');
  Route::get('/form-input-groups', 'FormsController@input_groups');
  Route::get('/form-number-input', 'FormsController@number_input');
  Route::get('/form-textarea', 'FormsController@textarea');
  Route::get('/form-date-time-picker', 'FormsController@date_time_picker');
  Route::get('/form-layout', 'FormsController@layouts');
  Route::get('/form-wizard', 'FormsController@wizard');
  Route::get('/form-validation', 'FormsController@validation');

  // Route Tables
  Route::get('/table', 'TableController@table');
  Route::get('/table-datatable', 'TableController@datatable');
  Route::get('/table-ag-grid', 'TableController@ag_grid');

  // Route Pages
  Route::get('/page-user-profile', 'PagesController@user_profile');
  Route::get('/page-faq', 'PagesController@faq');
  Route::get('/page-knowledge-base', 'PagesController@knowledge_base');
  Route::get('/page-kb-category', 'PagesController@kb_category');
  Route::get('/page-kb-question', 'PagesController@kb_question');
  Route::get('/page-search', 'PagesController@search');
  Route::get('/page-invoice', 'PagesController@invoice');
  Route::get('/page-account-settings', 'PagesController@account_settings');

  // Route Authentication Pages
  Route::get('/auth-login', 'AuthenticationController@login');
  Route::get('/auth-register', 'AuthenticationController@register');
  Route::get('/auth-forgot-password', 'AuthenticationController@forgot_password');
  Route::get('/auth-reset-password', 'AuthenticationController@reset_password');
  Route::get('/auth-lock-screen', 'AuthenticationController@lock_screen');



  // Route Charts & Google Maps
  Route::get('/chart-apex', 'ChartsController@apex');
  Route::get('/chart-chartjs', 'ChartsController@chartjs');
  Route::get('/chart-echarts', 'ChartsController@echarts');
  Route::get('/maps-google', 'ChartsController@maps_google');

  // Route Extension Components
  Route::get('/ext-component-sweet-alerts', 'ExtensionController@sweet_alert');
  Route::get('/ext-component-toastr', 'ExtensionController@toastr');
  Route::get('/ext-component-noui-slider', 'ExtensionController@noui_slider');
  Route::get('/ext-component-file-uploader', 'ExtensionController@file_uploader');
  Route::get('/ext-component-quill-editor', 'ExtensionController@quill_editor');
  Route::get('/ext-component-drag-drop', 'ExtensionController@drag_drop');
  Route::get('/ext-component-tour', 'ExtensionController@tour');
  Route::get('/ext-component-clipboard', 'ExtensionController@clipboard');
  Route::get('/ext-component-plyr', 'ExtensionController@plyr');
  Route::get('/ext-component-context-menu', 'ExtensionController@context_menu');
  Route::get('/ext-component-swiper', 'ExtensionController@swiper');
  Route::get('/ext-component-i18n', 'ExtensionController@i18n');

});

// Route Error Pages
Route::get('/error-404', 'MiscellaneousController@error_404');
Route::get('/error-500', 'MiscellaneousController@error_500');
Route::get('/page-not-authorized', 'MiscellaneousController@not_authorized');


Auth::routes([
  'register' => false, // Registration Routes...
  'reset' => false, // Password Reset Routes...
  'verify' => false, // Email Verification Routes...
]);
