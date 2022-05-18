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
Route::get('/', 'AdminController@index');

Auth::routes();

Route::get('export-alumni/{type}', 'admin\SiswaController@export_alumni')->name('export.alumni');
Route::get('export-tidak_aktif/{type}', 'admin\SiswaController@export_tidak_aktif')->name('export.tidak_aktif');
Route::get('export-file/{type}', 'admin\SiswaController@exportFile')->name('export.siswa');
Route::get('export-calon/{type}', 'admin\CandidateController@exportFile')->name('export.calon');
Route::get('export-formulirharian/{type}', 'admin\LaporanController@export')->name('export.formulir-harian');
Route::get('/formulir-harian/invoice', ['as' => 'Laporan', 'uses' => 'admin\LaporanController@invoice']);
Route::get('export-daftarharian/{type}', 'admin\LaporanController@export_daftar')->name('export.daftar-harian');
Route::get('/daftar-harian/invoice', ['as' => 'Laporan', 'uses' => 'admin\LaporanController@invoice_daftar']);
Route::get('export-sppharian/{type}', 'admin\LaporanController@export_spp')->name('export.spp-harian');
Route::get('/spp-harian/invoice', ['as' => 'Laporan', 'uses' => 'admin\LaporanController@invoice_spp']);
Route::get('export-rekap/{type}', 'admin\LaporanController@export_rekap')->name('export.spp-rekap');
Route::get('/spp-rekap/invoice', ['as' => 'Laporan', 'uses' => 'admin\LaporanController@invoice_rekap']);
Route::get('/pengembalian/invoice', ['as' => 'Laporan', 'uses' => 'admin\UangKeluarController@Pengembalian']);
Route::get('pengembalian/{type}', 'admin\UangKeluarController@export_pengembalian')->name('export.pengembalian');
Route::get('/all_siswa/invoice', ['as' => 'Laporan', 'uses' => 'admin\LaporanController@invoice_all_siswa']);
Route::get('/tunggakan/invoice', ['as' => 'Laporan', 'uses' => 'admin\LaporanController@invoice_tunggakan']);

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/detail', 'DetailController@index');

Route::get('/email-template-mailchimp', function () {
    return view('emails.testing_email');
});

// Make PDF

Route::get('siswa/cetak_pdf', 'admin\SiswaController@cetak_pdf');
Route::get('penerimaan/cetak_pdf', 'admin\PenerimaanController@cetak_pdf');

//Metode Forward Chaining
Route::get('/searchkategori', ['as' => 'searchkategori', 'uses' => 'HomeController@searchKategori']);
Route::get('/searchtype', ['as' => 'searchtype', 'uses' => 'HomeController@searchType']);

//Repository University:
// Route::get('/statistic-repo', 'StaticsController@repoStatic');
// Route::get('/stats/statistic-repo', '\Voerro\Laravel\VisitorTracker\Controllers\StatisticsController@oss')->name('visitortracker.repostaticpage');
Route::get('/stats/statistic-country', 'StatisticsController@pageCounter')->name('visitortracker.repostaticpage');
Route::get('/stats/page-url', 'StatisticsController@getVisitorUrl')->name('visitortracker.pageurl');
Route::get('/stats/chart', 'StatisticsController@chartPage')->name('visitortracker.chart-static');
Route::get('/stats/page_os', 'StatisticsController@pageOs')->name('visitortracker.page-os');
Route::get('/stats/page-browser', 'StatisticsController@pageBrowser')->name('visitortracker.pagebrowser');
 
// Group Authenticated First
Route::group(['middleware' => ['auth']], function() {
    Route::get('/admin', ['as' => 'admin.dashboard', 'uses' => 'HomeController@admin']);

	//User
	Route::get('/user/index', ['as' => 'index', 'uses' => 'admin\UserController@index']);
	Route::get('/user/create', ['as' => 'create', 'uses' => 'admin\UserController@create']);
	Route::post('/user/create', ['as' => 'store', 'uses' => 'admin\UserController@store']);
	Route::get('/user/edit/{id}', ['as' => 'edit', 'uses' => 'admin\UserController@edit']);
	Route::put('/user/edit/{id}', ['as' => 'edit', 'uses' => 'admin\UserController@update']);
	Route::get('/user/show/{id}', ['as' => 'show', 'uses' => 'admin\UserController@show']);
	Route::delete('/user/destroy/{id}', ['as' => 'destroy', 'uses' => 'admin\UserController@destroy']);
	Route::get('/searchuser', ['as' => 'searchjabatan', 'uses' => 'admin\UserController@search']);

	// Role
	Route::resource('roles', 'admin\RoleController');
	Route::get('search-roles','admin\RoleController@search');
	Route::resource('user-groups', 'admin\UserGroupController');
	Route::get('search-user-groups','admin\UserGroupController@search');
	Route::resource('groups', 'admin\GroupController');
	Route::get('search-groups','admin\GroupController@search');
	Route::resource('group-roles', 'admin\GroupRoleController');
	Route::get('search-group-roles','admin\GroupRoleController@search');

	// ===========================================admin==================================================================

	//Penerimaan
	Route::get('/penerimaans/index', ['as' => 'penerimaans', 'uses' => 'admin\PenerimaanController@index']);
	Route::get('/penerimaan/getdata', ['as' => 'penerimaans', 'uses' => 'admin\PenerimaanController@getdata']);
	Route::get('/penerimaans/invoice/{id}', ['as' => 'penerimaans', 'uses' => 'admin\PenerimaanController@invoice']);
	Route::get('/penerimaans/create', ['as' => 'create', 'uses' => 'admin\PenerimaanController@create']);
	Route::post('/penerimaans/create', ['as' => 'store', 'uses' => 'admin\PenerimaanController@store']);
	Route::get('/penerimaans/edit/{id}', ['as' => 'edit', 'uses' => 'admin\PenerimaanController@edit']);
	Route::put('/penerimaans/edit/{id}', ['as' => 'edit', 'uses' => 'admin\PenerimaanController@update']);
	Route::get('/penerimaans/show/{id}', ['as' => 'show', 'uses' => 'admin\PenerimaanController@show']);
	Route::delete('/penerimaans/destroy/{id}', ['as' => 'destroy', 'uses' => 'admin\PenerimaanController@destroy']);
	Route::get('/searchpenerimaans', ['as' => 'searchpenerimaans', 'uses' => 'admin\PenerimaanController@search']);
	Route::get('/filterpenerimaans', ['as' => 'filterpenerimaans', 'uses' => 'admin\PenerimaanController@filter']);
	
	
	// Siswa
	Route::get('/siswa/index', ['as' => 'index', 'uses' => 'admin\SiswaController@index']);
	Route::get('/alumni', ['as' => 'alumni', 'uses' => 'admin\SiswaController@alumni']);
	Route::get('/siswa/tidak_aktif', ['as' => 'tidak_aktif', 'uses' => 'admin\SiswaController@siswa_tidak_aktif']);
	Route::get('/siswa/invoice/{id}', ['as' => 'siswa', 'uses' => 'admin\SiswaController@invoice']);
	Route::get('/siswa/create', ['as' => 'create', 'uses' => 'admin\SiswaController@create']);
	Route::post('/siswa/create', ['as' => 'store', 'uses' => 'admin\SiswaController@store']);
	Route::get('/siswa/edit/{id}', ['as' => 'edit', 'uses' => 'admin\SiswaController@edit']);
	Route::put('/siswa/edit/{id}', ['as' => 'edit', 'uses' => 'admin\SiswaController@update']);
	Route::get('/siswa/show/{id}', ['as' => 'show', 'uses' => 'admin\SiswaController@show']);
	Route::delete('/siswa/destroy/{id}', ['as' => 'destroy', 'uses' => 'admin\SiswaController@destroy']);
	Route::get('/searchsiswa', ['as' => 'searchsiswa', 'uses' => 'admin\SiswaController@search']);
	Route::get('/searchsekolahonsiswa', ['as' => 'searchsekolahonsiswa', 'uses' => 'admin\SiswaController@searchsekolahonsiswa']);
	Route::get('/siswa/pay/{id}', ['as' => 'store', 'uses' => 'admin\SiswaController@payment']);
	Route::post('/siswa/payment', ['as' => 'store', 'uses' => 'admin\SiswaController@pay']);
	Route::get('/siswa/reset', ['as' => 'resetsiswa', 'uses' => 'admin\SiswaController@reset']);

	// Bukti
	Route::get('/bukti/index', ['as' => 'bukti', 'uses' => 'admin\BuktiTuntasController@index']);
	Route::get('/bukti/create', ['as' => 'create', 'uses' => 'admin\BuktiTuntasController@create']);
	Route::post('/bukti/create', ['as' => 'store', 'uses' => 'admin\BuktiTuntasController@store']);
	Route::get('/bukti/edit/{id}', ['as' => 'edit', 'uses' => 'admin\BuktiTuntasController@edit']);
	Route::put('/bukti/edit/{id}', ['as' => 'edit', 'uses' => 'admin\BuktiTuntasController@update']);
	Route::get('/bukti/show/{id}', ['as' => 'show', 'uses' => 'admin\BuktiTuntasController@show']);
	Route::delete('/bukti/destroy/{id}', ['as' => 'destroy', 'uses' => 'admin\BuktiTuntasController@destroy']);
	Route::get('/searchbukti', ['as' => 'searchbukti', 'uses' => 'admin\BuktiTuntasController@search']);
	Route::get('/filterbukti', ['as' => 'filterbukti', 'uses' => 'admin\BuktiTuntasController@filter']);

	// Bukti Pengeluaran
	Route::get('/buktipengeluaran/index', ['as' => 'bukti', 'uses' => 'admin\BuktiPengeluaranController@index']);
	Route::get('/buktipengeluaran/create', ['as' => 'create', 'uses' => 'admin\BuktiPengeluaranController@create']);
	Route::post('/buktipengeluaran/create', ['as' => 'store', 'uses' => 'admin\BuktiPengeluaranController@store']);
	Route::get('/buktipengeluaran/edit/{id}', ['as' => 'edit', 'uses' => 'admin\BuktiPengeluaranController@edit']);
	Route::put('/buktipengeluaran/edit/{id}', ['as' => 'edit', 'uses' => 'admin\BuktiPengeluaranController@update']);
	Route::get('/buktipengeluaran/show/{id}', ['as' => 'show', 'uses' => 'admin\BuktiPengeluaranController@show']);
	Route::delete('/buktipengeluaran/destroy/{id}', ['as' => 'destroy', 'uses' => 'admin\BuktiPengeluaranController@destroy']);
	Route::get('/searchbuktipengeluaran', ['as' => 'searchbuktipengeluaran', 'uses' => 'admin\BuktiPengeluaranController@search']);
	Route::get('/filterbuktipengeluaran', ['as' => 'filterbuktipengeluaran', 'uses' => 'admin\BuktiPengeluaranController@filter']);

	// Sekolah
	Route::get('/sekolah/index', ['as' => 'index', 'uses' => 'admin\SekolahController@index']);
	Route::get('/sekolah/create', ['as' => 'create', 'uses' => 'admin\SekolahController@create']);
	Route::post('/sekolah/create', ['as' => 'store', 'uses' => 'admin\SekolahController@store']);
	Route::get('/sekolah/edit/{id}', ['as' => 'edit', 'uses' => 'admin\SekolahController@edit']);
	Route::put('/sekolah/edit/{id}', ['as' => 'edit', 'uses' => 'admin\SekolahController@update']);
	Route::get('/sekolah/show/{id}', ['as' => 'show', 'uses' => 'admin\SekolahController@show']);
	Route::delete('/sekolah/destroy/{id}', ['as' => 'destroy', 'uses' => 'admin\SekolahController@destroy']);
	Route::get('/searchsekolah', ['as' => 'searchsekolah', 'uses' => 'admin\SekolahController@search']);
	Route::get('/sekolah/reset', ['as' => 'reset', 'uses' => 'admin\SekolahController@reset']);

	// Pengeluaran
	Route::get('/luaran/index', ['as' => 'luaran', 'uses' => 'admin\PengeluaranController@index']);
	Route::get('/luaran/invoice/{id}', ['as' => 'luaran', 'uses' => 'admin\PengeluaranController@invoice']);
	Route::get('/luaran/create', ['as' => 'create', 'uses' => 'admin\PengeluaranController@create']);
	Route::post('/luaran/create', ['as' => 'store', 'uses' => 'admin\PengeluaranController@store']);
	Route::get('/luaran/edit/{id}', ['as' => 'edit', 'uses' => 'admin\PengeluaranController@edit']);
	Route::put('/luaran/edit/{id}', ['as' => 'edit', 'uses' => 'admin\PengeluaranController@update']);
	Route::get('/luaran/show/{id}', ['as' => 'show', 'uses' => 'admin\PengeluaranController@show']);
	Route::delete('/luaran/destroy/{id}', ['as' => 'destroy', 'uses' => 'admin\PengeluaranController@destroy']);
	Route::get('/searchluaran', ['as' => 'searchluaran', 'uses' => 'admin\PengeluaranController@search']);
	Route::get('/filterluarans', ['as' => 'filterluarans', 'uses' => 'admin\PengeluaranController@filter']);
	
	// Biaya
	Route::get('/biaya/index', ['as' => 'biaya', 'uses' => 'admin\BiayaController@index']);
	Route::get('/biaya/invoice/{id}', ['as' => 'biaya', 'uses' => 'admin\BiayaController@invoice']);
	Route::get('/biaya/create', ['as' => 'create', 'uses' => 'admin\BiayaController@create']);
	Route::post('/biaya/create', ['as' => 'store', 'uses' => 'admin\BiayaController@store']);
	Route::get('/biaya/edit/{id}', ['as' => 'edit', 'uses' => 'admin\BiayaController@edit']);
	Route::put('/biaya/edit/{id}', ['as' => 'edit', 'uses' => 'admin\BiayaController@update']);
	Route::get('/biaya/show/{id}', ['as' => 'show', 'uses' => 'admin\BiayaController@show']);
	Route::delete('/biaya/destroy/{id}', ['as' => 'destroy', 'uses' => 'admin\BiayaController@destroy']);
	Route::get('/searchbiaya', ['as' => 'searchbiaya', 'uses' => 'admin\BiayaController@search']);

	// Calon
	Route::get('/calon/index', ['as' => 'calon', 'uses' => 'admin\CalonController@index']);
	Route::get('/calon/invoice/{id}', ['as' => 'calon', 'uses' => 'admin\CalonController@invoice']);
	Route::get('/calon/create', ['as' => 'create', 'uses' => 'admin\CalonController@create']);
	Route::post('/calon/create', ['as' => 'store', 'uses' => 'admin\CalonController@store']);
	Route::get('/calon/edit/{id}', ['as' => 'edit', 'uses' => 'admin\CalonController@edit']);
	Route::put('/calon/edit/{id}', ['as' => 'edit', 'uses' => 'admin\CalonController@update']);
	Route::get('/calon/show/{id}', ['as' => 'show', 'uses' => 'admin\CalonController@show']);
	Route::delete('/calon/destroy/{id}', ['as' => 'destroy', 'uses' => 'admin\CalonController@destroy']);
	Route::get('/searchcalon', ['as' => 'searchcalon', 'uses' => 'admin\CalonController@search']);
	Route::get('/filtercalon', ['as' => 'filtercalon', 'uses' => 'admin\CalonController@filter']);
	
	Route::get('/filterluaran', ['as' => 'searchluaran', 'uses' => 'admin\PengeluaranController@filter']);

	// User Sekolah
	Route::get('/user_sekolah/index', ['as' => 'user_sekolah', 'uses' => 'admin\UserSekolahController@index']);
	Route::get('/user_sekolah/invoice/{id}', ['as' => 'user_sekolah', 'uses' => 'admin\UserSekolahController@invoice']);
	Route::get('/user_sekolah/create', ['as' => 'create', 'uses' => 'admin\UserSekolahController@create']);
	Route::post('/user_sekolah/create', ['as' => 'store', 'uses' => 'admin\UserSekolahController@store']);
	Route::get('/user_sekolah/edit/{id}', ['as' => 'edit', 'uses' => 'admin\UserSekolahController@edit']);
	Route::put('/user_sekolah/edit/{id}', ['as' => 'edit', 'uses' => 'admin\UserSekolahController@update']);
	Route::get('/user_sekolah/show/{id}', ['as' => 'show', 'uses' => 'admin\UserSekolahController@show']);
	Route::delete('/user_sekolah/destroy/{id}', ['as' => 'destroy', 'uses' => 'admin\UserSekolahController@destroy']);
	Route::get('/searchuser_sekolah', ['as' => 'searchuser_sekolah', 'uses' => 'admin\UserSekolahController@search']);

	// Route::get('/ajax-bayar  ', ['as' => 'store', 'uses' =>'admin\CalonController@ajax_getbayar']);

	// Candidate
	Route::get('/candidate/index', ['as' => 'candidate', 'uses' => 'admin\CandidateController@index']);
	Route::get('/candidate/invoice/{id}', ['as' => 'candidate', 'uses' => 'admin\CandidateController@invoice']);
	Route::get('/candidate/create', ['as' => 'create', 'uses' => 'admin\CandidateController@create']);
	Route::post('/candidate/create', ['as' => 'store', 'uses' => 'admin\CandidateController@store']);
	Route::get('/candidate/edit/{id}', ['as' => 'edit', 'uses' => 'admin\CandidateController@edit']);
	Route::put('/candidate/edit/{id}', ['as' => 'edit', 'uses' => 'admin\CandidateController@update']);
	Route::get('/candidate/show/{id}', ['as' => 'show', 'uses' => 'admin\CandidateController@show']);
	Route::delete('/candidate/destroy/{id}', ['as' => 'destroy', 'uses' => 'admin\CandidateController@destroy']);
	Route::get('/searchcandidate', ['as' => 'searchcandidate', 'uses' => 'admin\CandidateController@search']);
	Route::get('/candidate/pay/{id}', ['as' => 'edit', 'uses' => 'admin\CandidateController@pay']);
	Route::post('/candidate/pay', ['as' => 'store', 'uses' => 'admin\CandidateController@payment']);
	Route::get('/approve/{id}', ['as' => 'store', 'uses' => 'admin\CandidateController@approve']);
	Route::post('/approve/create', ['as' => 'store', 'uses' => 'admin\CandidateController@approve_on_show']);
	Route::get('/filtercandidate', ['as' => 'filtercandidate', 'uses' => 'admin\CandidateController@filter']);

	// Tahun
	Route::get('/year/index', ['as' => 'year', 'uses' => 'admin\YearController@index']);
	Route::get('/year/invoice/{id}', ['as' => 'year', 'uses' => 'admin\YearController@invoice']);
	Route::get('/year/create', ['as' => 'create', 'uses' => 'admin\YearController@create']);
	Route::post('/year/create', ['as' => 'store', 'uses' => 'admin\YearController@store']);
	Route::get('/year/edit/{id}', ['as' => 'edit', 'uses' => 'admin\YearController@edit']);
	Route::put('/year/edit/{id}', ['as' => 'edit', 'uses' => 'admin\YearController@update']);
	Route::get('/year/show/{id}', ['as' => 'show', 'uses' => 'admin\YearController@show']);
	Route::delete('/year/destroy/{id}', ['as' => 'destroy', 'uses' => 'admin\YearController@destroy']);
	Route::get('/searchyear', ['as' => 'searchyear', 'uses' => 'admin\YearController@search']);

	// Payment
	Route::get('/payment/index', ['as' => 'payment', 'uses' => 'admin\PaymentTypeController@index']);
	Route::get('/payment/invoice/{id}', ['as' => 'payment', 'uses' => 'admin\PaymentTypeController@invoice']);
	Route::get('/payment/create', ['as' => 'create', 'uses' => 'admin\PaymentTypeController@create']);
	Route::post('/payment/create', ['as' => 'store', 'uses' => 'admin\PaymentTypeController@store']);
	Route::get('/payment/edit/{id}', ['as' => 'edit', 'uses' => 'admin\PaymentTypeController@edit']);
	Route::put('/payment/edit/{id}', ['as' => 'edit', 'uses' => 'admin\PaymentTypeController@update']);
	Route::get('/payment/show/{id}', ['as' => 'show', 'uses' => 'admin\PaymentTypeController@show']);
	Route::delete('/payment/destroy/{id}', ['as' => 'destroy', 'uses' => 'admin\PaymentTypeController@destroy']);
	Route::get('/searchpayment', ['as' => 'searchpayment', 'uses' => 'admin\PaymentTypeController@search']);

	// Cost
	Route::get('/cost/index', ['as' => 'cost', 'uses' => 'admin\CostController@index']);
	Route::get('/cost/invoice/{id}', ['as' => 'cost', 'uses' => 'admin\CostController@invoice']);
	Route::get('/cost/create', ['as' => 'create', 'uses' => 'admin\CostController@create']);
	Route::post('/cost/create', ['as' => 'store', 'uses' => 'admin\CostController@store']);
	Route::get('/cost/edit/{id}', ['as' => 'edit', 'uses' => 'admin\CostController@edit']);
	Route::put('/cost/edit/{id}', ['as' => 'edit', 'uses' => 'admin\CostController@update']);
	Route::get('/cost/show/{id}', ['as' => 'show', 'uses' => 'admin\CostController@show']);
	Route::delete('/cost/destroy/{id}', ['as' => 'destroy', 'uses' => 'admin\CostController@destroy']);
	Route::get('/searchcost', ['as' => 'searchcost', 'uses' => 'admin\CostController@search']);

	// Program
	Route::get('/programs/index', ['as' => 'programs', 'uses' => 'admin\ProgramController@index']);
	Route::get('/programs/invoice/{id}', ['as' => 'programs', 'uses' => 'admin\ProgramController@invoice']);
	Route::get('/programs/create', ['as' => 'create', 'uses' => 'admin\ProgramController@create']);
	Route::post('/programs/create', ['as' => 'store', 'uses' => 'admin\ProgramController@store']);
	Route::get('/programs/edit/{id}', ['as' => 'edit', 'uses' => 'admin\ProgramController@edit']);
	Route::put('/programs/edit/{id}', ['as' => 'edit', 'uses' => 'admin\ProgramController@update']);
	Route::get('/programs/show/{id}', ['as' => 'show', 'uses' => 'admin\ProgramController@show']);
	Route::delete('/programs/destroy/{id}', ['as' => 'destroy', 'uses' => 'admin\ProgramController@destroy']);
	Route::get('/searchprograms', ['as' => 'searchprograms', 'uses' => 'admin\ProgramController@search']);

	// Detail Payment
	Route::get('/detail_payments/index', ['as' => 'detail_payments', 'uses' => 'admin\DetailPaymentController@index']);
	Route::get('/detail_payments/invoice/{id}', ['as' => 'detail_payments', 'uses' => 'admin\DetailPaymentController@invoice']);
	Route::get('/detail_payments/create', ['as' => 'create', 'uses' => 'admin\DetailPaymentController@create']);
	Route::post('/detail_payments/create', ['as' => 'store', 'uses' => 'admin\DetailPaymentController@store']);
	Route::get('/detail_payments/edit/{id}', ['as' => 'edit', 'uses' => 'admin\DetailPaymentController@edit']);
	Route::put('/detail_payments/edit/{id}', ['as' => 'edit', 'uses' => 'admin\DetailPaymentController@update']);
	Route::get('/detail_payments/show/{id}', ['as' => 'show', 'uses' => 'admin\DetailPaymentController@show']);
	Route::delete('/detail_payments/destroy/{id}', ['as' => 'destroy', 'uses' => 'admin\DetailPaymentController@destroy']);
	Route::get('/searchdetail_payments', ['as' => 'searchdetail_payments', 'uses' => 'admin\DetailPaymentController@search']);

	//AJAX
	Route::get('/ajax-sekolah-tahun  ', ['as' => 'store', 'uses' =>'admin\YearController@ajax_getsekolah_tahun']);
	Route::get('/ajax-thn-program', ['as' => 'store', 'uses' =>'admin\ProgramController@ajax_getsekolah_tahun']);
	Route::get('/ajax-program-bayar', ['as' => 'store', 'uses' =>'admin\PaymentTypeController@ajax_getsekolah_tahun']);
	Route::get('/ajax-payment-biaya', ['as' => 'store', 'uses' =>'admin\CostController@ajax_getsekolah_tahun']);
	Route::get('/ajax-candidate', ['as' => 'store', 'uses' =>'admin\CandidateController@ajax_getsekolah_tahun']);
	Route::get('/ajax-detail', ['as' => 'store', 'uses' =>'admin\DetailPaymentController@ajax_getsekolah_tahun']);
	Route::get('/ajax-siswa', ['as' => 'store', 'uses' =>'admin\SiswaController@ajax_getsekolah_tahun']);

	Route::get('/uang_keluar/index', ['as' => 'uang_keluar', 'uses' => 'admin\UangKeluarController@index']);
	Route::get('/uang_keluar/invoice/{id}', ['as' => 'uang_keluar', 'uses' => 'admin\UangKeluarController@invoice']);
	Route::get('/uang_keluar/create/{id}', ['as' => 'create', 'uses' => 'admin\UangKeluarController@create']);
	Route::post('/uang_keluar/create', ['as' => 'store', 'uses' => 'admin\UangKeluarController@store']);
	Route::post('/uang_keluar/create_class', ['as' => 'store', 'uses' => 'admin\UangKeluarController@store_class']);
	Route::delete('/uang_keluar/destroy/{id}', ['as' => 'destroy', 'uses' => 'admin\UangKeluarController@destroy']);
	Route::get('/search_uang_keluar', ['as' => 'search_uang_keluar', 'uses' => 'admin\UangKeluarController@search']);

	// Laporan
	Route::get('/rekap/index', ['as' => 'rekap', 'uses' => 'admin\LaporanController@rekap']);
	Route::get('/report-all-siswa', ['as' => 'harian', 'uses' => 'admin\LaporanController@harian']);
	Route::get('/tunggakan/index', ['as' => 'tunggakan', 'uses' => 'admin\LaporanController@tunggakan']);
	Route::get('/formulir-harian', ['as' => 'formulir', 'uses' => 'admin\LaporanController@formulir']);
	Route::get('/daftarulang-harian', ['as' => 'daftarulang', 'uses' => 'admin\LaporanController@daftar']);
	Route::get('/spp-harian', ['as' => 'spp', 'uses' => 'admin\LaporanController@spp']);
	Route::get('/search_tunggakan', ['as' => 'search_tunggakan', 'uses' => 'admin\LaporanController@filter_tunggakan']);
	Route::get('/search_all_siswa', ['as' => 'search_all_siswa', 'uses' => 'admin\LaporanController@search_all_siswa']);

	Route::get('/spp/index', ['as' => 'index', 'uses' => 'admin\SppController@index']);
	Route::post('/spp/import', ['as' => 'import', 'uses' => 'admin\SppController@import']);
	Route::get('/searchspp', ['as' => 'searchspp', 'uses' => 'admin\SppController@search']);
	Route::get('/approve/spp/{id}', ['as' => 'approve', 'uses' => 'admin\SppController@approve']);
	Route::get('/disagree/spp/{id}', ['as' => 'disagree', 'uses' => 'admin\SppController@disagree']);

	Route::post('/siswa/import', ['as' => 'import', 'uses' => 'admin\SiswaController@import']);

	Route::post('/daterange/fetch_data', 'DateRangeController@fetch_data')->name('daterange.fetch_data');
	Route::post('/daterange/fetch_data_register', 'DateRangeController@fetch_data_register')->name('daterange.fetch_data_register');
	Route::post('/daterange/fetch_data_spp', 'DateRangeController@fetch_data_spp')->name('daterange.fetch_data_spp');

	Route::get('/search_rekap', ['as' => 'search_rekap', 'uses' => 'admin\LaporanController@search_rekap']);

});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/ajax-get-id-penerimaan', ['as' => 'show', 'uses' => 'admin\BuktiTuntasController@get_data_penerimaan']);
Route::get('/ajax-get-id-pengeluaran', ['as' => 'show', 'uses' => 'admin\BuktiPengeluaranController@get_data_pengeluaran']);
Route::post('/ajax-filter-penerimaan', ['as' => 'show', 'uses' => 'admin\PenerimaanController@filter_penerimaan']);

Route::get('/json-payment','CandidateController@payment');
Route::get('/json-cost', 'CandidateController@amount');