<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\BookingMail;
use App\Models\Appointment;
use App\Models\Company;
use App\Models\Event;
use App\Models\Review;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class GuestController extends Controller
{
    public function reviewCompany(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'companyId' => 'required',
            // 'comment' => 'required',
            'stars' => 'required|numeric|min:1|max:5',
        ]);
        if ($validator->fails()) {
            return response(['status' => 'error', 'code' => 422, 'message' => 'missing or wrong params', 'errors' => $validator->errors()->all()], 422);
        }
        $checkVendor = Company::where('id', $request->companyId)->first();
        if (!$checkVendor) {
            return response(['status' => 'error', 'code' => 403, 'message' => 'Company not found'], 403);
        }
        $user = auth()->user();
        $checkReviews = Review::where('userId', $user['id'])->where('companyId', $request->companyId)->get();
        if ($checkReviews->count() > 0) {
            return response(['status' => 'error', 'code' => 403, 'message' => 'already reviewed']);
        }
        Review::create(
            [
                'userId' => $user['id'],
                'companyId' => $request->companyId,
                'comment' => $request->comment,
                'stars' => $request->stars ?  $request->stars : 0,
            ]
        );
        $ratings = $checkVendor['rating'];
        if ($ratings == 0) {
            Company::where('id', $request->companyId)->update(['rating' => $request->stars]);
        } else {
            $getTotalRatings = Review::where('companyId', $request->companyId)->get();
            $totalRatings = $getTotalRatings->count() * 5;
            $total = Review::where('companyId', $request->companyId)->sum('stars');
            $getmultiply = $total * 5;
            $average = $getmultiply / $totalRatings;
            Company::where('id', $request->companyId)->update(['rating' => $average]);
        }

        return response(['status' => 'success', 'code' => 200, 'message' => 'Vendor reviewed successfully'], 200);
    }

    public function getService(Request $request)
    {
        $service = Service::get();
        if ($service->count() > 0) {
            return response(['status' => 'success', 'code' => 200, 'data' => $service, 'message' => 'Get Services Successfully'], 200);
        } else {
            return response(['status' => 'error', 'code' => 403, 'data' => null, 'message' => 'Get Services Failed']);
        }
    }
    // public function createUserAppointment(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'id' => 'required|numeric|exists:_vendors_services',
    //         'userId' => 'required|numeric|min:1',
    //         'serviceId' => 'required|numeric|min:1',
    //         'service' => 'required',
    //         'durationStart' => 'required',
    //         'durationEnd' => 'required',
    //         'catId' => 'required',
    //         'serviceType'=>'required',
    //         // 'phoneNumber'=>'required',
    //         // 'status' => 'required|numeric|min:1|max:2',

    //     ]);
    //     if ($validator->fails()) {
    //         return response(['status' => 'error', 'code' => 422, 'message' => 'missing or wrong params', 'errors' => $validator->errors()->all()], 422);
    //     }
    //     $user = auth()->user();
    //     if ($user['userType'] == "User") {
    //         // $getVendorService = VendorService::where('id', $request->id)->first();
    //         $checkAppointment = Appointment::where('vendorId', $getVendorService['vendorId'])->where('status', 0)->orWhere('durationStart', 'LIKE', '%' . $request->durationStart . '%')->get();
    //         if ($checkAppointment->count() > 0) {
    //             $availablePersons = $getVendorService['personAvailble'];
    //             $getSlotAppointments = $checkAppointment->count();
    //             $data = [];
    //             $z = 0;
    //             $y = 0;
    //             for ($x = 0; $x < $checkAppointment->count(); $x++) {
    //                 $data[$x]['durationStart'] = $checkAppointment[$x]['durationStart'];
    //                 $data[$x]['durationEnd'] = $checkAppointment[$x]['durationEnd'];

    //                 if ($data[$x]['durationStart'] <= $request->durationStart && $data[$x]['durationEnd'] >= $request->durationStart) {
    //                     $z = $z + 1;


    //                     if ($z >= $availablePersons) {
    //                         // echo 'yes1';

    //                         return response(['status' => 'error', 'code' => 403, 'message' => 'An appointment already found in this slot of time'], 403);
    //                     }
    //                 }

    //                 if ($data[$x]['durationEnd'] >= $request->durationEnd && $data[$x]['durationStart'] <= $request->durationEnd) {
    //                     $y = $y + 1;
    //                     if ($y >= $availablePersons) {

    //                         return response(['status' => 'error', 'code' => 403, 'message' => 'An appointment already found in this slot of time'], 403);
    //                     }
    //                 }
    //             }
    //         }

    //         Appointment::create([
    //             'vendorId' =>  $getVendorService['vendorId'],
    //             'userId' => $user['id'],
    //             'serviceId' => $request->id,
    //             'service' => $request->service,
    //             'serviceType' => $request->serviceType,
    //             'durationStart' => $request->durationStart,
    //             'durationEnd' => $request->durationEnd,
    //             'catId' => $request->catId,

    //         ]);
    //         $data = [];
    //         $getAppointment = Appointment::where('userId', $user->id)->where('status', 0)->orderBy("id", "desc")->get();
    //         if ($getAppointment->count() > 0) {

    //             for ($i = 0; $i < count($getAppointment); $i++) {
    //                 $data[$i] = $getAppointment[$i];
    //                 $getDuration = VendorService::where('id', $getAppointment[$i]['serviceId'])->first();
    //                 if ($getDuration) {
    //                     $data[$i]['serviceDuration'] = $getDuration['duration'];
    //                 } else {
    //                     $data[$i]['serviceDuration'] = null;
    //                 }
    //                 $getVendor = User::where('id', $getAppointment[$i]['vendorId'])->first();
    //                 if ($getVendor->count() > 0) {
    //                     $data[$i]['vendorDetails'] = $getVendor;
    //                     if (isset($getVendor['image']) && $getVendor['image'] != null) {
    //                         $data[$i]['vendorDetails']['image'] = json_decode($getVendor['image'], true);
    //                     }
    //                 } else {
    //                     $data[$i]['vendorDetails'] = null;
    //                 }
    //             }
    //         }
    //         return response(['status' => 'success', 'code' => 200, 'appointments' => $data, 'message' => 'Appointment requested successfully'], 200);
    //     } else {
    //         return response(['status' => 'error', 'code' => 403, 'appointments' => null, 'message' => 'Only User can access this api'], 403);
    //     }
    // }

    public function deleteUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'userId' => 'required|numeric|exists:users,id',

        ]);
        if ($validator->fails()) {
            return response(['status' => 'error', 'code' => 422, 'message' => 'missing or wrong params', 'errors' => $validator->errors()->all()], 422);
        }
        $deleteUser = User::where('id', $request->userId)->update(['deleteUser' => 1]);
        if ($deleteUser == 1) {
            return response(['status' => 'success', 'code' => 200, 'message' => 'User Deleted Successfully'], 200);
        } else {
            return response(['status' => 'error', 'code' => 403, 'data' => null, 'message' => 'User Deleted Failed']);
        }
    }


    public function bookingMail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'contact' => 'required',
            'email' => 'required|email',
            'adults' => 'required',
            'date' => 'required',
            'timeAvailable' => 'required',
            'time' => 'required',
            'platform' => 'required',
        ]);
        if ($validator->fails()) {
            return response(['status' => 'error', 'code' => 422, 'message' => 'missing or wrong params', 'errors' => $validator->errors()->all()], 422);
        }
        $data = [
            'name' => $request->name,
            'contact' => $request->contact,
            'email' => $request->email,
            'adults' => $request->adults,
            'childern' => $request->childern,
            'description' => $request->description,
            'date' => $request->date,
            'timeAvailable' => $request->timeAvailable,
            'platform' => $request->platform,
            'link' => $request->link,
        ];

        Mail::to($request->email)->send(new BookingMail($data));
        return response(['status' => 'success', 'code' => 200, 'message' => "Email is sent successfully."], 200);
    }

    public function dateSearchEvent(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'start_date' => 'required',
        ]);
        if ($validator->fails()) {
            return response(['status' => 'error', 'code' => 422, 'message' => 'missing or wrong params', 'errors' => $validator->errors()->all()], 422);
        }
        // $from = date('Y-m-d', strtotime($request->start_date));
        // $to = date('Y-m-d', strtotime($request->end_date));

        $from = $request->start_date;
        $to = $request->end_date;
        $event = Event::whereBetween('date', [$from, $to])->get();
        if ($event ->count()>0) {
            return response(['status' => 'success', 'code' => 200,'event', 'message' => 'Event Get Successfully'], 200);
        } else {
            return response(['status' => 'error', 'code' => 403, 'data' => null, 'message' => 'Event Get Failed']);
        }
    }
}
