<?php

namespace App\Http\Controllers;

use App\Helpers\Qs;
use App\Repositories\UserRepo;
use App\Models\Subject;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    protected $user;
    public function __construct(UserRepo $user)
    {
        $this->user = $user;
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
}
