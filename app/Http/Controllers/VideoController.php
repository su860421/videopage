<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

use App\Video;

use Auth;
use DB;
use Gate;

class VideoController extends Controller
{
    //
    // public function showvidepage(Request $request)
    // {
    //     return view('showvideo');
    // }
    public function logout()
    {
      if (Auth::check()) {
          Auth::logout();
          $return_msg="已登出";
          return $return_msg;
      }

    }
    public function videodat(Request $request){//-------------------取得影片資料
      $create_returnmsg=array(
        "status" => "ture",
        "msg" => "檔案已上傳成功",
     );
      $create_return_errormsg=array(
         "status" => "false",
         "msg" => "請稍後在試",
      );
      $r=$request->all();
      if($r){
        $videoinfor = Video::Where('videokey', $r['videokey'])->first();
        return $videoinfor;
      }else{
        return $create_return_errormsg;
      }
    }
    public function videpage()
    {//影片首頁
        $create_returnmsg=array(
          "status" => "ture",
          "msg" => "檔案已上傳成功",
       );
        $create_return_errormsg=array(
           "status" => "false",
           "msg" => "請稍後在試",
        );
        $allvideo = Storage::disk('s3')->allFile('videoblogdat');//取得該資料夾內所有子檔案名稱
        if ($allvideo) {
            return (array)$allvideo;
        } else {
            return $create_return_errormsg;
        }
    }
    public function ownvideo()
    {//---------自己的影片
        $create_returnmsg=array(
          "status" => "ture",
          "msg" => "",
       );
        $create_return_errormsg=array(
           "status" => "false",
           "msg" => "沒有影片",
      );
        $awsdata=Auth::user()->email;
        $allvideo = Video::Where('email', $awsdata)->get();
        if ($allvideo) {
            return $allvideo;
        } else {
            return $create_return_errormsg;
        }
    }
    public function create(Request $request)//新增影片
    {
        $create_returnmsg=array(
            "status" => "ture",
            "msg" => "檔案已上傳成功",
         );
        $create_return_errormsg=array(
             "status" => "false",
             "msg" => "輸入錯誤",
          );
        $r=$request->all();
        $return_err=array();
        if ($r == null||empty($r) ||!isset($r)) {//
            $return_errlogin_info['msg']="要加雙引號,逗號和{}";
            echo json_encode((array)$return_errlogin_info);
        } else {
            $awsdata=Auth::user()->id;//aws s3資料夾名稱
            $r['email']=Auth::user()->email;
            $validator = Validator::make(
                $r,
                [//驗證
                  'email'        => ['required','email'],//限制字母 數字
                  'title'        => ['required','alpha_num','max:50'],
                  'msg'          => ['required','alpha_num'],
              ]
            );
            if ($validator->fails()) {
                $return_err=array_merge_recursive((array)$return_err, (array)$create_return_errormsg);
                foreach ($validator->messages()->all() as $message) {//直接validator取的錯誤值
                    $create_return['msg']="";
                    $create_return['msg'] .= $message;// .=疊加
                    $return_err=array_merge_recursive($return_err, $create_return);
                }
                echo json_encode((array)$return_err);
            } else {
                $video=request()->file('file')->store(//store==function
                  $awsdata,
                    's3'
                );
                $img=request()->file('imgfile')->store(//store==function
                  $awsdata,
                    's3'
                );
                if ($video||$img) {
                    unset($r['file']);
                    $r['videokey']=$video;
                    $r['imgkey']=$img;
                    $r['msg']="檔案上傳成功";
                    $pro=Video::create($r);
                    return $pro;
                } else {
                    $create_return_errormsg['msg']="檔案上傳失敗";
                    return $create_return_errormsg;
                }
            }
        }
    }
    public function deletevideo(Request $request)//刪除影片
    {
        //
        $create_returnmsg=array(
            "status" => "ture",
            "msg" => "檔案已刪除",
            "id" => "",
         );
        $create_return_errormsg=array(
             "status" => "false",
             "msg" => "檔案刪除失敗",
          );
        $bucket = 'videoblogdat';
        $keyname=$request->all();
        $keyname=$keyname['videokey'];
        $imgkey = Video::Where('videokey', $keyname)->value('imgkey');
        $videoid = Video::Where('videokey', $keyname)->value('id');
        $delvideo = Storage::disk('s3')->delete($keyname);
        $delimg=Storage::disk('s3')->delete($imgkey);
        if ($delimg&&$delvideo) {
            $path=Video::Where('videokey', $keyname)->delete();
            if($path){//做防呆
              $create_returnmsg['id']=$videoid;
              return $create_returnmsg;
            }else{
              return $create_return_errormsg;
            }
        } else {

            return $create_return_errormsg;
        }
    }
}
