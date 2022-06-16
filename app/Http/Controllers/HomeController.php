<?php

namespace App\Http\Controllers;

use App\Helpers\Qs;
use App\Repositories\UserRepo;
use App\Repositories\SmsRepo;
use App\Models\Subject;
use Illuminate\Support\Facades\DB;
use App\Repositories\MyClassRepo;
use Illuminate\Http\Request;


class HomeController extends Controller
{
    protected $user;
    public function __construct(MyClassRepo $my_class, UserRepo $user, SmsRepo $sms)
    {
        $this->user = $user;
        $this->my_class = $my_class;
        $this->sms = $sms;
    }


    public function index()
    {
        return redirect()->route('dashboard');
    }

    public function privacy_policy()
    {
        $data['app_name'] = config('app.name');
        $data['app_url'] = config('app.url');
        $data['contact_phone'] = Qs::getSetting('phone');
        return view('pages.other.privacy_policy', $data);
    }

    public function terms_of_use()
    {
        $data['app_name'] = config('app.name');
        $data['app_url'] = config('app.url');
        $data['contact_phone'] = Qs::getSetting('phone');
        return view('pages.other.terms_of_use', $data);
    }

    public function dashboard()
    {
        $d=[];
        if(Qs::userIsTeamSAT()){
            $d['users'] = $this->user->getAll();
        }
     
        $sorted = [];
        $class = [];
        $marks =  DB::table('marks')
                      ->join('subjects', 'marks.subject_id', '=', 'subjects.id')
                      ->join('my_classes', 'marks.my_class_id', '=', 'my_classes.id')
                      ->select('marks.*','subjects.name', 'my_classes.name as class_name')
                      ->get();
        
        foreach ($marks as $key => $value) {
            $sorted[$value->name][$key] = $value->tex1;
        }
        foreach ($sorted as $key => $value) {
            $sorted[$key] = array_sum($value)/count($value);
        }
        foreach ($marks as $key => $value) {
            if($value->class_name == $value->class_name ){
                 $class[$value->class_name] = $sorted;
            }
            else{
                return false;
                // $class['class_name'][$value->class_name] = $sorted;
            }
        }

        return view('pages.support_team.dashboard', compact('class'),$d);
    }

    public function sms_index()
    {
        $data['selected'] = false;
        $data['my_classes'] = $this->my_class->all();
        return view('pages.admin.send_sms',$data);
    }

    public function sms_to_parent(Request $request)
    {
        return $this->sms->send_sms($request->phone_number,$request->message);
    }

   
}
