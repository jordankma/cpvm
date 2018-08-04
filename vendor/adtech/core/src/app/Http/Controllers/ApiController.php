<?php

namespace Adtech\Core\App\Http\Controllers;

use Illuminate\Http\Request;
use Adtech\Application\Cms\Controllers\Controller as Controller;
use Adtech\Core\App\Repositories\ApiRepository;
use Adtech\Core\App\Repositories\PackageRepository;
use Adtech\Core\App\Http\Requests\ApiRequest;
use Adtech\Core\App\Models\Api;
use Spatie\Activitylog\Models\Activity;
use Yajra\Datatables\Datatables;
use Validator;

class ApiController extends Controller
{
    private $messages = array(
        'name.regex' => "Sai định dạng",
        'required' => "Bắt buộc",
        'numeric'  => "Phải là số"
    );

    public function __construct(ApiRepository $apiRepository, PackageRepository $packageRepository)
    {
        parent::__construct();
        $this->api = $apiRepository;
        $this->package = $packageRepository;
    }

    public function add(ApiRequest $request)
    {
        $package_id = $request->input('package_id');
        $apis = new Api($request->all());
        $apis->save();

        if ($apis->api_id) {

            activity('api')
                ->performedOn($apis)
                ->withProperties($request->all())
                ->log('User: :causer.email - Add Api - name: :properties.name, api_id: ' . $apis->api_id);

            return redirect()->route('adtech.core.api.manage', ['package_id' => $package_id])->with('success', trans('adtech-core::messages.success.create'));
        } else {
            return redirect()->route('adtech.core.api.manage')->with('error', trans('adtech-core::messages.error.create'));
        }
    }

    public function create(Request $request)
    {
        if ($request->has('package_id')) {
            $package_id = $request->input('package_id');
            return view('ADTECH-CORE::modules.core.api.create', compact('package_id'));
        } else {
            return redirect()->route('adtech.core.package.manage')->with('error', trans('adtech-core::messages.error.create'));
        }
    }

    public function delete(Request $request)
    {
        $api_id = $request->input('api_id');
        $api = $this->api->find($api_id);

        if (null != $api) {
            $this->api->delete($api_id);

            activity('api')
                ->performedOn($api)
                ->withProperties($request->all())
                ->log('User: :causer.email - Delete Api - api_id: :properties.api_id, name: ' . $api->name);

            return redirect()->route('adtech.core.api.manage', ['package_id' => $api->package_id])->with('success', trans('adtech-core::messages.success.delete'));
        } else {
            return redirect()->route('adtech.core.api.manage', ['package_id' => $api->package_id])->with('error', trans('adtech-core::messages.error.delete'));
        }
    }

    public function manage(Request $request)
    {
        if ($request->has('package_id')) {
            $package_id = $request->input('package_id');
            $packageDetail = $this->package->find($package_id);
            if (null != $packageDetail) {

                return view('ADTECH-CORE::modules.core.api.manage', compact("package_id"));
            } else {
                return redirect()->route('adtech.core.package.manage')->with('error', trans('adtech-core::messages.error.create'));
            }
        } else {
            return redirect()->route('adtech.core.package.manage')->with('error', trans('adtech-core::messages.error.create'));
        }

    }

    public function show(ApiRequest $request)
    {
        $api_id = $request->input('api_id');
        $api = $this->api->find($api_id);
        $data = [
            'api' => $api
        ];

        return view('ADTECH-CORE::modules.core.api.edit', $data);
    }

    public function update(ApiRequest $request)
    {
        $api_id = $request->input('api_id');

        $api = $this->api->find($api_id);
        $api->name = $request->input('name');
        $api->link = $request->input('link');
        $api->description = $request->input('description');
        $api->datademo = $request->input('datademo');

        if ($api->save()) {

            activity('api')
                ->performedOn($api)
                ->withProperties($request->all())
                ->log('User: :causer.email - Update Api - api_id: :properties.api_id, name: :properties.name');

            return redirect()->route('adtech.core.api.manage', ['package_id' => $api->package_id])->with('success', trans('adtech-core::messages.success.update'));
        } else {
            return redirect()->route('adtech.core.api.show', ['api_id' => $request->input('api_id')])->with('error', trans('adtech-core::messages.error.update'));
        }
    }

    public function getModalDelete(ApiRequest $request)
    {
        $model = 'api';
        $confirm_route = $error = null;

        try {
            $confirm_route = route('adtech.core.api.delete', ['api_id' => $request->input('api_id')]);
            return view('includes.modal_confirmation', compact('error', 'model', 'confirm_route'));
        } catch (GroupNotFoundException $e) {
            return view('includes.modal_confirmation', compact('error', 'model', 'confirm_route'));
        }
    }

    public function getModalDatademo(ApiRequest $request)
    {
        $model = 'api';
        $error = null;
        try {
            $api = $this->api->find($request->input('api_id'));
            $info = $api->datademo;
            return view('includes.modal_info', compact('error', 'model', 'info'));
        } catch (GroupNotFoundException $e) {
            return view('includes.modal_info', compact('error', 'model', 'info'));
        }
    }

    public function log(Request $request)
    {
        $model = 'api';
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

    public function showdata($module, $link) {
        $apis = Api::where('visible', 1)->where('link', $link)->first();
        if (null != $apis) {
            return response($apis->datademo)->setStatusCode(200)->header('Content-Type', 'application/json; charset=utf-8');
        } else {
            abort(404);
        }
    }

    //Table Data to index page
    public function data(Request $request)
    {
        if ($request->has('package_id')) {
            $package_id = $request->input('package_id');
            $packageDetail = $this->package->find($package_id);
            $module_alias = $packageDetail->module_alias;
            $apis = Api::where('package_id', $package_id)->get();
            return Datatables::of($apis)
                ->editColumn('datademo', function ($apis) {
                    return '<a href=' . route('adtech.core.api.datademo', ['api_id' => $apis->api_id]) . ' data-toggle="modal" data-target="#datademo"><div class="btn-group btn-group-xs">
                        <button type="button" class="btn btn-primary btn_sizes">View data demo</button></div></a>';
                })
                ->editColumn('link', function ($apis) use ($module_alias) {
                    //them nut view datademo + add route tong cho phan api
                    $url = '/admin/api/' . $module_alias . '/' . $apis->link;
                    return $url;
                })
                ->addColumn('status', function ($apis) use ($module_alias) {
                    $url = '/admin/api/' . $module_alias . '/' . $apis->link;
                    $route = app('router')->getRoutes()->match(app('request')->create($url))->getName();
                    if ($route != 'adtech.core.api.showdata') {
                        $actions = '<div class="btn-group btn-group-xs">
                            <button type="button" class="btn btn-success btn_sizes">Success</button>
                        </div>';
                    } else {
                        $actions = '<div class="btn-group btn-group-xs">
                            <button type="button" class="btn btn-warning btn_sizes">Waiting</button>
                        </div>';
                    }

                    return $actions;
                })
                ->addColumn('actions', function ($apis) {
                    $actions = '<a href=' . route('adtech.core.api.log', ['type' => 'api', 'id' => $apis->api_id]) . ' data-toggle="modal" data-target="#log"><i class="livicon" data-name="info" data-size="18" data-loop="true" data-c="#F99928" data-hc="#F99928" title="log api"></i></a>
                        <a href=' . route('adtech.core.api.show', ['api_id' => $apis->api_id]) . '><i class="livicon" data-name="edit" data-size="18" data-loop="true" data-c="#428BCA" data-hc="#428BCA" title="update api"></i></a>
                        <a href=' . route('adtech.core.api.confirm-delete', ['api_id' => $apis->api_id]) . ' data-toggle="modal" data-target="#delete_confirm"><i class="livicon" data-name="trash" data-size="18" data-loop="true" data-c="#f56954" data-hc="#f56954" title="delete api"></i></a>';

                    return $actions;
                })
                ->rawColumns(['actions', 'datademo', 'status'])
                ->make();
        }
    }
}
