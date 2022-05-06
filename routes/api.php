<?php

use App\Models\PartNumber;
use App\Models\Returns;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('returns', function () {
    return  DB::select('CALL `SelectReturns`()');
});

Route::get('tracking/{tracking}', function ($tracking) {
    return  response()->json([
        'returnValue' => Returns::where('track_number', $tracking)->count()
    ]);
});

Route::get('dashboard', function () {
    $getCurrentDate = date('m/d/Y');

    $searchByDate = isset(request()->date) ? date_format(date_create(request()->date), 'm/d/Y')  : $getCurrentDate;
    return  response()->json([
        'generalSummary' => DB::select('CALL `elpdashboarGeneral`()'),
        'dailySummary' =>  DB::select("CALL elpdasboard('$searchByDate')")
    ]);
});

Route::get('photos', function () {
    $partnumber = PartNumber::findOrFail(request()->partNumber_id)->partNumberPhotos;

    return  response()->json([
        'photos' => $partnumber
    ]);
});

Route::post('return/partnumbers', function () {

    $partnumber = PartNumber::create(request()->all());


    if (request()->totalImages > 0) {
        for ($i = 0; $i < request()->totalImages; $i++) {
            if (request()->hasFile("picture$i")) {
                $file = request()->file("picture$i");
                $name = $file->getClientOriginalName();
                $fileName = request()->returns_id . "-" . $partnumber->id . "-" . $name;
                request()->file("picture$i")->storeAs("public/PartNumbers", $fileName);
                $partnumber->partNumberPhotos()->create([
                    "image" => $fileName
                ]);
            }
        }
    }
});

Route::post('return', function () {

    $validator = Validator::make(request()->all(), [
        'track_number' => ['required', Rule::unique('returns', 'track_number')]
    ]);

    if ($validator->fails()) {
        return response()->json([
            'returnValue' => 0,
            'validator' => $validator->errors()
        ], 422);
    }

    $return = Returns::create([
        'user_id' => request()->lastUpdateBy,
        'returnstatus_id' => 1,
        'track_number' => request()->track_number,
        'created_by' => request()->lastUpdateBy,
    ]);

    return response()->json([
        'returnValue' => $return->id ?? 0,
    ]);
});

Route::get('user', function (Request $request) {

    $user = User::where('username', $request->username)->firstOrFail()->makeVisible('password');
    if (Hash::check($request->password, $user->password)) {
        return $user;
    }

    return null;
});
