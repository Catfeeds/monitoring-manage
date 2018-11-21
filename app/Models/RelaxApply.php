<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Tanmo\Search\Traits\Search;

class RelaxApply extends Model
{
    use Search;
    /**
     * 请假状态 1审核中 2已同意 3已拒绝 4已取消
     */
    const APPLYING = 1;
    const AGREED = 2;
    const REFUSED = 3;
    const CANCEL = 4;

    /**
     * 请假类型 1事假 2病假
     */
    const THING = 1;
    const ILLNESS = 2;

    protected $statusMap = [
        self::APPLYING => 'applying',
        self::AGREED => 'agreed',
        self::REFUSED => 'refused',
        self::CANCEL => 'cancel',
    ];

    protected $typeMap = [
        self::THING => '事假',
        self::ILLNESS => '病假',
    ];

    /**
     * @return mixed
     */
    public function getStatusTextAttribute()
    {
        return $this->statusMap[$this->status];
    }

    /**
     * @return mixed
     */
    public function getTypeTextAttribute()
    {
        return $this->typeMap[$this->type];
    }

    /**
     * @param Builder $builder
     * @param $status
     * @return $this
     */
    public function scopeFilterStatus(Builder $builder, $status)
    {
        $condition = [];
        foreach($status as $statu) {
            if (array_key_exists($statu, $this->statusMap)) {
                $condition[] = $statu;
            }
        }
        if($condition) {
            return $builder->whereIn('status', $condition);
        }
        else {
            foreach($status as $statu) {
                $value = array_flip($this->statusMap)[$statu];
                $condition[] = $value;
            }
            return $builder->whereIn('status', $condition);
        }
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function teacher()
    {
        return $this->belongsTo(Teacher::class,'teacher_id','id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function student()
    {
        return $this->belongsTo(Student::class,'student_id','id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function collective()
    {
        return $this->belongsTo(Collective::class,'class_id','id');
    }

    /**
     * @param Builder $builder
     * @param $start
     * @param $end
     * @return $this|void
     */
    public function scopeTimeInterval(Builder $builder, $start,$end)
    {
        if($start && $end) {
            return $builder->whereBetween('created_at', [$start,$end]);
        }
        else if($start) {
            return $builder->where('created_at', '>=' ,$start);
        }
        else if($end) {
            return $builder->where('created_at', '<=' ,$end);
        }
        else return;
    }

    /**
     * 计算当月请假天数
     */
    public function countLeave($student_id){
        $monthBegin = strtotime(date('Y-m',time()));
        $monthEnd = mktime(23,59,59,date('m'),date('t'),date('Y'));


        $leaves = RelaxApply::where('student_id',$student_id)
            ->where(function ($query) use ($monthBegin,$monthEnd) {
                $query->where([
                    ['begin','>=',$monthBegin],
                    ['end','<=',$monthEnd],
                ])->orWhere([
                    ['begin','<',$monthBegin],
                    ['end','<=',$monthEnd],
                ])->orWhere([
                    ['begin','>=',$monthBegin],
                    ['end','>',$monthEnd],
                ])->orWhere([
                    ['begin','<',$monthBegin],
                    ['end','>',$monthEnd],
                ]);
            })->get();

        $leave_count = 0;
        foreach ($leaves as $key => $leave){
            $begin = strtotime($leave->begin);
            $end = strtotime($leave->end);

            if ($begin > $end ) continue;

            if ( $begin >= $monthBegin && $end <= $monthEnd){
                $tmp = ($end - $begin) / 86400;
            }
            if ( $begin < $monthBegin && $end <= $monthEnd){
                $tmp = ($end - $monthBegin) / 86400;
            }
            if ( $begin >= $monthBegin && $end > $monthEnd){
                $tmp = ($monthEnd - $begin) / 86400;
            }
            if ( $begin < $monthBegin && $end > $monthEnd){
                $tmp = ($monthEnd - $monthBegin) / 86400;
            }

            $leave_count += $tmp;
        }

        $int = floor($leave_count);
        $point = $leave_count - $int;

        if ($point < 0.5 && $point > 0 ){
            $leave_count = $int + 0.5;
        }else{
            $leave_count = $int + 1;
        }

        return $leave_count;
    }


    public function covers()
    {
        return $this->hasMany(RelaxApplyCover::class,'apply_id','id');
    }

//    /**
//     * @param $status
//     * @return mixed
//     */
//    public function getStatusMap($status)
//    {
//        return $this->statusMap[$status];
//    }
}
