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

use App\Foundation\Regexp;

Route::group(
    [
        'as' => 'web::',
    ],
    function () {
        // 首页
        Route::get('/', 'HomeController@index')->name('index');

        // 模型内容列表
        Route::get('/entity/{entityId}/content/', 'HomeController@content')
            ->name('entity.content.list')->where(['entityId' => Regexp::RESOURCE_ID]);
        // 模型内容详情
        Route::get('/entity/{entityId}/content/{contentId}', 'ContentController@show')
            ->name('content')->where(['entityId' => Regexp::RESOURCE_ID, 'contentId' => Regexp::RESOURCE_ID]);

        // 评论列表
        Route::get('/entity/{entityId}/content/{contentId}/comment', 'CommentController@list')
            ->name('comment.list')->where(['entityId' => Regexp::RESOURCE_ID, 'contentId' => Regexp::RESOURCE_ID]);
            Route::get('/admin/users/download',function(){
        return response()->download(realpath(base_path('public')).'/upload/xuehao.xlsx', 'xuehao.xlsx');
});
        Route::get('nav','HomeController@nav')->name('nav');
        Route::get('plan','HomeController@plan')->name('plan');
        Route::get('strategy','HomeController@strategy')->name('strategy');
        Route::get('feenav','HomeController@feeNav')->name('feenav');
        Route::get('savecard','HomeController@saveCard')->name('savecard');
        Route::post('renav','HomeController@renav')->name('renav');
        Route::post('savefee','HomeController@saveFee')->name('savefee');
        Route::get('salenav','HomeController@saleNav')->name('salenav');
        Route::post('savesale','HomeController@saveFee')->name('savesale');
        Route::post('resale','HomeController@renav')->name('resale');
        Route::get('purchasenav','HomeController@purchaseNav')->name('purchasenav');
        Route::post('savepurchase','HomeController@savePurchase')->name('savepurchase');
        Route::post('repurchase','HomeController@renav')->name('repurchase');
        Route::get('flowsheet','HomeController@flowSheet')->name('flowsheet');
        Route::post('savestrategy','HomeController@saveStrategy')->name('savestrategy');
        Route::post('restrategy','HomeController@replan')->name('restrategy');
        Route::post('saveplan','HomeController@saveStrategy')->name('saveplan');
        Route::post('replan','HomeController@replan')->name('replan');
        Route::post('refee','HomeController@reFee')->name('refee');
    }
);
