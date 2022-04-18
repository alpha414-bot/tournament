<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use voku\helper\HtmlDomParser as HTML;
use voku\helper\SimpleHtmlDomInterface;
use voku\helper\SimpleHtmlDomNode;
use voku\helper\SimpleHtmlDomNodeInterface;
use File;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Link;
use App\Models\User;
use App\Models\Site;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;

class MainController extends Controller
{
    //
    public function index(){
        $tourna = Link::oldest()->get();
        return view('major', ['tournaments'=>$tourna]);
    }

    public function store(Request $request){
        if(parse_url($request->link, PHP_URL_HOST) !== "tournej.it"){
            return back()->withErrors(['error' => "This url is invalid and can't be parsed by the system."]);
        }
        $data = $this->onceLoadTask($request->link);
        $tournament_id = json_decode($this->onceLoadTask($request->link))->openTournament;
        $tournament_name = json_decode($this->onceLoadTask($request->link))->tournaments->$tournament_id->headers->name;
        Link::create([
            'url'=>$request->link,
            'tournament_id'=> $tournament_id,
            'tournament_name'=>$tournament_name,
            'data'=>$data
        ]);
        return back()->with('done', 'done');
    }
    public function setting(){
        $token = str_random(20); 
        $reset_token = strtolower(str_random(64));
        DB::table('password_resets')->insert([
            'email'=>Auth::user()->email,
            'token'=>$reset_token,
            'created_at'=>Carbon::now(),
        ]);
        return view('setting', ['reset_token'=>$reset_token]);
    }
    public function resetPassword(Request $request){
        $request->validate([
            'email' => ['email', 'unique:users'],
            'password' => ['confirmed', Rules\Password::defaults()],
        ]);
        $user = Auth::user();

        if($request->email == null){
            $email = $user->email;
        }else{
            $email = $request->email;
        }

        if($request->password == null){
            $password = $user->password;
        }else{
            $password = Hash::make($request->password);
        }        
        User::where([['email', $user->email], ['id', $user->id]])->update([
            'password'=>$password,
            'email'=>$email,
        ]);
        return redirect()->route('logout');
    }

    public function sitename(Request $request){
        $default_name = Site::where('variable', 'sitename')->first();
        if($default_name == null){
            //Create row for sitename
            Site::create([
                'variable'=>'sitename',
                'value'=>$request->app_name
            ]);
        }else{
            Site::where('variable', 'sitename')->update([
                'value'=>$request->app_name
            ]);
        }
        return back()->with('status', 'The app name has been successfully updated!');
    }

    public function delete($id, $tournament_id){
        Link::where([['tournament_id', $tournament_id],['id', $id]])->delete();
        return back()->with('done', 'done');
    }

    public function onceLoadTask($url){
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        //for debug only!
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        $resp = curl_exec($curl); // $resp is the content containing the HTML DOM
        curl_close($curl);
        $html = HTML::file_get_html($this->createFile($resp));
        $jsonScript = $html->find('script')[0]->nodeValue;
        return $this->parseItOut($jsonScript);
    }         

    private function createFile($contents){
        $filePath = public_path().'/latest/major/file/';
        if(!is_dir($filePath)){
            mkdir($filePath, 0777, true);
        }
        File::put($filePath.'scrap.html', $contents);
        return $filePath.'scrap.html';
    }

    private function parseItOut($string){
        $major = str_replace('window.preloadedState = ', '', $string); // Remove 'window.preloadedState = '
        //$major = preg_replace('/window.PHP_HTTPSROOT = "\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]";/i', '', $major); 
        $major = preg_replace('/window.PHP_HTTPSROOT = ("([^"]|"")*");/i','',$major); // Remove window.PHP_HTTPSROOT
        $major = preg_replace('/window.PHP_VERSION = ("([^"]|"")*");/i', '',$major); //  Remove window.PHP_VERSION
        $major = preg_replace('/window.PHP_TID = ("([^"]|"")*");/i','',$major); // Remove window.PHP_TID
        $major = preg_replace('/window.PHP_LANG = ("([^"]|"")*");/i','',$major); // Remove window.LANG
        $major = str_replace('}}};','}}}', $major);
        return $major;
    }

}
