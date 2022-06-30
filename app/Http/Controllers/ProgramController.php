<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ApiBaseMethod;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Admin\Hr\SmDepartmentRequest;
use App\SmHumanDepartment;
use App\Models\Program;
use App\Http\Requests\Admin\Program\ProgramRequest;
class ProgramController extends Controller
{
     

    public function index(Request $request){  
    try {
            $programs = Program::get();

            return view('backEnd.program.index', compact('programs'));
        } catch (\Exception $e) {
            // dd($e->getMessage());
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function store(ProgramRequest $request)
    {
 
        try {
            $program = new Program();
            $program->p_name = $request->program_name;
            // $program->school_id = Auth::user()->school_id;dd($program);
            $program->created_at = date('Y-m-d H:i:s');
            $result = $program->save();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                if ($result) {
                    return ApiBaseMethod::sendResponse(null, 'Program has been created successfully');
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
             $program = Program::find($id);

            $programs = Program::get();
 
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $data = [];
                $data['program'] = $program->toArray();
                $data['programs'] = $programs->toArray();
                return ApiBaseMethod::sendResponse($data, null);
            } 
            return view('backEnd.program.index', compact('program', 'programs'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function update(ProgramRequest $request, $id)
    {

        try {

            $program = Program::find($id);
            $program->p_name = $request->program_name;
            $result = $program->save();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                if ($result) {
                    return ApiBaseMethod::sendResponse(null, 'Program has been updated successfully');
                } else {
                    return ApiBaseMethod::sendError('Something went wrong, please try again.');
                }
            }
            Toastr::success('Operation successful', 'Success');
            return redirect('program_module');
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function destroy(Request $request, $id)
    {

        try {
            $tables = \App\tableList::getTableList('p_id', $id);  
            try {
                if ($tables == null) {
                    $department = Program::destroy($id);
                  
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
