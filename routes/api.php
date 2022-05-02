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
    return  Returns::all();
});

Route::get('tracking/{tracking}', function ($tracking) {
    return  response()->json([
        'returnValue' => Returns::where('track_number', $tracking)->count()
    ]);
});

Route::get('dashboard', function () {
    $getCurrentDate = date('m/d/Y');
    return  response()->json([
        'generalSummary' => DB::select('CALL elpdashboarGeneral()'),
        'dailySummary' =>  DB::select("CALL elpdasboard('$getCurrentDate')")
    ]);
});

Route::post('return/partnumbers', function () {

    PartNumber::create(request()->all());

    if (request()->hasFile("picture")) {
        $file = request()->file("picture");
        $name = $file->getClientOriginalName();
        request()->file("picture")->storeAs("public/PartNumbers", request()->returns_id . "-" . $name);
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
