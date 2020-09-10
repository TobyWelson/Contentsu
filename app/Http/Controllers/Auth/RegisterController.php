<?php

namespace App\Http\Controllers\Auth;
use Log;
use Illuminate\Http\Request; // ★ 追加
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\User;
use App\Http\Controllers\MailController;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Auth\Events\Registered;

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
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * 登録完了時に呼び出されるメソッド。
    */
    protected function registered(Request $request, $user)
    {
        return $user;
    }

    /**
     * 仮登録
     *
     * @param  Request
     * @return JsonResponse
     */
    public function preRegister(Request $request)
    {
        // 入力チェック
        $validate = $this->preValidator($request->all());
        if ($validate->fails()) {
            return response(["errors" => $validate->errors()], 422);
        }
        // ユーザ作成
        $createResult = $this->preCreate($request->all());
        if ($createResult) {
            return abort(201);
        } else {
            return response(["errors" => ["email" => ['仮登録に失敗しました。理由が不明な場合、お問い合わ画面よりお問い合わせください。']]], 422);
        }
        
    }

    /**
     * 仮登録用入力チェック
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function preValidator(array $data)
    {
        return Validator::make($data, [
            'email' => ['required', 'string', 'email', 'max:255'],
        ]);
    }

    /**
     * 仮登録用ユーザ生成
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function preCreate(array $data)
    {
        $user = User::where('email', $data['email'])->first();

        // ユーザ未登録の場合、登録及メール送信
        if (empty($user)) {
            $createUser = User::create([
                'email' => $data['email'],
            ]);
            MailController::preRegistSend($data['email'], $createUser['id']);
            return true;

        // 仮登録の場合、メール送信
        } else if ($user['states'] == 0) {
            MailController::preRegistSend($data['email'], $user['id']);
            return true;

        // 本登録・凍結の場合
        } else {
            return false;
        }
    }

    /**
     * 本登録
     *
     * @param  Request
     * @return JsonResponse
     */
    public function register(Request $request)
    {
        // 入力チェック
        $validate = $this->validator($request->all());
        if ($validate->fails()) {
            return response(["errors" => $validate->errors()], 422);
        }
        // ユーザ作成
        event(new Registered($user = $this->update($request->all())));

        if (empty($user)) {
            // エラー返却
            return response(["errors" => ["email" => ['登録に失敗しました。理由が不明な場合、お問い合わ画面よりお問い合わせください。']]], 422);
        } else {
            // ログインし成功
            $this->guard()->login( $user );
            return response($user, 201);
        }

    }

    /**
     * 本登録用入力チェック
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:50'],
            'password' => ['required', 'string', 'min:8', 'max:15', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function update(array $data)
    {
        $user = User::where('id', $data['id'])->first();

        // 仮登録の場合、本登録を行う
        if ($user['states'] == 0) {
            $user->name = $data['name'];
            $user->password = Hash::make($data['password']);
            $user->states = 1;
    
            DB::beginTransaction();
            try {
                $user->save();
                DB::commit();
            } catch (\Exception $exception) {
                DB::rollBack();
                throw $exception;
            }
    
            return $user;

        // 仮登録以外の場合
        } else {
            return null;
        }


    }
}
