<?php

namespace Cpvm\Classes\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Classes extends Model {
    use SoftDeletes;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'classes';

    protected $primaryKey = 'classes_id';

    protected $fillable = ['name'];

    protected $dates = ['deleted_at'];

    public function getSubject() {
        return $this->belongsToMany('Cpvm\Subject\App\Models\Subject','class_has_subject','classes_id','subject_id');
    }

    public static function getAllSubjectByClass() {
        $classAll = Classes::with(['getSubject'=>function($q){
            $q->where('status','enable');
        }])->where('status','enable')->get()->toArray();
        $data = [];
        if (!empty($classAll)) {
            foreach ($classAll as $class) {
                $data[$class['classes_id']]['name'] = $class['name'];
                $data[$class['classes_id']]['type'] = $class['type'];
                if (!empty($class['get_subject'])) {
                    foreach ($class['get_subject'] as $subject) {
                        $data[$class['classes_id']]['subject'][$subject['subject_id']]['name'] = $subject['name'];
                        $data[$class['classes_id']]['subject'][$subject['subject_id']]['classes_id'] = $subject['pivot']['classes_id'];
                    }
                }
            }
        }
        return $data;
    }
}
