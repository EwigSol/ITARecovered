<?php

namespace App\Http\Controllers\Admin\SchoolRegisteration;

use App\User;
use App\SmExam;
use ZipArchive;
use App\SmClass;
use App\SmStaff;
use App\SmStyle;
use App\Language;
use App\SmBackup;
use App\SmModule;
use App\SmSchool;
use App\SmCountry;
use App\SmSection;
use App\SmSubject;
use App\SmWeekend;
use App\SmCurrency;
use App\SmExamType;
use App\SmLanguage;
use App\SmTimeZone;
use App\SmDateFormat;
use App\SmSmsGateway;
use App\ApiBaseMethod;
use App\SmBankAccount;
use App\SmAcademicYear;
use App\SmEmailSetting;
use App\SmAssignSubject;
use App\SmSystemVersion;
use App\SmChartOfAccount;
use App\SmLanguagePhrase;
use App\SmPaymentMethhod;
use App\SmGeneralSettings;
use App\Traits\UploadTheme;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\SmPaymentGatewaySetting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Nwidart\Modules\Facades\Module;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;
use App\Scopes\ActiveStatusSchoolScope;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Modules\RolePermission\Entities\InfixModuleInfo;
use Modules\RolePermission\Entities\InfixModuleStudentParentInfo;
use App\Models\District; 
use App\Http\Requests\Admin\GeneralSettings\SmEmailSettingsRequest;
use App\Http\Requests\Admin\GeneralSettings\SmGeneralSettingsRequest;

use App\Http\Requests\Admin\AdminSection\SmAdmissionQueryRequest;
use App\Http\Requests\Admin\AdminSection\SmAdmissionQuerySearchRequest;
use App\Http\Requests\Admin\AdminSection\SmAdmissionQueryFollowUpRequest;
use App\Http\Requests\Admin\GeneralSettings\SmSchoolRequest;
 
use App\SmSetupAdmin;
use App\SmAdmissionQuery;
class RegisterationController extends Controller
{
    public function __construct()
    {
        $this->middleware('PM');
        if (empty(Auth::user()->id)) {
            return redirect('login');
        }
    } 
    public function index()
    {
        try {
            $role_id = auth()->user()->role_id;

            if ($role_id == 10) {
                $smstaff = DB::table('sm_staffs')->where('user_id',auth()->user()->id)->get();
                $registration_query =  SmSchool::where('district_idFk',$smstaff[0]->district_idFk)->get();  
            }else{
                $registration_query =  SmSchool::get();  
            }
              
            $classes = SmClass::get();
            $references = SmSetupAdmin::where('type', 4)->get();
            $sources = SmSetupAdmin::where('type', 3)->get();
            return view('backEnd.admin.school.index', compact('registration_query', 'references', 'classes', 'sources'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function createSchool(Request $request,$id=false)
    { 
        try {
            $editData = SmSchool::leftjoin('districts','district_idFk','district_id')->where('sm_schools.id',$id)->first();   
            $role_id = auth()->user()->role_id; 
            $school =  SmSchool::where('id',auth()->user()->school_id)->get();  
            // if ($role_id == 11 || $role_id == 10) { 
            // $districts = District::where('district_id',$school[0]->district_idFk)->get();
            // }else{
                $districts = District::get();  
            // } 
            $session_ids = SmAcademicYear::where('school_id', Auth::user()->school_id)->where('active_status', 1)->get();
            $dateFormats = SmDateFormat::where('active_status', 1)->get();
            $languages = SmLanguage::all();
            $countries = SmCountry::select('currency')->groupBy('currency')->get();
            $currencies = SmCurrency::where('school_id',auth()->user()->school_id)->get();
            $academic_years = SmAcademicYear::where('school_id', Auth::user()->school_id)->get();
            $time_zones = SmTimeZone::all();
            $weekends = SmWeekend::where('school_id', Auth::user()->school_id)->get();

            $sell_heads = SmChartOfAccount::where('active_status', '=', 1)
                ->where('school_id', Auth::user()->school_id)
                ->where('type', 'I')
                ->get();  
            // $districts = District::get();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $data = [];
                $data['editData'] = $editData;
                $data['session_ids'] = $session_ids->toArray();
                $data['dateFormats'] = $dateFormats->toArray();
                $data['languages'] = $languages->toArray();
                $data['countries'] = $countries->toArray();
                $data['currencies'] = $currencies->toArray();
                $data['academic_years'] = $academic_years->toArray();
                return ApiBaseMethod::sendResponse($data, 'apply leave');
            }
            return view('backEnd.admin.school.registration', compact('editData', 'session_ids', 'dateFormats', 'languages', 'countries', 'currencies', 'academic_years', 'time_zones', 'weekends', 'sell_heads','districts','role_id'));
        } catch (\Exception $e) {

            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function store(SmSchoolRequest $request)
    {
         
        try {
            
          

            $school = new SmSchool();
            
            $school->school_name = $request->school_name;
            $school->school_code = $request->school_code;
            $school->address = $request->address;
            $school->phone = $request->phone;
            $school->email = $request->email;
            $school->district_idFk = $request->district_name;

            $school->save();

            
            
            Toastr::success('Operation successful', 'Success');
            return \Redirect::route('school-view', $school->id);
        } catch (\Exception $e) {
            dd($e->getMessage());
         
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function View(Request $request,$id=false)
    { 
         
        try {


            $editData = SmSchool::leftjoin('districts','district_idFk','district_id')->where('sm_schools.id',$id)->first();   
            $session = SmGeneralSettings::join('sm_academic_years', 'sm_academic_years.id', '=', 'sm_general_settings.session_id')->find($id);   
 
            // return $editData;
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {

                return ApiBaseMethod::sendResponse($editData, null);
            }
            return view('backEnd.admin.school.view', compact('editData', 'session','id'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function SchoolLogo(Request $request ,$id=false)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'main_school_logo' => "sometimes|nullable|mimes:jpg,jpeg,png|max:50000",
            'main_school_favicon' => "sometimes|nullable|mimes:jpg,jpeg,png|max:50000",
        ]);

        if ($validator->fails()) {
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendError('Validation Error.', $validator->errors());
            }
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // for upload School Logo
            if ($request->file('main_school_logo') != "") {
                $maxFileSize = SmGeneralSettings::first('file_size')->file_size;
                $file = $request->file('main_school_logo');
                $fileSize = filesize($file);
                $fileSizeKb = ($fileSize / 1000000);
                if ($fileSizeKb >= $maxFileSize) {
                    Toastr::error('Max upload file size ' . $maxFileSize . ' Mb is set in system', 'Failed');
                    return redirect()->back();
                }
                $main_school_logo = "";
                $file = $request->file('main_school_logo');
                $main_school_logo = md5($file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();
                $file->move('public/uploads/settings/', $main_school_logo);
                $main_school_logo = 'public/uploads/settings/' . $main_school_logo;
                $generalSettData = SmGeneralSettings::where('id',$id)->first();
                $generalSettData->logo = $main_school_logo;
                $results = $generalSettData->update();

                if ($results) {
                    session()->forget('school_config');
                    $school_config = SmGeneralSettings::where('id',$id)->first();
                    session()->put('school_config', $school_config);

                    session()->forget('generalSetting');
                    session()->put('generalSetting', $generalSettData);
                }
            } // for upload School favicon
            else if ($request->file('main_school_favicon') != "") {
                $maxFileSize = SmGeneralSettings::first('file_size')->file_size;
                $file = $request->file('main_school_favicon');
                $fileSize = filesize($file);
                $fileSizeKb = ($fileSize / 1000000);
                if ($fileSizeKb >= $maxFileSize) {
                    Toastr::error('Max upload file size ' . $maxFileSize . ' Mb is set in system', 'Failed');
                    return redirect()->back();
                }
                $main_school_favicon = "";
                $file = $request->file('main_school_favicon');
                $main_school_favicon = md5($file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();
                $file->move('public/uploads/settings/', $main_school_favicon);
                $main_school_favicon = 'public/uploads/settings/' . $main_school_favicon;
                $generalSettData = SmGeneralSettings::where('id',$id)->first();
                $generalSettData->favicon = $main_school_favicon;
                $results = $generalSettData->update();

                if ($results) {
                    session()->forget('school_config');
                    $school_config = SmGeneralSettings::where('id',$id)->first();
                    session()->put('school_config', $school_config);
                    session()->forget('generalSetting');
                    session()->put('generalSetting', $generalSettData);
                }


            } else {
                if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                    return ApiBaseMethod::sendError('No change applied, please try again');
                }
                Toastr::error('Operation Failed', 'Failed');
                return redirect()->back();
            }
            if ($results) {
                if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                    return ApiBaseMethod::sendResponse(null, 'Logo has been updated successfully');
                }
                Toastr::success('Operation successful', 'Success');
                return redirect()->back();
            } else {
                if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                    return ApiBaseMethod::sendError('Something went wrong, please try again');
                }
                Toastr::error('Operation Failed', 'Failed');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function edit(SmSchoolRequest $request,$id=false)
    {   
        try {
 
             $school = SmSchool::find($id); 
            $school->school_name = $request->school_name;
            $school->school_code = $request->school_code;
            $school->address = $request->address;
            $school->phone = $request->phone;
            $school->email = $request->email;
            $school->district_idFk = $request->district_name;
            $school->save();

            
 
            Toastr::success('Operation successful', 'Success');
            return \Redirect::route('school-view', $id);
        } catch (\Exception $e) {
            dd($e->getMessage());
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function delete(Request $request)
    {
        DB::beginTransaction();
        try {
            $admission_query = SmSchool::find($request->id);
             
            $admission_query->delete();
            DB::commit();
            Toastr::success('Operation successful', 'Success');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
}
