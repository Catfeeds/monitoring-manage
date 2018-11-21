<?php
use Tanmo\Admin\Facades\Admin;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use App\Models\School;
use App\Models\Teacher;

function mlog($txtname,$data){
    $now = date("Y-m-d H:i:s",time());
    file_put_contents($txtname.".txt",var_export($now,1)."\r\n",FILE_APPEND);
    file_put_contents($txtname.".txt",var_export($data,1)."\r\n",FILE_APPEND);
    file_put_contents($txtname.".txt","================================"."\r\n",FILE_APPEND);
}

function getIp() {
    //strcasecmp 比较两个字符，不区分大小写。返回0，>0，<0。
    if(getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')) {
        $ip = getenv('HTTP_CLIENT_IP');
    } elseif(getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')) {
        $ip = getenv('HTTP_X_FORWARDED_FOR');
    } elseif(getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')) {
        $ip = getenv('REMOTE_ADDR');
    } elseif(isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    $res =  preg_match ( '/[\d\.]{7,15}/', $ip, $matches ) ? $matches [0] : '';
    return $res;
}

function getShowSchoolId(){
    $ip = getIp();
    $school_id = \Illuminate\Support\Facades\Redis::get($ip."show_school_id");
    return $school_id;
}

function getSchoolId(){
    if(Admin::user()->isAdmin()){
        $school_id = getShowSchoolId();
        if( !$school_id ) {
            return view('admin::errors.no_school');
        }
    }else {
        $school_id = Admin::user()->school_id;
    }
    return $school_id;
}

function getSchoolName()
{
    $id = getShowSchoolId();
    $school = DB::table('schools')->find($id);

    return $school->name??'主平台';
}

function getSchools() {
    $value = Cache::rememberForever('schools', function() {
        return DB::table('schools')->get();
    });

    return $value;
}

function setSchools() {
    $schools = DB::table('schools')->where('state',1)->get();
    Cache::forever('schools',$schools);
}

function getSchoolList() {
    $schools = DB::table('schools')->get();
    return $schools ;
}

/**
 * @param $length
 * @return string
 */
function randomKeys($length)
{
    $key='';
    $pattern='1234567890';
    for($i=0;$i<$length;++$i) {
        $key .= $pattern{mt_rand(0,9)};    // 生成php随机数
    }
    return $key;
}

/**
 * 表情转换
 *
 * @param $content
 * @return mixed
 */
function expression($content)
{
    if(!defined("paths")) {
        $dir = public_path('/ic_tp/');
        $paths = [];

        if ($dh = opendir($dir)){
            while (($file = readdir($dh)) !== false){
                if($file == '.' || $file == '..') {
                    continue;
                }
                $paths[] = '/ic_tp/'.$file;
            }
            closedir($dh);
        }

        $labels = [];

        for($i=1;$i<=count($paths);$i++)
        {
            if($i<10) {
                $labels[] = '[tp0'.$i.']';
            }
            else {
                $labels[] = '[tp'.$i.']';
            }
        }

        foreach($paths as $k => $v) {
            $paths[$k]  =  "<img src='$v' alt='获取失败' />";
        }
        define('paths',$paths);
        define('labels',$labels);
    }

    $content = str_replace(labels, paths, $content);
    return $content;
}





define('IS_AJAX',((isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')) ? true : false);

/**
 * 获取老师的班级id
 * @param $id
 * @return array
 */
function getTeacherClassIds($id)
{
    $teacher = Teacher::where('admin_id',$id)->first();
    $classes = $teacher->collective;
    $data = [];
    foreach ($classes as $k=>$v){
        $data['ids'][$k] = $v->id;
    }
    $data['id'] = $teacher->id;

    return $data;
}

/**
 * 获取本周的日期
 *
 * @param $time
 * @param string $format
 * @return mixed
 */
function get_week($time, $format = "Y-m-d") {
    $week = date('w',$time);
    $weekname=array('星期一','星期二','星期三','星期四','星期五','星期六','星期日');
    //星期日排到末位
    if(empty($week)){
        $week=7;
    }
    for ($i=0;$i<=6;$i++){
        $data[$i]['date'] = date($format,strtotime( '+'. $i+1-$week .' days',$time));
        $data[$i]['week'] = $weekname[$i];
    }
    return $data;
}

/**
 * 获取当前日期的星期
 *
 * @param $time
 * @param string $format
 * @return mixed
 */
function get_date_week($time, $format = "Y-m-d") {
    $week = date('w',$time);
    $weekname=array('星期一','星期二','星期三','星期四','星期五','星期六','星期日');
    //星期日排到末位
    if(empty($week)){
        $week=7;
    }

    return $weekname[$week];
}

/**
 * 根据生日计算岁数
 *
 * @param $birthday
 * @return string
 */
function babyAge($birthday) {

    $baby_year = date('Y',strtotime($birthday));
    $current_year = date('Y');
    $year = $current_year - $baby_year;
    $date = floor((strtotime("now") - strtotime("$current_year-01-01"))/(3600*24));

    return $year.'岁'.$date.'天';
}

function path_name($filepath)
{
    return ltrim(substr($filepath, strrpos($filepath, '/')),"/");
}