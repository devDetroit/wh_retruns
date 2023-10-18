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

    public function selectTrackingNumbers()
    {
        return DB::select('SELECT id, track_number as data FROM returns WHERE track_number LIKE ?', ['%' . request()->tracking . '%']);
    }
    public function selectOrders()
    {
        return DB::select('SELECT id, order_number as data FROM returns WHERE order_number LIKE ?', ['%' . request()->tracking . '%']);
    }
    public function index()
    {
        return  DB::select("CALL SelectReturns()");
    }
public function selectTrackingsReport(){
    return  DB::select("CALL SelectReturnsReport(?,?)", [request()->startDate, request()->endDate]);

}
    public function returnsCondition()
    {
        $getCurrentDate = date('m/d/Y');

        $startSearchDate = isset(request()->startDate) ? (date_format(date_create(request()->startDate), 'm/d/Y')) : $getCurrentDate;
        $endSearchDate = isset(request()->endDate) ? date_format(date_create(request()->endDate), 'm/d/Y') : $getCurrentDate;

        return DB::select("CALL returnsConditionReport('$startSearchDate', '$endSearchDate')");
    }

    public function loginUser()
    {
        $user = User::where('username', request()->username)->firstOrFail()->makeVisible('password');

        if (Hash::check(request()->password, $user->password)) {
            return $user;
        }

        return null;
    }

    public function getELPProductionDashboard()
    {
        $getCurrentDate = date('m/d/Y');

        $searchByDate = isset(request()->date) ? date_format(date_create(request()->date), 'm/d/Y')  : $getCurrentDate;
        return  response()->json([
            'generalSummary' => DB::select('CALL `elpDashboardGeneral`()'),
            'dailySummary' =>  DB::select("CALL elpDashboard('$searchByDate')")
        ]);
    }

    public function getJRZProductionDashboard()
    {
        $getCurrentDate = date('m/d/Y');

        $searchByDate = isset(request()->date) ? date_format(date_create(request()->date), 'm/d/Y')  : $getCurrentDate;
        return  response()->json([
            'generalSummary' => DB::select('CALL `jrzDashboardGeneral`()'),
            'dailySummary' =>  DB::select("CALL jrzDashboard('$searchByDate')"),
            'storeSummary' => DB::select('CALL `storesDashboardTotal`()'),
            'dailyStoreSummary' => DB::select("CALL storesDashboard('$searchByDate')"),
            'dailyPNStatusSummary' => DB::select("CALL pnStatusDashboard('$searchByDate')")
        ]);
    }

    public function getPhotosPerPartNumber()
    {
        $partnumber = PartNumber::findOrFail(request()->partNumber_id)->photos;

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
                    $partnumber->photos()->create([
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
