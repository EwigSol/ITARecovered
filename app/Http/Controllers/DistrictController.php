<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ApiBaseMethod;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Admin\Hr\SmDepartmentRequest;
use App\SmHumanDepartment;
use App\Models\District;
use App\Models\Province;
class DistrictController extends Controller
{
     

    public function index(Request $request){  
    try {
            $districts = District::get();
            $provinces = Province::get();
            return view('backEnd.district.index', compact('districts','provinces'));
        } catch (\Exception $e) {
            // dd($e->getMessage());
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function store(Request $request)
    {
 
        try {
            $district = new District();
            $district->province_idFk = $request->province_idFk;
            $district->district_name = $request->name;
            // $district->school_id = Auth::user()->school_id;dd($district);
            $district->created_at = date('Y-m-d H:i:s');
            $result = $district->save();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                if ($result) {
                    return ApiBaseMethod::sendResponse(null, 'District has been created successfully');
                } else {
                    return ApiBaseMethod::sendError('Something went wrong, please try again.');
                }
            } 
            Toastr::success('Operation successful', 'Success');
            return redirect()->back();
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function show(Request $request, $id)
    {

        try {
             $district = District::find($id);

            $districts = District::get();
            $provinces = Province::get();
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $data = [];
                $data['district'] = $district->toArray();
                $data['districts'] = $districts->toArray();
                return ApiBaseMethod::sendResponse($data, null);
            }
            return view('backEnd.district.index', compact('district', 'districts','provinces'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function update(Request $request, $id)
    {

        try {

            $district = District::find($id);
            $district->province_idFk = $request->province_idFk;
            $district->district_name = $request->name;
            $result = $district->save();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                if ($result) {
                    return ApiBaseMethod::sendResponse(null, 'District has been updated successfully');
                } else {
                    return ApiBaseMethod::sendError('Something went wrong, please try again.');
                }
            }
            Toastr::success('Operation successful', 'Success');
            return redirect('district_module');
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function destroy(Request $request, $id)
    {

        try {
            $tables = \App\tableList::getTableList('district_id', $id);
            try {
                if ($tables == null) {
                    $department = District::destroy($id);
                  
                        if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                            if ($department) {
                                return ApiBaseMethod::sendResponse(null, 'Deleted successfully');
                            } else {
                                return ApiBaseMethod::sendError('Something went wrong, please try again');
                            }
                        } 
                        Toastr::success('Operation successful', 'Success');
                        return redirect()->back();
                   
                } else {
                    $msg = 'This data already used in  : ' . $tables . ' Please remove those data first';
                    Toastr::error($msg, 'Failed');
                    return redirect()->back();
                }
            } catch (\Illuminate\Database\QueryException $e) {

                $msg = 'This data already used in  : ' . $tables . ' Please remove those data first';
                Toastr::error($msg, 'Failed');
                return redirect()->back();
            } catch (\Exception $e) {
                Toastr::error('Operation Failed', 'Failed');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
}
