<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{
    public function endAddress(Request $request)
    {  
        // \Log::debug('in');
        // ログインユーザー情報を取得する
        $userId = Auth::id();
        // 現在住所を取得する
        $nowAddress = $request->address;
        // ログインユーザーに該当する現住所テーブルのレコードを取得する
        $address = Address::where('user_id', $userId)->first();
        // 現住所テーブルのレコードを上書き保存する
        $address->now_address = $nowAddress;
        $address->save();

        // \Log::debug('out');

        return response()->json();
    }
}
