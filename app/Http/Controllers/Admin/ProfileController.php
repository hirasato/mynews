<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Profile;
use App\HistoryProfile;
use Carbon\Carbon;
class ProfileController extends Controller
{
    //
     public function add()
    {
        return view('admin.profile.create');
    }

    public function create(Request $request)
    { 
      // 以下を追記
      // Varidationを行う
      $this->validate($request, Profile::$rules);
      
      $profile = new Profile;
      $form = $request->all();
      
      // フォームから送信されてきた_tokenを削除する
      unset($form['_token']);
      
      // データベースに保存する
      $profile->fill($form);
      $profile->save();
      
      return redirect('admin/profile/create');
    }

    public function edit(Request $request)
    {
        // Profile Modelからデータを取得する
      $profile = Profile::find($request->id);
       if (empty($profile)) {
        abort(404); 
        
      }
      return view('admin.profile.edit', ['profile_form' => $profile]);
    }

    public function update(Request $request)
    {
         // Validationをかける
        $this->validate($request, Profile::$rules);
        // Profile Modelからデータを取得する
        $profile = Profile::find($request->id);
          // 送信されてきたフォームデータを格納する
        $profile_form = $request->all();
        unset($profile_form['_token']);
         // 該当するデータを上書きして保存する
        $profile->fill($profile_form)->save();

        // 以下を追記
        $history_profile = new HistoryProfile();
        $history_profile->profile_id = $profile->id;
        $history_profile->edited_at = Carbon::now();
        $history_profile->save();
    return redirect('admin/profile/');
    }
       public function delete(Request $request)
    {
      // 該当するProfile Modelを取得
      $profile = Profile::find($request->id);
      // 削除する
      $profile->delete();
      return redirect('admin/profile/');
    }
  }