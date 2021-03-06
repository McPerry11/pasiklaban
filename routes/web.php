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

use App\Common;
use App\User;
use Illuminate\Http\Request;

Route::get('/ ', function () {
  return redirect('/register');
});

Route::middleware('auth')->group(function () {
  Route::get('dashboard', 'DashboardController@showAll')->name('dashboard');

  Route::get('scanner', function () {
    return view('scanner');
  });
  Route::get('logged', function () {
    return view('scanner', ['logged' => true]);
  });
  Route::get('raffle', 'DashboardController@raffle');
  Route::post('raffle', 'DashboardController@raffleWinner');
  Route::post('scanner', 'DashboardController@scanner');
  Route::get('loggedlist', 'DashboardController@loggedList');
  Route::get('loggedlist/export', 'ReportController@loggedList');
  Route::get('logs', 'LogController@show')->name('logs');
  Route::get('report', 'ReportController@show');;

  Route::post('/user/delete', function (Request $request) {
    $code     = $request->code;
    $password = $request->password;

    if (!$code) {
      abort(404);
    } else if (!\Hash::check($password, \Auth::user()->password)) {
      abort(401);
    }

    $reference_number = Common::decrypt($code);

    try {
      $user = User::where('reference_number', $reference_number)->first();

      if (!$user) {
        abort(404);
      }

      $user->delete();

      Common::createLog('Deleted User: ' . $user->id);

      return json_encode(['success' => true]);
    } catch (QueryException $e) {
      return json_encode(['success' => false, 'error' => $e]);
    }
  });

  Route::get('/user/{id}', function (Request $request) {
    return json_encode(\DB::table('users')->where('id', $request->id)->first());
  });

  Route::get('qrdisplay', 'MailController@display');

  Route::post('setdisqualified', function (Request $request) {
    $user               = User::find($request->id);
    $user->disqualified = $request->status;
    $user->save();
  });
});

Route::get('/login', 'LoginController@show')->name('login');
Route::post('/login', 'LoginController@process');

Route::get('logout', 'LoginController@logout');

Route::get('/register', 'RegisterController@create')->name('register');
Route::post('/register', 'RegisterController@store');

Route::get('/mailer/steps', 'MailController@sendSteps');
Route::post('/mailer/ticket', 'MailController@sendTicket');
Route::get('/mailer', 'MailController@display');

Route::get('/upload', 'UploadController@create');
Route::post('/upload', 'UploadController@store');

Route::get('/upload/success', 'UploadController@showSuccess');
