<?php

namespace Cpvm\Block\App\Http\Controllers;

use Illuminate\Http\Request;
use Adtech\Application\Cms\Controllers\Controller as Controller;
use Cpvm\Block\App\Repositories\BlockRepository;
use Cpvm\Block\App\Models\Block;

use Cpvm\Classes\App\Models\Classes;
use Cpvm\Subject\App\Models\Subject;

use Spatie\Activitylog\Models\Activity;
use Yajra\Datatables\Datatables;
use Validator,DateTime,DB;

class BlockController extends Controller
{
    private $messages = array(
        'name.regex' => "Sai định dạng",
        'required' => "Bắt buộc",
        'numeric'  => "Phải là số"
    );

    public function __construct(BlockRepository $blockRepository)
    {
        parent::__construct();
        $this->block = $blockRepository;
    }

    public function manage()
    {
        return view('CPVM-BLOCK::modules.block.block.manage');
    }

    public function create()
    {
        $classes = Classes::all();
        if(count($classes) <= 0){
            return redirect()->route('cpvm.block.block.manage')->with('error', 'Bạn cần tạo lớp trước'); 
        }
        $data = [
            'classes' => $classes   
        ];
        return view('CPVM-BLOCK::modules.block.block.create',$data);
    }

    public function add(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'classes' => 'required',
            'subject_id' => 'required'
        ], $this->messages);
        if (!$validator->fails()) {
            $blocks = new Block();
            $blocks->name = $request->input('name');
            $blocks->alias = self::stripUnicode($request->input('name'));
            $blocks->create_by = $this->user->email;
            $blocks->created_at = new DateTime();
            $blocks->updated_at = new DateTime();
            if ($blocks->save()) {
                $block_id = $blocks->block_id;
                if(!empty($request->input('classes'))){
                    foreach ($request->input('classes') as $class_id) {
                        DB::table('block_has_class')->insert([
                            'block_id' => $block_id,
                            'classes_id' => $class_id
                        ]);
                    }
                }
                if(!empty($request->input('subject_id'))){
                    foreach ($request->input('subject_id') as $subject_id) {
                        DB::table('block_has_subject')->insert([
                            'block_id' => $block_id,
                            'subject_id' => $subject_id
                        ]);
                    }
                }
                activity('block')
                    ->performedOn($blocks)
                    ->withProperties($request->all())
                    ->log('User: :causer.email - Add block - name: :properties.name, block_id: ' . $blocks->block_id);

                return redirect()->route('cpvm.block.block.manage')->with('success', trans('cpvm-block::language.messages.success.create'));
            } else {
                return redirect()->route('cpvm.block.block.manage')->with('error', trans('cpvm-block::language.messages.error.create'));
            }
        } else {
            return $validator->messages();
        }
    }

    public function show(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'block_id' => 'required|numeric',
        ], $this->messages);
        if (!$validator->fails()) {
            $block_id = $request->input('block_id');
            $block = $this->block->find($block_id);
            if($block==null){
                return redirect()->route('cpvm.block.block.manage')->with('error', trans('cpvm-block::language.messages.error.update')); 
            }
            //get id
            $class = DB::table('block_has_class')->where('block_id', $block_id)->select('classes_id')->get()->toArray();
            $subject = DB::table('block_has_subject')->where('block_id', $block_id)->select('subject_id')->get()->toArray();
            $subject_id = array();
            $class_id = array();
            if(!empty($subject)){
                foreach ($subject as $item) {
                    $subject_id[] = $item->subject_id;
                }
            }
            if(!empty($class)){
                foreach ($class as $value) {
                    $class_id[] = $value->classes_id;
                }
            }
            //get class subject
            $classes = Classes::all();
            $subjects = Subject::all();
            $data = [
                'block' => $block,
                'subject_id' => $subject_id,
                'class_id' => $class_id,
                'classes' => $classes,
                'subjects' => $subjects
            ];

            return view('CPVM-BLOCK::modules.block.block.edit', $data);
        } else {
            return $validator->messages();
        }
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'block_id' => 'required|numeric',
        ], $this->messages);
        if (!$validator->fails()) {
            $block_id = $request->input('block_id');

            $block = $this->block->find($block_id);
            $block->name = $request->input('name');
            $block->alias = self::stripUnicode($request->input('name'));
            $block->updated_at = new DateTime();

            if ($block->save()) {
                DB::table('block_has_class')->where('block_id', $block_id)->delete();
                DB::table('block_has_subject')->where('block_id', $block_id)->delete();
                if(!empty($request->input('classes'))){
                    foreach ($request->input('classes') as $class_id) {
                        DB::table('block_has_class')->insert([
                            'block_id' => $block_id,
                            'classes_id' => $class_id
                        ]);
                    }
                }
                if(!empty($request->input('subject_id'))){
                    foreach ($request->input('subject_id') as $subject_id) {
                        DB::table('block_has_subject')->insert([
                            'block_id' => $block_id,
                            'subject_id' => $subject_id
                        ]);
                    }
                }
                activity('block')
                    ->performedOn($block)
                    ->withProperties($request->all())
                    ->log('User: :causer.email - Update block - block_id: :properties.block_id, name: :properties.name');

                return redirect()->route('cpvm.block.block.manage')->with('success', trans('cpvm-block::language.messages.success.update'));
            } else {
                return redirect()->route('cpvm.block.block.show', ['block_id' => $request->input('block_id')])->with('error', trans('cpvm-block::language.messages.error.update'));
            }
        } else {
            return $validator->messages();
        }
    }

    public function getModalDelete(Request $request)
    {
        $model = 'block';
        $type = 'delete';
        $confirm_route = $error = null;
        $validator = Validator::make($request->all(), [
            'block_id' => 'required|numeric',
        ], $this->messages);
        if (!$validator->fails()) {
            try {
                $confirm_route = route('cpvm.block.block.delete', ['block_id' => $request->input('block_id')]);
                return view('CPVM-BLOCK::modules.block.modal.modal_confirmation', compact('error','type', 'model', 'confirm_route'));
            } catch (GroupNotFoundException $e) {
                return view('CPVM-BLOCK::modules.block.modal.modal_confirmation', compact('error','type', 'model', 'confirm_route'));
            }
        } else {
            return $validator->messages();
        }
    }

    public function delete(Request $request)
    {
        $block_id = $request->input('block_id');
        $block = $this->block->find($block_id);

        if (null != $block) {
            $this->block->delete($block_id);
            DB::table('block_has_class')->where('block_id', $block_id)->delete();
            DB::table('block_has_subject')->where('block_id', $block_id)->delete();
            activity('block')
                ->performedOn($block)
                ->withProperties($request->all())
                ->log('User: :causer.email - Delete block - block_id: :properties.block_id, name: ' . $block->name);

            return redirect()->route('cpvm.block.block.manage')->with('success', trans('cpvm-block::language.messages.success.delete'));
        } else {
            return redirect()->route('cpvm.block.block.manage')->with('error', trans('cpvm-block::language.messages.error.delete'));
        }
    }

    public function log(Request $request)
    {
        $model = 'block';
        $confirm_route = $error = null;
        $validator = Validator::make($request->all(), [
            'type' => 'required',
            'id' => 'required|numeric'
        ], $this->messages);
        if (!$validator->fails()) {
            try {
                $logs = Activity::where([
                    ['log_name', $model],
                    ['subject_id', $request->input('id')]
                ])->get();
                return view('CPVM-BLOCK::modules.block.modal.modal_table', compact('error', 'model', 'confirm_route', 'logs'));
            } catch (GroupNotFoundException $e) {
                return view('CPVM-BLOCK::modules.block.modal.modal_table', compact('error', 'model', 'confirm_route'));
            }
        } else {
            return $validator->messages();
        }
    }

    //Table Data to index page
    public function data()
    {
        $blocks = $this->block->findAll();
        return Datatables::of($blocks)
            ->addColumn('actions', function ($blocks) {
                $actions = '';
                if ($this->user->canAccess('cpvm.block.block.log')) {
                    $actions .= '<a href=' . route('cpvm.block.block.log', ['type' => 'block', 'id' => $blocks->block_id]) . ' data-toggle="modal" data-target="#log"><i class="livicon" data-name="info" data-size="18" data-loop="true" data-c="#F99928" data-hc="#F99928" title="log block"></i></a>';
                }
                if ($this->user->canAccess('cpvm.block.block.show')) {
                    $actions .= '<a href=' . route('cpvm.block.block.show', ['block_id' => $blocks->block_id]) . '><i class="livicon" data-name="edit" data-size="18" data-loop="true" data-c="#428BCA" data-hc="#428BCA" title="update block"></i></a>';
                }
                if ($this->user->canAccess('cpvm.block.block.confirm-delete')) {
                    $actions .= '<a href=' . route('cpvm.block.block.confirm-delete', ['block_id' => $blocks->block_id]) . ' data-toggle="modal" data-target="#delete_confirm"><i class="livicon" data-name="trash" data-size="18" data-loop="true" data-c="#f56954" data-hc="#f56954" title="delete block"></i></a>';
                }
                return $actions;
            })
            ->addIndexColumn()
            ->rawColumns(['actions'])
            ->make();
    }
}
