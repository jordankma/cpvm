<?php

namespace Cpvm\Subject\App\Http\Controllers;

use Illuminate\Http\Request;
use Adtech\Application\Cms\Controllers\Controller as Controller;
use Cpvm\Subject\App\Repositories\DemoRepository;
use Cpvm\Subject\App\Models\Demo;
use Spatie\Activitylog\Models\Activity;
use Yajra\Datatables\Datatables;
use Validator;

class DemoController extends Controller
{
    private $messages = array(
        'name.regex' => "Sai định dạng",
        'required' => "Bắt buộc",
        'numeric'  => "Phải là số"
    );

    public function __construct(DemoRepository $demoRepository)
    {
        parent::__construct();
        $this->demo = $demoRepository;
    }

    public function add(Request $request)
    {
        $demos = new Demo($request->all());
        $demos->save();

        if ($demos->demo_id) {

            activity('demo')
                ->performedOn($demos)
                ->withProperties($request->all())
                ->log('User: :causer.email - Add Demo - name: :properties.name, demo_id: ' . $demos->demo_id);

            return redirect()->route('cpvm.subject.demo.manage')->with('success', trans('cpvm-subject::language.messages.success.create'));
        } else {
            return redirect()->route('cpvm.subject.demo.manage')->with('error', trans('cpvm-subject::language.messages.error.create'));
        }
    }

    public function create()
    {
        return view('CPVM-SUBJECT::modules.subject.demo.create');
    }

    public function delete(Request $request)
    {
        $demo_id = $request->input('demo_id');
        $demo = $this->demo->find($demo_id);

        if (null != $demo) {
            $this->demo->delete($demo_id);

            activity('demo')
                ->performedOn($demo)
                ->withProperties($request->all())
                ->log('User: :causer.email - Delete Demo - demo_id: :properties.demo_id, name: ' . $demo->name);

            return redirect()->route('cpvm.subject.demo.manage')->with('success', trans('cpvm-subject::language.messages.success.delete'));
        } else {
            return redirect()->route('cpvm.subject.demo.manage')->with('error', trans('cpvm-subject::language.messages.error.delete'));
        }
    }

    public function manage()
    {
        return view('CPVM-SUBJECT::modules.subject.demo.manage');
    }

    public function show(Request $request)
    {
        $demo_id = $request->input('demo_id');
        $demo = $this->demo->find($demo_id);
        $data = [
            'demo' => $demo
        ];

        return view('CPVM-SUBJECT::modules.subject.demo.edit', $data);
    }

    public function update(Request $request)
    {
        $demo_id = $request->input('demo_id');

        $demo = $this->demo->find($demo_id);
        $demo->name = $request->input('name');

        if ($demo->save()) {

            activity('demo')
                ->performedOn($demo)
                ->withProperties($request->all())
                ->log('User: :causer.email - Update Demo - demo_id: :properties.demo_id, name: :properties.name');

            return redirect()->route('cpvm.subject.demo.manage')->with('success', trans('cpvm-subject::language.messages.success.update'));
        } else {
            return redirect()->route('cpvm.subject.demo.show', ['demo_id' => $request->input('demo_id')])->with('error', trans('cpvm-subject::language.messages.error.update'));
        }
    }

    public function getModalDelete(Request $request)
    {
        $model = 'demo';
        $confirm_route = $error = null;
        $validator = Validator::make($request->all(), [
            'demo_id' => 'required|numeric',
        ], $this->messages);
        if (!$validator->fails()) {
            try {
                $confirm_route = route('cpvm.subject.demo.delete', ['demo_id' => $request->input('demo_id')]);
                return view('includes.modal_confirmation', compact('error', 'model', 'confirm_route'));
            } catch (GroupNotFoundException $e) {
                return view('includes.modal_confirmation', compact('error', 'model', 'confirm_route'));
            }
        } else {
            return $validator->messages();
        }
    }

    public function log(Request $request)
    {
        $model = 'demo';
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
    public function data()
    {
        return Datatables::of($this->demo->findAll())
            ->addColumn('actions', function ($demos) {
                $actions = '<a href=' . route('cpvm.subject.demo.log', ['type' => 'demo', 'id' => $demos->demo_id]) . ' data-toggle="modal" data-target="#log"><i class="livicon" data-name="info" data-size="18" data-loop="true" data-c="#F99928" data-hc="#F99928" title="log demo"></i></a>
                        <a href=' . route('cpvm.subject.demo.show', ['demo_id' => $demos->demo_id]) . '><i class="livicon" data-name="edit" data-size="18" data-loop="true" data-c="#428BCA" data-hc="#428BCA" title="update demo"></i></a>
                        <a href=' . route('cpvm.subject.demo.confirm-delete', ['demo_id' => $demos->demo_id]) . ' data-toggle="modal" data-target="#delete_confirm"><i class="livicon" data-name="trash" data-size="18" data-loop="true" data-c="#f56954" data-hc="#f56954" title="delete demo"></i></a>';

                return $actions;
            })
            ->addIndexColumn()
            ->rawColumns(['actions'])
            ->make();
    }
}
