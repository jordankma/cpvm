<?php

namespace Cpvm\Classes\App\Http\Controllers;

use Illuminate\Http\Request;
use Adtech\Application\Cms\Controllers\Controller as Controller;
use Cpvm\Classes\App\Repositories\ClassesRepository;
use Cpvm\Classes\App\Models\Classes;

use Cpvm\Level\App\Repositories\LevelRepository;
use Cpvm\Level\App\Models\Level;

use Spatie\Activitylog\Models\Activity;
use Yajra\Datatables\Datatables;
use Validator,DateTime;

class ClassesController extends Controller
{
    private $messages = array(
        'name.regex' => "Sai định dạng",
        'required' => "Bắt buộc",
        'numeric'  => "Phải là số"
    );

    public function __construct(ClassesRepository $classesRepository,LevelRepository $levelRepository)
    {
        parent::__construct();
        $this->classes = $classesRepository;
        $this->level = $levelRepository;
    }

    public function manage()
    {
        return view('CPVM-CLASSES::modules.classes.classes.manage');
    }

    public function create()
    {
        $levels = $this->level->all();
        if(count($levels)<=0){
            return redirect()->route('cpvm.classes.classes.manage')->with('error', trans('Bạn cần thêm cấp trước'));   
        }
        $data = [
            'levels' => $levels
        ]; 
        return view('CPVM-CLASSES::modules.classes.classes.create',$data);
    }

    public function add(Request $request)
    {
        $classes = new Classes();
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:0|max:200',
            'type' => 'required',
            'level' => 'required',
            'priority' => 'required',
            'color_mobile' => 'required',
            'background_mobile' => 'required'
        ], $this->messages);
        if (!$validator->fails()) {
            $classes->name = $request->input('name');
            $classes->alias = self::stripUnicode($request->input('name'));
            $classes->create_by = $this->user->email;
            $classes->type = $request->input('type');
            $classes->level_id = $request->input('level');
            $classes->priority = $request->input('priority');
            $classes->color_mobile = $request->input('color_mobile');
            $classes->background_mobile = $request->input('background_mobile');
            $classes->created_at = new DateTime();
            $classes->updated_at = new DateTime();
            if ($classes->save()) {
                activity('classes')
                    ->performedOn($classes)
                    ->withProperties($request->all())
                    ->log('User: :causer.email - Add classes - name: :properties.name, classes_id: ' . $classes->classes_id);

                return redirect()->route('cpvm.classes.classes.manage')->with('success', trans('cpvm-classes::language.messages.success.create'));
            } else {
                return redirect()->route('cpvm.classes.classes.manage')->with('error', trans('cpvm-classes::language.messages.error.create'));
            }
        } else {
            return $validator->messages();
        }
    }

    public function show(Request $request)
    {
        $classes_id = $request->input('classes_id');
        $classes = $this->classes->find($classes_id);
        $levels = $this->level->all();
        if(count($levels)<=0 || $classes==null){
            return redirect()->route('cpvm.classes.classes.manage')->with('error', trans('Bạn cần thêm cấp trước'));   
        }
        $data = [
            'classes' => $classes,
            'levels' => $levels
        ];
        return view('CPVM-CLASSES::modules.classes.classes.edit', $data);
    }

    public function update(Request $request)
    {
        $classes_id = $request->input('classes_id');

        $classes = $this->classes->find($classes_id);
        if($classes==null){
            return redirect()->route('cpvm.classes.classes.manage')->with('error', trans('cpvm-classes::language.messages.error.update'));   
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|min:0|max:200',
            'type' => 'required',
            'level' => 'required',
            'priority' => 'required',
            'color_mobile' => 'required',
            'background_mobile' => 'required'
        ], $this->messages);
        if (!$validator->fails()) {
            $classes->name = $request->input('name');
            $classes->alias = self::stripUnicode($request->input('name'));
            $classes->type = $request->input('type');
            $classes->level_id = $request->input('level');
            $classes->priority = $request->input('priority');
            $classes->color_mobile = $request->input('color_mobile');
            $classes->background_mobile = $request->input('background_mobile');
            $classes->updated_at = new DateTime();

            if ($classes->save()) {

                activity('classes')
                    ->performedOn($classes)
                    ->withProperties($request->all())
                    ->log('User: :causer.email - Update classes - classes_id: :properties.classes_id, name: :properties.name');

                return redirect()->route('cpvm.classes.classes.manage')->with('success', trans('cpvm-classes::language.messages.success.update'));
            } else {
                return redirect()->route('cpvm.classes.classes.show', ['classes_id' => $request->input('classes_id')])->with('error', trans('cpvm-classes::language.messages.error.update'));
            }
        } else {
            return $validator->messages();
        }
    }

    public function getModalDelete(Request $request)
    {
        $model = 'classes';
        $type = 'delete';
        $confirm_route = $error = null;
        $validator = Validator::make($request->all(), [
            'classes_id' => 'required|numeric',
        ], $this->messages);
        if (!$validator->fails()) {
            try {
                $confirm_route = route('cpvm.classes.classes.delete', ['classes_id' => $request->input('classes_id')]);
                return view('CPVM-CLASSES::modules.modal.modal_confirmation', compact('error','type', 'model', 'confirm_route'));
            } catch (GroupNotFoundException $e) {
                return view('CPVM-CLASSES::modules.modal.modal_confirmation', compact('error','type', 'model', 'confirm_route'));
            }
        } else {
            return $validator->messages();
        }
    }

    public function delete(Request $request)
    {
        $classes_id = $request->input('classes_id');
        $classes = $this->classes->find($classes_id);

        if (null != $classes) {
            $this->classes->delete($classes_id);

            activity('classes')
                ->performedOn($classes)
                ->withProperties($request->all())
                ->log('User: :causer.email - Delete classes - classes_id: :properties.classes_id, name: ' . $classes->name);

            return redirect()->route('cpvm.classes.classes.manage')->with('success', trans('cpvm-classes::language.messages.success.delete'));
        } else {
            return redirect()->route('cpvm.classes.classes.manage')->with('error', trans('cpvm-classes::language.messages.error.delete'));
        }
    }

    public function log(Request $request)
    {
        $model = 'classes';
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
                return view('CPVM-CLASSES::modules.modal.modal_table', compact('error', 'model', 'confirm_route', 'logs'));
            } catch (GroupNotFoundException $e) {
                return view('CPVM-CLASSES::modules.modal.modal_table', compact('error', 'model', 'confirm_route'));
            }
        } else {
            return $validator->messages();
        }
    }

    //Table Data to index page
    public function data()
    {
        $classes = $this->classes->findAll();
        return Datatables::of($classes)
            ->addColumn('actions', function ($classes) {
                $actions = '';
                if ($this->user->canAccess('cpvm.classes.classes.log')) {
                    $actions .= '<a href=' . route('cpvm.classes.classes.log', ['type' => 'classes', 'id' => $classes->classes_id]) . ' data-toggle="modal" data-target="#log"><i class="livicon" data-name="info" data-size="18" data-loop="true" data-c="#F99928" data-hc="#F99928" title="log classes"></i></a>';
                }
                if ($this->user->canAccess('cpvm.classes.classes.show')) {
                    $actions .= '<a href=' . route('cpvm.classes.classes.show', ['classes_id' => $classes->classes_id]) . '><i class="livicon" data-name="edit" data-size="18" data-loop="true" data-c="#428BCA" data-hc="#428BCA" title="update classes"></i></a>';
                }
                if ($this->user->canAccess('cpvm.classes.classes.confirm-delete')) {
                    $actions .= '<a href=' . route('cpvm.classes.classes.confirm-delete', ['classes_id' => $classes->classes_id]) . ' data-toggle="modal" data-target="#delete_confirm"><i class="livicon" data-name="trash" data-size="18" data-loop="true" data-c="#f56954" data-hc="#f56954" title="delete classes"></i></a>';
                }

                return $actions;
            })
            ->addColumn('background_mobile', function ($classes) {
                $background_mobile = '<img style="width:100px;height:100px"src="'.$classes->background_mobile.'">'; 
                return $background_mobile;   
            })
            ->addColumn('color_mobile', function ($classes) {
                $color_mobile = '<p style="background-color : ' . $classes->color_mobile . ' ">' . $classes->color_mobile . '</p>';
                return $color_mobile;
            })
            ->addIndexColumn()
            ->rawColumns(['actions','color_mobile','background_mobile'])
            ->make();
    }
}
