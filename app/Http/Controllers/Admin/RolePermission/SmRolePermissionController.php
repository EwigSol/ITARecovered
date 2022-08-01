<?php

namespace App\Http\Controllers\Admin\RolePermission;
use App\Role;
use App\SmModule;
use App\ApiBaseMethod;
use App\SmRolePermission;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Modules\RolePermission\Entities\InfixRole;
use DB;
use Modules\RolePermission\Entities\InfixPermissionAssign;
use Modules\RolePermission\Entities\InfixModuleStudentParentInfo;
use App\SmSchool;
use App\Models\District; 
use Modules\RolePermission\Entities\InfixModuleInfo;
class SmRolePermissionController extends Controller
{
    public function __construct()
	{
        $this->middleware('PM');
        // User::checkAuth();
	}



    public function assignPermission(Request $request,$id){
 
		try{
			// $role = InfixRole::find($id);
			 if (checkAdmin()) {
				$role = InfixRole::find($id);
			}else{
				$role = InfixRole::where('id',$id)->where('school_id',Auth::user()->school_id)->first();
			}
			$modulesRole = SmModule::where('active_status', 1)->where('school_id',Auth::user()->school_id)->get();
			$role_permissions = SmRolePermission::where('role_id', $id)->where('school_id',Auth::user()->school_id)->get();
			$already_assigned = [];
			foreach($role_permissions as $role_permission){
				$already_assigned[] = $role_permission->module_link_id;
			}

			if (ApiBaseMethod::checkUrl($request->fullUrl())) {
				$data = [];
				$data['role'] = $role;
				$data['modules'] = $modulesRole->toArray();
				$data['already_assigned'] = $already_assigned;
				return ApiBaseMethod::sendResponse($data, null);
			}
			return view('backEnd.systemSettings.role.assign_role_permission', compact('role', 'modulesRole', 'already_assigned'));
		}catch (\Exception $e) {
		   Toastr::error('Operation Failed', 'Failed');
		   return redirect()->back();
		}
    }


    public function rolePermissionStore(Request $request){ 
		try{
			
			// SmRolePermission::where('role_id', $request->role_id)->delete();
			 if (checkAdmin()) {
				SmRolePermission::where('role_id', $request->role_id)->delete();
			}else{
				SmRolePermission::where('role_id', $request->role_id)->where('school_id',Auth::user()->school_id)->delete();
			}

			if(isset($request->permissions)){
				foreach($request->permissions as $permission){
					$role_permission = new SmRolePermission();
					$role_permission->role_id = $request->role_id;
					$role_permission->module_link_id = $permission;
					$role_permission->school_id = Auth::user()->school_id;
					$role_permission->save();
				}
			}
			if (ApiBaseMethod::checkUrl($request->fullUrl())) {
				return ApiBaseMethod::sendResponse(null, 'Role permission has been assigned successfully');
			}
			Toastr::success('Role permission has been assigned successfully', 'Success');
            return redirect()->back();
			// return redirect('role')->with('message-success-delete', 'Role permission has been assigned successfully');
		}catch (\Exception $e) {
		   Toastr::error('Operation Failed', 'Failed');
		   return redirect()->back();
		}
    }
    public function assignPermissionDistrict($id,$school_district_id){
    	 try {
            $sm_stuff =$school_ids= '';
           
            
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            $role = InfixRole::where('is_saas',0)->where('id',$id)->first(); 
            if ($id == 10) {
            $assign_modules = InfixPermissionAssign::where('district_idFk',$school_district_id)->where('role_id', $id)->get(); 
            }else{
            $assign_modules = InfixPermissionAssign::where('school_id',$school_district_id)->where('role_id', $id)->get();	
            } 
           
            $sm_stuff = SmSchool::where('active_status',1);
            
            $sm_stuff=$sm_stuff->get(); 
            $districts = District::get();
            $already_assigned = [];
            foreach ($assign_modules as $assign_module) {
                $already_assigned[] = $assign_module->module_id;
            }
            

            if ($id != 2 && $id != 3) {

                $all_modules = InfixModuleInfo::query();

                if (moduleStatusCheck('Zoom')== FALSE) {
                    $all_modules->where('module_id','!=',22);
                } 
                if (moduleStatusCheck('ParentRegistration')== FALSE) {
                    $all_modules->where('module_id','!=',21);
                } 
            
                if (moduleStatusCheck('Jitsi')== FALSE) {
                    $all_modules->where('module_id','!=',30);
                }

                if (moduleStatusCheck('Lesson')== FALSE) {
                    $all_modules->where('module_id','!=',29);
                }
                 if (moduleStatusCheck('BBB')== FALSE) {
                    $all_modules->where('module_id','!=',33);
                }
                 if (moduleStatusCheck('OnlineExam')== FALSE) {
                    $all_modules->where('module_id','!=',32);
                }

                if (moduleStatusCheck('Saas')== True) {
                    $all_modules->whereNotIn('module_id',[19,20]);
                }


                if (moduleStatusCheck('OnlineExam')== FALSE) {
                    $all_modules->where('module_id','!=',101);
                } 


                if (moduleStatusCheck('OnlineExam')== FALSE) {
                    $all_modules->where('module_id','!=',101);
                } 
                if (moduleStatusCheck('OnlineExam')== True) {
                    $all_modules->where('module_id','!=',51);
                }

                $all_modules =  $all_modules->where('is_saas',0)->where('parent_id',0)->where('active_status', 1)->get();
                // $all_modules = InfixModuleInfo::where('is_saas',0)->where('active_status', 1)->where('module_id','!=',22)->get();


                $all_modules = $all_modules->groupBy('module_id');
 

                return view('rolepermission::role_permission', compact('role', 'all_modules', 'already_assigned','sm_stuff','districts'));
            } else {

                if ($id == 2) {
                    $user_type = 1;
                } else {
                    $user_type = 2;
                }

                $all_modules=InfixModuleStudentParentInfo::query();

                if (moduleStatusCheck('Zoom')== FALSE) {
                    $all_modules->where('module_id','!=',2022);
                } 
          
            
                if (moduleStatusCheck('Jitsi')== FALSE) {
                    $all_modules->where('module_id','!=',2030);
                }

                 if (moduleStatusCheck('BBB')== FALSE) {
                    $all_modules->where('module_id','!=',2033);
                }
                 if (moduleStatusCheck('OnlineExam')== FALSE) {
                    $all_modules->where('module_id','!=',101);
                }
                if (moduleStatusCheck('OnlineExam')== True) {
                    $all_modules->where('module_id','!=',10);
                }
                 if (moduleStatusCheck('OnlineExam')== true) {
                    $all_modules->where('module_id','!=',100);
                }

                $all_modules =$all_modules->where('active_status', 1)->where('user_type', $user_type)->where('parent_id', 0)->get();
               
                return view('rolepermission::role_permission_student', compact('role', 'all_modules', 'already_assigned', 'user_type','sm_stuff','school_ids'));
            }
        } catch (\Exception $e) {
            Toastr::error($e->getMessage(), 'Failed');
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
}
