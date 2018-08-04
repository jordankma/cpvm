<?php

namespace Adtech\Core\App\Http\Controllers;

use Adtech\Core\App\Models\User;
use Illuminate\Http\Request;
use Adtech\Application\Cms\Controllers\Controller as Controller;
use Adtech\Core\App\Repositories\RoleRepository;
use Adtech\Core\App\Http\Requests\RoleRequest;
use Adtech\Core\App\Models\Role;
use Yajra\Datatables\Datatables;
use Spatie\Activitylog\Models\Activity;
use Validator;
use Auth;

class RoleController extends Controller
{
    private $_user = null;
    private $messages = array(
        'name.regex' => "Sai định dạng",
        'required' => "Bắt buộc",
        'numeric'  => "Phải là số"
    );

    public function __construct(RoleRepository $roleRepository)
    {
        parent::__construct();
        $this->role = $roleRepository;
        $this->_user = Auth::user();
    }

    public function add(RoleRequest $request)
    {
        $role = new Role($request->all());
        if ($request->has('permission_locked')) {
            $role->permission_locked = 1;
        }
        $role->save();

        if ($role->role_id) {

            activity('role')
                ->performedOn($role)
                ->withProperties($request->all())
                ->log('User: :causer.email - Add Role - name: :properties.name, role_id: ' . $role->role_id);

            return redirect()->route('adtech.core.role.manage')->with('success', trans('adtech-core::messages.success.create'));
        } else {
            return redirect()->route('adtech.core.role.manage')->with('error', trans('adtech-core::messages.error.create'));
        }
    }

    public function create()
    {
        return view('ADTECH-CORE::modules.core.role.create');
    }

    public function delete(RoleRequest $request)
    {
        $role_id = $request->input('role_id');
        $role = $this->role->find($role_id);

        if ($role->permission_locked == 1) {
            return redirect()->route('adtech.core.role.manage')->with('error', trans('adtech-core::messages.error.permission'));
        }

        if (null != $role) {

            $this->role->delete($role_id);
            activity('role')
                ->performedOn($role)
                ->withProperties($request->all())
                ->log('User: :causer.email - Delete Role - role_id: :properties.role_id, name: ' . $role->name);

            return redirect()->route('adtech.core.role.manage')->with('success', trans('adtech-core::messages.success.delete'));
        } else {
            return redirect()->route('adtech.core.role.manage')->with('error', trans('adtech-core::messages.error.delete'));
        }
    }

    public function manage()
    {
        return view('ADTECH-CORE::modules.core.role.manage');
    }

    public function show(RoleRequest $request)
    {
        $role_id = $request->input('role_id');
        $role = $this->role->find($role_id);
        $data = [
            'role' => $role
        ];

        if ($role->permission_locked == 1) {
            return redirect()->route('adtech.core.role.manage')->with('error', trans('adtech-core::messages.error.permission'));
        }

        return view('ADTECH-CORE::modules.core.role.edit', $data);
    }

    public function update(RoleRequest $request)
    {
        $role_id = $request->input('role_id');

        $role = $this->role->find($role_id);
        $role->name = $request->input('name', '');
//        $role->sort = $request->input('sort');
//        $role->status = ($request->has('status')) ? 1 : 0;
//        $role->permission_locked = ($request->has('permission_locked')) ? 1 : 0;

        if ($role->save()) {

            activity('role')
                ->performedOn($role)
                ->withProperties($request->all())
                ->log('User: :causer.email - Update Role - role_id: :properties.role_id, name: :properties.name, status: :properties.status');

            return redirect()->route('adtech.core.role.manage')->with('success', trans('adtech-core::messages.success.update'));
        } else {
            return redirect()->route('adtech.core.role.show', ['role_id' => $request->input('role_id')])->with('error', trans('adtech-core::messages.error.update'));
        }
    }

    public function getModalDelete(RoleRequest $request)
    {
        $model = 'role';
        $confirm_route = $error = null;
        $validator = Validator::make($request->all(), [
            'role_id' => 'required|numeric',
        ], $this->messages);
        if (!$validator->fails()) {
            try {
                $confirm_route = route('adtech.core.role.delete', ['role_id' => $request->input('role_id')]);
                return view('includes.modal_confirmation', compact('error', 'model', 'confirm_route'));
            } catch (GroupNotFoundException $e) {

//                $error = trans('news/message.error.destroy', compact('id'));
                return view('includes.modal_confirmation', compact('error', 'model', 'confirm_route'));
            }
        } else {
            return $validator->messages();
        }
    }

    public function log(Request $request)
    {
        $model = 'role';
        $confirm_route = $error = null;
        $validator = Validator::make($request->all(), [
            'type' => 'required',
            'id' => 'required|numeric',
        ], $this->messages);
        if (!$validator->fails()) {
            try {
                $logs = Activity::where([
                    ['log_name', $model],
                    ['subject_id', $request->input('id')]
                ])->get();
                return view('includes.modal_table', compact('error', 'model', 'confirm_route', 'logs'));
            } catch (GroupNotFoundException $e) {
                return view('includes.modal_table', compact('error', 'model', 'confirm_route'));
            }
        } else {
            return $validator->messages();
        }
    }

    //Table Data to index page
    public function data(Request $request)
    {
        return Datatables::of($this->role->findAll())
            ->editColumn('name', function ($roles) {
                if ($roles->role_id == 1) {
                    return $roles->name;
                } elseif ($this->_user->canAccess('adtech.core.permission.manage', ['object_type' => 'role', 'role_id' => $roles->role_id])) {
                    return $actions = '<a href=' . route('adtech.core.permission.manage', ['object_type' => 'role', 'role_id' => $roles->role_id]) . '>' . $roles->name . '</a>';
                } else {
                    return $roles->name;
                }
            })
            ->addColumn('actions', function ($roles) {
                $actions = '';
                if ($roles->role_id != 1) {
                    if ($this->_user->canAccess('adtech.core.role.log', ['object_type' => 'role', 'id' => $roles->role_id])) {
                        $actions .= '<a href=' . route('adtech.core.role.log', ['type' => 'role', 'id' => $roles->role_id]) . ' data-toggle="modal" data-target="#log"><i class="livicon" data-name="info" data-size="18" data-loop="true" data-c="#F99928" data-hc="#F99928" title="Log Role"></i></a>';
                    }
                    if ($this->_user->canAccess('adtech.core.permission.manage', ['object_type' => 'role', 'role_id' => $roles->role_id])) {
                        $actions .= '<a href=' . route('adtech.core.permission.manage', ['object_type' => 'role', 'role_id' => $roles->role_id]) . '><i class="livicon" data-name="gear" data-size="18" data-loop="true" data-c="#6CC66C" data-hc="#6CC66C" title="add Role"></i></a>';
                    }
                    if ($this->_user->canAccess('adtech.core.role.show')) {
                        $actions .= '<a href=' . route('adtech.core.role.show', ['role_id' => $roles->role_id]) . '><i class="livicon" data-name="edit" data-size="18" data-loop="true" data-c="#428BCA" data-hc="#428BCA" title="update News"></i></a>';
                    }
                    if ($this->_user->canAccess('adtech.core.role.confirm-delete')) {
                        $actions .= '<a href=' . route('adtech.core.role.confirm-delete', ['role_id' => $roles->role_id]) . ' data-toggle="modal" data-target="#delete_confirm"><i class="livicon" data-name="trash" data-size="18" data-loop="true" data-c="#f56954" data-hc="#f56954" title="delete Roles"></i></a>';
                    }
                }
                return $actions;
            })
            ->addIndexColumn()
            ->rawColumns(['actions', 'name'])
            ->make();
    }
}
