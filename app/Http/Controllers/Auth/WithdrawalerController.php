<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Auth;

class WithdrawalerController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // 認証が必要
        $this->middleware('auth');
    }

    /**
     * 退会処理
     */
    public function withdrawaler() {
        // 退会処理
        $user = Auth::user();
        Auth::logout();
        $user->delete();
        return response()->json();
    }
}
