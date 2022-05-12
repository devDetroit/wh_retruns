<?php

namespace App\Http\Controllers;

use App\Models\PartNumber;
use App\Models\Returns;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class ReturnApiController extends Controller
{
    public function index()
    {
        return  DB::select('CALL `SelectReturns`()');
    }

    public function loginUser()
    {
        $user = User::where('username', request()->username)->firstOrFail()->makeVisible('password');

        if (Hash::check(request()->password, $user->password)) {
            return $user;
        }

        return null;
    }

    public function getProductionPerUserDashboard()
    {
        $getCurrentDate = date('m/d/Y');

        $searchByDate = isset(request()->date) ? date_format(date_create(request()->date), 'm/d/Y')  : $getCurrentDate;
        return  response()->json([
            'generalSummary' => DB::select('CALL `elpdashboarGeneral`()'),
            'dailySummary' =>  DB::select("CALL elpdasboard('$searchByDate')")
        ]);
    }

    public function getPhotosPerPartNumber()
    {
        $partnumber = PartNumber::findOrFail(request()->partNumber_id)->partNumberPhotos;

        return  response()->json([
            'photos' => $partnumber
        ]);
    }

    public function getTrackingNumberCount()
    {
        return  response()->json([
            'returnValue' => Returns::where('track_number', request()->tracking)->count()
        ]);
    }

    public function submitPartNumber()
    {
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
    }

    public function submitTrackingNumber()
    {
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
    }
}
