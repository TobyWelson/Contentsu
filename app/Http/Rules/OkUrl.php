<?php

namespace App\Http\Rules;

use Illuminate\Contracts\Validation\Rule;

class OkUrl implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $youtube = 'https://youtu\.be/.*|https://www\.youtube\.com/watch.*';
        $pattern = $youtube;
        return mb_ereg($pattern, $value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        // return '氏名は「姓1文字以上＋空白＋名1文字以上」の形式である必要があります。';
        return '指定サイト以外のＵＲＬを入力することはできません。\n必要な場合は申請をお願いします。';
    }
}