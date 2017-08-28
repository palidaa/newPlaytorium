<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use DB;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/register';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('admin');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'id' => 'required|unique:users',
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
             'role' => 'required|string',
             'department' => 'required|string',
             'type' => 'required|int'
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        $sName = explode(" ",$data['name']);
         $lastName = "";
         for ($i=1;$i<count($sName);$i++) {
             if($i==1) $lastName = $sName[$i];
             else $lastName = $lastName." ".$sName[$i];
         }

        $alphabets = str_split('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ');
        shuffle($alphabets);
        $password = '';
        for ($i = 0; $i < 6; $i++) {
          $password .= $alphabets[$i];
        }

        DB::insert('insert into employees values (?,?,?,?,?,?,?,?)', [$data['id'],$sName[0],$lastName,$data['role'],$data['type'],$data['email'],$data['department'], 0]);

        $user = [
          'email' => $data['email'],
          'password' => $password
        ];
        User::create([
            'id' => $data['id'],
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($password),
        ]);
        return $user;
    }

    public function register(Request $request)
    {
        $validator = $this->validator($request->all());
        if ($validator->fails()) {
            $this->throwValidationException(
              $request, $validator
            );
        }

        $user = $this->create($request->all());
        return view('auth.register_success')->with('user', $user);
    }

}
