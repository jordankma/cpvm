<?php

namespace Cpvm\Level\App\Http\Controllers;

use Illuminate\Http\Request;
use Adtech\Application\Cms\Controllers\Controller as Controller;
use Cpvm\Level\App\Repositories\LevelRepository;
use Cpvm\Level\App\Models\Level;
use Spatie\Activitylog\Models\Activity;
use Yajra\Datatables\Datatables;
use Validator;
use DateTime;
class LevelController extends Controller
{
    private $messages = array(
        'name.regex' => "Sai định dạng",
        'required' => "Bắt buộc",
        'numeric'  => "Phải là số"
    );

    public function __construct(LevelRepository $levelRepository)
    {
        parent::__construct();
        $this->level = $levelRepository;
    }

    public function manage()
    {
        return view('CPVM-LEVEL::modules.level.level.manage');
    }

    public function create()
    {
        return view('CPVM-LEVEL::modules.level.level.create');
    }

    public function add(Request $request)
    {   
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:0|max:200',
            'type' => 'required',
            'color' => 'required',
            'background' => 'required',
            'color_mobile' => 'required',
            'background_mobile' => 'required'
        ], $this->messages);
        if (!$validator->fails()) {
            $levels = new Level();
            $levels->name = $request->input('name');
            $levels->create_by = $this->user->email;
            $levels->alias = self::stripUnicode($request->input('name'));
            $levels->color = $request->input('color');
            $levels->background = $request->input('background');
            $levels->color_mobile = $request->input('color_mobile');
            $levels->background_mobile = $request->input('background_mobile');
            $levels->created_at = new DateTime();
            $levels->updated_at = new DateTime();

            if ($levels->save()) {

                activity('level')
                    ->performedOn($levels)
                    ->withProperties($request->all())
                    ->log('User: :causer.email - Add Level - name: :properties.name, level_id: ' . $levels->level_id);

                return redirect()->route('cpvm.level.level.manage')->with('success', trans('cpvm-level::language.messages.success.create'));
            } else {
                return redirect()->route('cpvm.level.level.manage')->with('error', trans('cpvm-level::language.messages.error.create'));
            }
        } else {
            return $validator->messages();
        }
    }

    public function show(Request $request)
    {
        $level_id = $request->input('level_id');
        $level = $this->level->find($level_id);
        if($level == null){
            return redirect()->route('cpvm.level.level.manage')->with('error', trans('cpvm-level::language.messages.error.update')); 
        }
        $data = [
            'level' => $level
        ];

        return view('CPVM-LEVEL::modules.level.level.edit', $data);
    }

    public function update(Request $request)
    {
        $level_id = $request->input('level_id');
        $level = $this->level->find($level_id);

        if($level == null){
            return redirect()->route('cpvm.level.level.manage')->with('error', trans('cpvm-level::language.messages.error.update')); 
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|min:0|max:200',
            'type' => 'required',
            'color' => 'required',
            'background' => 'required',
            'color_mobile' => 'required',
            'background_mobile' => 'required'
        ], $this->messages);
        if (!$validator->fails()) {
            $level->name = $request->input('name');
            $level->alias = self::stripUnicode($request->input('name'));;
            $level->color = $request->input('color');
            $level->background = $request->input('background');
            $level->color_mobile = $request->input('color_mobile');
            $level->background_mobile = $request->input('background_mobile');
            $level->updated_at = new DateTime();

            if ($level->save()) {

                activity('level')
                    ->performedOn($level)
                    ->withProperties($request->all())
                    ->log('User: :causer.email - Update level - level_id: :properties.level_id, name: :properties.name');

                return redirect()->route('cpvm.level.level.manage')->with('success', trans('cpvm-level::language.messages.success.update'));
            } else {
                return redirect()->route('cpvm.level.level.show', ['level_id' => $request->input('level_id')])->with('error', trans('cpvm-level::language.messages.error.update'));
            }
        } else {
            return $validator->messages();    
        }
    }

    public function getModalDelete(Request $request)
    {
        $model = 'level';
        $type = 'delete';
        $confirm_route = $error = null;
        $validator = Validator::make($request->all(), [
            'level_id' => 'required|numeric',
        ], $this->messages);
        if (!$validator->fails()) {
            try {
                $confirm_route = route('cpvm.level.level.delete', ['level_id' => $request->input('level_id')]);
                return view('CPVM-LEVEL::modules.level.modal.modal_confirmation', compact('error','type', 'model', 'confirm_route'));
            } catch (GroupNotFoundException $e) {
                return view('CPVM-LEVEL::modules.level.modal.modal_confirmation', compact('error','type', 'model', 'confirm_route'));
            }
        } else {
            return $validator->messages();
        }
    }

    public function delete(Request $request)
    {
        $level_id = $request->input('level_id');
        $level = $this->level->find($level_id);

        if($level == null){
            return redirect()->route('cpvm.level.level.manage')->with('error', trans('cpvm-level::language.messages.error.update')); 
        }

        if (null != $level) {
            $this->level->delete($level_id);

            activity('level')
                ->performedOn($level)
                ->withProperties($request->all())
                ->log('User: :causer.email - Delete level - level_id: :properties.level_id, name: ' . $level->name);

            return redirect()->route('cpvm.level.level.manage')->with('success', trans('cpvm-level::language.messages.success.delete'));
        } else {
            return redirect()->route('cpvm.level.level.manage')->with('error', trans('cpvm-level::language.messages.error.delete'));
        }
    }

    public function log(Request $request)
    {
        $model = 'level';
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
                return view('CPVM-LEVEL::modules.level.modal.modal_table', compact('error', 'model', 'confirm_route', 'logs'));
            } catch (GroupNotFoundException $e) {
                return view('CPVM-LEVEL::modules.level.modal.modal_table', compact('error', 'model', 'confirm_route'));
            }
        } else {
            return $validator->messages();
        }
    }

    //Table Data to index page
    public function data()
    {
        $levels = $this->level->findAll();
        return Datatables::of($levels)
            ->addColumn('actions', function ($levels) {
                $actions = '';
                if ($this->user->canAccess('cpvm.level.level.log')) {
                    $actions .= '<a href=' . route('cpvm.level.level.log', ['type' => 'level', 'id' => $levels->level_id]) . ' data-toggle="modal" data-target="#log"><i class="livicon" data-name="info" data-size="18" data-loop="true" data-c="#F99928" data-hc="#F99928" title="log level"></i></a>';
                }
                if ($this->user->canAccess('cpvm.level.level.show')) {
                    $actions .= '<a href=' . route('cpvm.level.level.show', ['level_id' => $levels->level_id]) . '><i class="livicon" data-name="edit" data-size="18" data-loop="true" data-c="#428BCA" data-hc="#428BCA" title="update level"></i></a>';
                }
                if ($this->user->canAccess('cpvm.level.level.confirm-delete')) {
                    $actions .= '<a href=' . route('cpvm.level.level.confirm-delete', ['level_id' => $levels->level_id]) . ' data-toggle="modal" data-target="#delete_confirm"><i class="livicon" data-name="trash" data-size="18" data-loop="true" data-c="#f56954" data-hc="#f56954" title="delete level"></i></a>';
                }
                return $actions;
            })
            ->addColumn('background', function ($levels) {
                $background = '<img  style="width:100px;height:100px"src="'.$levels->background.'">'; 
                return $background;   
            })
            ->addColumn('background_mobile', function ($levels) {
                $background_mobile = '<img  style="width:100px;height:100px"src="'.$levels->background_mobile.'">'; 
                return $background_mobile;   
            })
            ->addColumn('color', function ($levels) {
                $color = '<p style="background-color : ' . $levels->color . ' ">' . $levels->color . '</p>';
                return $color;
            })
            ->addColumn('color_mobile', function ($levels) {
                $color_mobile = '<p style="background-color : ' . $levels->color_mobile . ' ">' . $levels->color_mobile . '</p>';
                return $color_mobile;
            })
            ->addIndexColumn()
            ->rawColumns(['actions','color','color_mobile','background','background_mobile'])
            ->make();
    }
}
