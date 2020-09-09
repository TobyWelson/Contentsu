<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Mail;
use App\Mail\MailAction;

class MailController extends Controller
{
  public static function preRegistSend(string $to, string $id)
  {
    $subject = 'コン転ツ：仮登録';
    $text = 'http://localhost:3000/regist/'.$id;
    Mail::to($to)->send(new MailAction($subject, $text));
  }
}