<?php

namespace App\Http\Controllers\Admin\Academics;

use App\SmSection;
use App\YearCheck;
use App\ApiBaseMethod;
use App\SmClassSection;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Admin\Academics\SectionRequest;
use App\Models\District;
use App\SmSchool;
class SmSectionController extends Controller
{
    public function __construct()
	{
        $this->middleware('PM');
        // User::checkAuth();
	}

    public function index(Request $request)
    {
        

        try {
            $user = Auth::user();
            $role_id = $user->role_id;
            $sections = SmSection::get();
            $school =  SmSchool::where('id',Auth::user()->school_id)->get();

            if ($role_id == 11 || $role_id == 10) { 
            $districts = District::where('district_id',$school[0]->district_idFk)->get();
            }else{
                $districts = District::get();
            } 
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendResponse($sections, null);
            }
            return view('backEnd.academics.section', compact('sections','districts'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function store(SectionRequest $request)
    {
       
        $academic_year=academicYears();
        if ($academic_year==null) {
            Toastr::warning('Create academic year first', 'Warning');
            return redirect()->back();
        }


        // if ($validator->fails()) {
        //     if (ApiBaseMethod::checkUrl($request->fullUrl())) {
        //         return ApiBaseMethod::sendError('Validation Error.', $validator->errors());
        //     }
        //     return redirect()->back()
        //         ->withErrors($validator)
        //         ->withInput();
        // }

        try {
            $section = new SmSection();
            $section->section_name = $request->name;
            $section->created_at = YearCheck::getYear() . '-' . date('m-d h:i:s');
            $section->district_idFk = $request->district_name;
            $section->school_id = $request->school_name;
            $section->created_at=auth()->user()->id;
            $section->academic_id = getAcademicId();
            $result = $section->save();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                if ($result) {
                    return ApiBaseMethod::sendResponse(null, 'Section has been created successfully');
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
    public function edit(Request $request, $id)
    {

        try {
            $section = SmSection::find($id);
            $sections = SmSection::get();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $data = [];
                $data['section'] = $section->toArray();
                $data['sections'] = $sections->toArray();
                return ApiBaseMethod::sendResponse($data, null);
            }

            return view('backEnd.academics.section', compact('section', 'sections'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function update(SectionRequest $request)
    {
  
        // if ($validator->fails()) {
        //     if (ApiBaseMethod::checkUrl($request->fullUrl())) {
        //         return ApiBaseMethod::sendError('Validation Error.', $validator->errors());
        //     }
        //     return redirect()->back()
        //         ->withErrors($validator)
        //         ->withInput();
        // }

        try {
          
            $section = SmSection::find($request->id);
            $section->section_name = $request->name;
            $section->district_idFk = $request->district_name;
            $section->school_id = $request->school_name;
            $result = $section->save();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                if ($result) {
                    return ApiBaseMethod::sendResponse(null, 'Section has been updated successfully');
                } else {
                    return ApiBaseMethod::sendError('Something went wrong, please try again.');
                }
            } 
            Toastr::success('Operation successful', 'Success');
            return redirect('section');
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function delete(Request $request, $id)
    {
        try {
            $tables = SmClassSection::where('section_id',$id)->first();
                if ($tables == null) {
                          SmSection::destroy($request->id);
                          Toastr::success('Operation successful', 'Success');
                          return redirect('section');
                } else {
                    $msg = 'This section already assigned with class .';
                    Toastr::warning($msg, 'Warning');
                    return redirect()->back();
                }

        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
}