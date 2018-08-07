<?php

namespace Cpvm\Subject\App\Http\Controllers;

use Illuminate\Http\Request;
use Adtech\Application\Cms\Controllers\Controller as Controller;
use Cpvm\Subject\App\Repositories\SubjectRepository;
use Cpvm\Subject\App\Models\Subject;

use Cpvm\Classes\App\Repositories\ClassesRepository;
use Cpvm\Classes\App\Models\Classes;

use Cpvm\Subject\App\Models\ClassHasSubject;

use Spatie\Activitylog\Models\Activity;
use Yajra\Datatables\Datatables;
use Validator,DateTime;
use DB;

class SubjectController extends Controller
{
    private $messages = array(
        'name.regex' => "Sai định dạng",
        'required' => "Bắt buộc",
        'numeric'  => "Phải là số"
    );

    public function __construct(SubjectRepository $subjectRepository,ClassesRepository $classesRepository)
    {
        parent::__construct();
        $this->subject = $subjectRepository;
        $this->classes = $classesRepository;
    }

    public function manage()
    {
        return view('CPVM-SUBJECT::modules.subject.subject.manage');
    }

    public function create()
    {
        $classes = Classes::where('status','enable')->get();
        $data = [
            'classes' => $classes
        ];
        return view('CPVM-SUBJECT::modules.subject.subject.create',$data);
    }

    public function add(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:0|max:200',
            'background_color' => 'required',
            'icon' => 'required',
            'background_color_mobile' => 'required',
            'icon_mobile' => 'required'
        ], $this->messages);
        if (!$validator->fails()) {
            $subject = new Subject();
            $subject->name = $request->input('name');
            $subject->alias = self::stripUnicode($request->input('name'));
            $subject->create_by = $this->user->email;
            $subject->icon = $request->input('icon');
            $subject->icon_mobile = $request->input('icon_mobile');
            $subject->background_color = $request->input('background_color');
            $subject->background_color_mobile = $request->input('background_color_mobile');
            $subject->priority = $request->input('priority'); 
            $subject->created_at = new DateTime();
            $subject->updated_at = new DateTime();
            if ($subject->save()) {
                if(!empty($request->input('classes'))){
                    foreach ($request->input('classes') as $classes ){
                        DB::table('class_has_subject')->insert([
                            'classes_id' => $classes,
                            'subject_id' => $subject->subject_id,
                        ]);
                    }
                }

                activity('subject')
                    ->performedOn($subject)
                    ->withProperties($request->all())
                    ->log('User: :causer.email - Add subject - name: :properties.name, subject_id: ' . $subject->subject_id);

                return redirect()->route('cpvm.subject.subject.manage')->with('success', trans('cpvm-subject::language.messages.success.create'));
            } else {
                return redirect()->route('cpvm.subject.subject.manage')->with('error', trans('cpvm-subject::language.messages.error.create'));
            }
        } else {
            $validator->messages();
        }
    }

    public function show(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'subject_id' => 'required|numeric',
        ], $this->messages);
        if (!$validator->fails()) {
            $subject_id = $request->input('subject_id');
            $subject = $this->subject->find($subject_id);
            $classes = Classes::where('status','enable')->get();
            $class_ids =  ClassHasSubject::where('subject_id', $subject_id)->select('classes_id')->get()->toArray();
            $list_class_id = array();
            foreach ($class_ids as $key => $value) {
                $list_class_id[] = $value['classes_id'];
            }
            if($subject==null){
                return redirect()->route('cpvm.subject.subject.manage')->with('error', trans('cpvm-subject::language.messages.error.update'));    
            }
            $data = [
                'subject' => $subject,
                'classes' => $classes,
                'list_class_id' => $list_class_id
            ];
            return view('CPVM-SUBJECT::modules.subject.subject.edit', $data);
        } else {
            return $validator->messages();
        }
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:0|max:200',
            'background_color' => 'required',
            'icon' => 'required',
            'background_color_mobile' => 'required',
            'icon_mobile' => 'required',
            'subject_id' => 'required|numeric'
        ], $this->messages);
        if (!$validator->fails()) {
            $subject_id = $request->input('subject_id');

            $subject = $this->subject->find($subject_id);

            $subject->name = $request->input('name');
            $subject->alias = self::stripUnicode($request->input('name'));
            $subject->icon = $request->input('icon');
            $subject->icon_mobile = $request->input('icon_mobile');
            $subject->background_color = $request->input('background_color');
            $subject->background_color_mobile = $request->input('background_color_mobile');
            $subject->priority = $request->input('priority'); 
            $subject->updated_at = new DateTime();

            if ($subject->save()) {
                DB::table('class_has_subject')->where('subject_id',$subject_id)->delete();
                if(!empty($request->input('classes'))){
                    foreach ($request->input('classes') as $classes ){
                        DB::table('class_has_subject')->insert([
                            'classes_id' => $classes,
                            'subject_id' => $subject->subject_id,
                        ]);
                    }
                }
                activity('subject')
                    ->performedOn($subject)
                    ->withProperties($request->all())
                    ->log('User: :causer.email - Update subject - subject_id: :properties.subject_id, name: :properties.name');

                return redirect()->route('cpvm.subject.subject.manage')->with('success', trans('cpvm-subject::language.messages.success.update'));
            } else {
                return redirect()->route('cpvm.subject.subject.show', ['subject_id' => $request->input('subject_id')])->with('error', trans('cpvm-subject::language.messages.error.update'));
            }
        } else {
            return $validator->messages();
        }
    }

    public function getModalDelete(Request $request)
    {
        $model = 'subject';
        $type = 'delete';
        $confirm_route = $error = null;
        $validator = Validator::make($request->all(), [
            'subject_id' => 'required|numeric',
        ], $this->messages);
        if (!$validator->fails()) {
            try {
                $confirm_route = route('cpvm.subject.subject.delete', ['subject_id' => $request->input('subject_id')]);
                return view('CPVM-SUBJECT::modules.subject.modal.modal_confirmation', compact('error','type', 'model', 'confirm_route'));
            } catch (GroupNotFoundException $e) {
                return view('CPVM-SUBJECT::modules.subject.modal.modal_confirmation', compact('error','type', 'model', 'confirm_route'));
            }
        } else {
            return $validator->messages();
        }
    }

    public function delete(Request $request)
    {
        $subject_id = $request->input('subject_id');
        $subject = $this->subject->find($subject_id);

        if (null != $subject) {
            $this->subject->delete($subject_id);

            activity('subject')
                ->performedOn($subject)
                ->withProperties($request->all())
                ->log('User: :causer.email - Delete subject - subject_id: :properties.subject_id, name: ' . $subject->name);

            return redirect()->route('cpvm.subject.subject.manage')->with('success', trans('cpvm-subject::language.messages.success.delete'));
        } else {
            return redirect()->route('cpvm.subject.subject.manage')->with('error', trans('cpvm-subject::language.messages.error.delete'));
        }
    }

    public function log(Request $request)
    {
        $model = 'subject';
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
                return view('CPVM-SUBJECT::modules.subject.modal.modal_table', compact('error', 'model', 'confirm_route', 'logs'));
            } catch (GroupNotFoundException $e) {
                return view('CPVM-SUBJECT::modules.subject.modal.modal_table', compact('error', 'model', 'confirm_route'));
            }
        } else {
            return $validator->messages();
        }
    }

    //Table Data to index page
    public function data()
    {
        $subjects = $this->subject->findAll();
        return Datatables::of($subjects)
            ->addColumn('actions', function ($subjects) {
                $actions = '';
                if ($this->user->canAccess('cpvm.subject.subject.log')) {
                    $actions .= '<a href=' . route('cpvm.subject.subject.log', ['type' => 'subject', 'id' => $subjects->subject_id]) . ' data-toggle="modal" data-target="#log"><i class="livicon" data-name="info" data-size="18" data-loop="true" data-c="#F99928" data-hc="#F99928" title="log subject"></i></a>';
                }
                if ($this->user->canAccess('cpvm.subject.subject.show')) {
                    $actions .= '<a href=' . route('cpvm.subject.subject.show', ['subject_id' => $subjects->subject_id]) . '><i class="livicon" data-name="edit" data-size="18" data-loop="true" data-c="#428BCA" data-hc="#428BCA" title="update subject"></i></a>';
                }
                if ($this->user->canAccess('cpvm.subject.subject.confirm-delete')) {
                    $actions .= '<a href=' . route('cpvm.subject.subject.confirm-delete', ['subject_id' => $subjects->subject_id]) . ' data-toggle="modal" data-target="#delete_confirm"><i class="livicon" data-name="trash" data-size="18" data-loop="true" data-c="#f56954" data-hc="#f56954" title="delete subject"></i></a>';
                }
                return $actions; 
            })
            ->addColumn('classes', function ($subjects) {
                $classes = '';
                foreach ($subjects->getClass as $key => $value) {
                    $classes .= $value->name. ',';  
                }
                return $classes;
            })
            ->addColumn('icon', function ($subjects) {
                $icon = '<img  style="width:100px;height:100px" src="'.$subjects->icon.'">'; 
                return $icon;   
            })
            ->addColumn('icon_mobile', function ($subjects) {
                $icon_mobile = '<img  style="width:100px;height:100px"src="'.$subjects->icon_mobile.'">'; 
                return $icon_mobile;   
            })
            ->addColumn('background_color', function ($subjects) {
                $background_color = '<p style="background-color : ' . $subjects->background_color . ' ">' . $subjects->background_color . '</p>';
                return $background_color;
            })
            ->addColumn('background_color_mobile', function ($subjects) {
                $background_color_mobile = '<p style="background-color : ' . $subjects->background_color_mobile . ' ">' . $subjects->background_color_mobile . '</p>';
                return $background_color_mobile;
            })
            ->addIndexColumn()
            ->rawColumns(['actions','classes','icon','icon_mobile','background_color_mobile','background_color'])
            ->make();
    }

    public function getSubject(Request $request) {
        $classes = new Classes();
        $subjectsData = $classes->getAllSubjectByClass();
        $data = [];
        foreach ($request->class as $id) {
            $subjects = $subjectsData[$id]['subject'];
            if (!empty($subjects)) {
                foreach ($subjects as $sub_id => $sub_name) {
                    $data[$sub_id] = $sub_name['name'];
                }
            }
        }
        return json_encode($data);
    }
}
