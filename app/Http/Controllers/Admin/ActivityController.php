<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\AlertMail;
use App\Models\Company;
use App\Models\Section;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ActivityController extends Controller
{
    public function dashboard()
    {
        $data['seller'] = User::where('user_type', 'Business')->count();
        $data['guest'] = User::where('user_type', 'Guest')->count();
        $data['total'] = User::whereNot('user_type', 'Admin')->count();
        $data['companies'] = Company::count();
        return response(['status' => 'success', 'code' => 200, 'user' => null, 'data' => $data, 'message' => 'Dashboard data'], 200);
    }

    public function getAllUser()
    {
        $data = User::whereNot('user_type', 'Admin')->get();
        if ($data->isEmpty()) {
            return response(['status' => 'error', 'code' => 404, 'user' => null, 'data' => null, 'message' => 'No user found'], 404);
        }
        return response(['status' => 'success', 'code' => 200, 'user' => null, 'data' => $data, 'message' => 'All users'], 200);
    }

    public function activeBlock(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|numeric|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response(['status' => 'error', 'code' => 403, 'user' => null, 'data' => null, 'error' => $validator->messages()->all(), 'message' => 'Missing or wrong params'], 403);
        }
        $data = User::where('id', $request->id)->first();
        if ($data) {
            if ($data['status'] == 0) {
                User::where('id', $request->id)->update(['status' => 1]);
            }
            if ($data['status'] == 1) {
                User::where('id', $request->id)->update(['status' => 0]);
            }

            return response(['status' => 'success', 'code' => 200, 'message' => 'Status Updated Successfully'], 200);
        }

        return response(['status' => 'error', 'code' => 404, 'message' => 'User Not Found']);
    }

    public function alertMail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'message' => 'required',

        ]);
        if ($validator->fails()) {
            return response(['status' => 'error', 'code' => 422, 'message' => 'missing or wrong params', 'errors' => $validator->errors()->all()], 422);
        }
        $users = User::whereNot('user_type', 'Admin')->get;

        $data = [
            'message' => $request->message,

        ];
        if ($users->count() == 0) {
            return response(['status' => 'error', 'code' => 404, 'message' => 'No user found'], 404);
        } else {
            foreach ($users as $user) {
                Mail::to($user->email)->send(new AlertMail($data));
            }
            return response(['status' => 'success', 'code' => 200, 'message' => "Email is sent successfully."], 200);
        }
    }

    public function addCategory(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',

        ]);
        if ($validator->fails()) {
            return response(['status' => 'error', 'code' => 422, 'message' => 'missing or wrong params', 'errors' => $validator->errors()->all()], 422);
        }

        if (isset($request->image)) {
            if ($request->hasFile('image')) {
                $imageName = rand() . time() . '.' . $request->image->extension();

                $request->image->move(public_path('Category_Images'), $imageName);
                $imageName = asset('Category_Images') . '/' . $imageName;
            } else {
                return response(['status' => 'unsuccessful', 'code' => 422, 'message' => 'Image should be file'], 422);
            }
        } else {
            $imageName = null;
        }
        $data = [
            'name' => $request->name,
            'user_id' => auth()->user()->id,
            'status ' => 1,
            'image' => $imageName,
        ];
        $category = Service::create($data);
        if (isset($category)) {
            return response(['status' => 'success', 'code' => 200, 'message' => "Category added successfully."], 200);
        } else {
            return response(['status' => 'error', 'code' => 422, 'message' => "Category not added."], 422);
        }
    }
    public function getCategory()
    {
        $data = Service::all();
        if ($data->isEmpty()) {
            return response(['status' => 'error', 'code' => 404, 'message' => 'No category found'], 404);
        }
        return response(['status' => 'success', 'code' => 200, 'data' => $data, 'message' => 'All category'], 200);
    }
    public function deleteCategory(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|numeric|exists:services,id',
        ]);

        if ($validator->fails()) {
            return response(['status' => 'error', 'code' => 403, 'message' => 'missing or wrong params', 'errors' => $validator->errors()->all()], 403);
        }
        $data = Service::where('id', $request->id)->first();
        if ($data) {
            Service::where('id', $request->id)->delete();
            return response(['status' => 'success', 'code' => 200, 'message' => 'Category Deleted Successfully'], 200);
        }
        return response(['status' => 'error', 'code' => 404, 'message' => 'Category Not Found'], 404);
    }

    public function updateCategory(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|numeric|exists:services,id',
        ]);

        if ($validator->fails()) {
            return response(['status' => 'error', 'code' => 403, 'message' => 'missing or wrong params', 'errors' => $validator->errors()->all()], 403);
        }
        $data = Service::where('id', $request->id)->first();
        if ($data) {
            if (isset($request->image)) {
                if ($request->hasFile('image')) {
                    $imageName = rand() . time() . '.' . $request->image->extension();

                    $request->image->move(public_path('Category_Images'), $imageName);
                    $imageName = asset('Category_Images') . '/' . $imageName;
                } else {
                    return response(['status' => 'unsuccessful', 'code' => 422, 'message' => 'Image should be file'], 422);
                }
            } else {
                $imageName = $data->image;
            }
            $data = [
                'name' => $request->name ?? $data->name,
                // 'status ' => $request->status,
                'image' => $imageName ?? $data->image,
            ];
            $data=Service::where('id', $request->id)->update($data);
            if ($data != 1) {
                return response(['status' => 'error', 'code' => 422, 'message' => 'Category Not Updated'], 422);
            }
            return response(['status' => 'success', 'code' => 200, 'message' => 'Category Updated Successfully'], 200);
        } else {
            return response(['status' => 'error', 'code' => 404, 'message' => 'Category Not Found'], 404);
        }
    }
    public function updateSection(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'id' => 'required',
        ]);

        if ($validator->fails()) {
            return response(['status' => 'error', 'code' => 403, 'message' => 'missing or wrong params', 'errors' => $validator->errors()->all()], 403);
        }
        $data = [
            'name' => $request->name,
        ];
        $section = Section::where('id',$request->id)->first();
        if ($section) {
            $updateSection = Section::where('id', $request->id)->update($data);
           
            if ($updateSection == 1) {
            return response(['status' => 'success', 'code' => 200, 'message' => 'Section Updated Successfully'], 200);
            }else {
               
                return response(['status' => 'error', 'code' => 422, 'message' => 'Section Not Updated'], 422);
            }
        } else {
            $updateSection = Section::create($data);
            return response(['status' => 'success', 'code' => 200, 'message' => 'Section Created Successfully'], 200);
        }
    }

    public function getSection()
    {
        $data = Section::first();
        if (!$data) {
            return response(['status' => 'error', 'code' => 404, 'message' => 'No section found'], 404);
        } else {
            return response(['status' => 'success', 'code' => 200, 'data' => $data, 'message' => 'All section'], 200);
        }
    }

   public function selectCompany(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|numeric|exists:companies,id',
        ]);

        if ($validator->fails()) {
            return response(['status' => 'error', 'code' => 403, 'message' => 'missing or wrong params', 'errors' => $validator->errors()->all()], 403);
        }
        $company = Company::where('id', $request->id)->first();
        if ($company) {
           if ($company['is_selected'] == 0) {
           $update =Company::where('id', $request->id)->update(['is_selected' => 1]);
            }
            if ($company['is_selected'] == 1) {
           $update =Company::where('id', $request->id)->update(['is_selected' => 0]);
            }
            if ($update != 1) {
                return response(['status' => 'error', 'code' => 422, 'message' => 'Company Not Selected'], 422);
            }else{
                return response(['status' => 'success', 'code' => 200, 'message' => 'Company Selection Updated Successfully'], 200);
            }
        }else{
            return response(['status' => 'error', 'code' => 404, 'message' => 'Company Not Found'], 404);
        }
    }
    
    public function eventPermission(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|numeric|exists:companies,id',
            'event_permission' => 'required',
        ]);

        if ($validator->fails()) {
            return response(['status' => 'error', 'code' => 403, 'message' => 'missing or wrong params', 'errors' => $validator->errors()->all()], 403);
        }
        $company = Company::where('id', $request->id)->first();
        if ($company) {
            $update = Company::where('id', $request->id)->update(['event_permission' => $request->event_permission]);
            if ($update != 1) {
                return response(['status' => 'error', 'code' => 422, 'message' => 'Event Permission Not Updated'], 422);
            } else {
                return response(['status' => 'success', 'code' => 200, 'message' => 'Event Permission Updated Successfully'], 200);
            }
        } else {
            return response(['status' => 'error', 'code' => 404, 'message' => 'Company Not Found'], 404);
        }
    }
}
