<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Auth;
use DB;
use Gate;
use App\Msgsub;

class MsgsubController extends Controller
{
    //
    public function submsg(Request $request)//---存儲留言
    {
        $create_returnmsg=array(
          "status" => "ture",
          "msg" => "留言新增成功",
       );
        $create_return_errormsg=array(
           "status" => "false",
           "msg" => "留言失敗",
        );
        $r=$request->all();
        $return_err=array();
        if ($r == null||empty($r) ||!isset($r)) {//
            $create_return_errormsg['msg']="要加雙引號,逗號和{}";
            echo json_encode((array)$create_return_errormsg);
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
                $r['name']=Auth::user()->name;
                $r['email']=Auth::user()->email;
                $pro = Msgsub::create($r);
                $create_returnmsg['msg']=$r['msg'];
                $r['time']=date('Y-m-d H:i:s');
                event(new \App\Events\PushNotification($pro));//將資料傳送給事件
                //嘗試event::fire(new PushNotification($message));
                $r['id']=$pro['id'];
                echo json_encode((array)$r);
            }
        }
    }
    public function searchmsg(Request $request)//------顯示全部留言
    {
        $create_returnmsg=array(
        "status" => "ture",
        "msg" => "留言新增成功",
     );
        $create_return_errormsg=array(
         "status" => "false",
         "msg" => "留言失敗",
      );
        $r=$request->all();
        $return_err=array();
        if ($r == null||empty($r) ||!isset($r)) {//
            $create_return_errormsg['msg']="要加雙引號,逗號和{}";
            echo json_encode((array)$create_return_errormsg);
        } else {
            $pro = Msgsub::where('videokey', '=', $r)->get();
            return json_encode($pro);
        }
    }
    public function msgwebsocket(Request $request)
    {
        $message=request()->all();
        event(new \App\Events\PushNotification($message));//嘗試event::fire(new PushNotification($message));
    }
    public function showupdatmodel(Request $request)//------------------------------------修改的modal
    {
        $return_msg_info=array(
        "status" => "ture",
        "msg"    => "第"
      );
        $return_errmsg_info=array(
        "status" => "false",
        "msg"    => "錯誤訊息",
      );
        $return_err=array();
        set_time_limit(0);//設定運行時間
        //將資料由js轉成php
        $r=$request->all();
        if ($r == null||empty($r) ||!isset($r)) {//
            $return_errlogin_info['msg']="要加雙引號,逗號和{}";
            echo json_encode((array)$return_errlogin_info);
        } else {
            $validator = Validator::make(
                $r,
                [//驗證
                  'id'         => ['required','alpha_num','max:50'],
                ]
            );
            if ($validator->fails()) {
                $return_err=array_merge_recursive((array)$return_err, (array)$return_errmsg_info);
                foreach ($validator->messages()->all() as $message) {//直接validator取的錯誤值
                    $return_errmsg_info['msg']="";
                    $return_errmsg_info['msg'] .= $message;// .=疊加
                    $return_err=array_merge_recursive($return_err, (array)$return_errmsg_info['msg']);
                }
                echo json_encode((array)$return_err);
            } else {
                $usermail = Msgsub::where('id', $r['id'])->first();
                if (Gate::allows('update-post', $usermail)) {//查看是否有授權權限
                    $log['id']=$usermail['id'];
                    $log['msg']=$usermail['msg'];
                    $log['title']=$usermail['title'];
                    return json_encode((array)$log);//websocket還未設定刪除事件
                } else {
                    $return_errlogin_info['msg']="只能編輯自己的留言";
                    return json_encode((array)$return_errlogin_info);
                }
            }
        }
    }
    public function updatemsg(Request $request)
    {//更新
        $return_msg_info=array(
        "status" => "ture",
        "msg"    => "第"
      );
        $return_errmsg_info=array(
        "status" => "false",
        "msg"    => "錯誤訊息",
      );
        $return_err=array();
        set_time_limit(0);//設定運行時間
        //將資料由js轉成php
        $r=$request->all();
        if ($r == null||empty($r) ||!isset($r)) {//
            $return_errlogin_info['msg']="要加雙引號,逗號和{}";
            echo json_encode((array)$return_errlogin_info);
        } else {
            $validator = Validator::make(
                $r,
                [//驗證
            'id'        => ['required'],
            'title'     => ['required','alpha_num','max:50'],
            'msg'      => ['required','string'],
          ]
            );
            if ($validator->fails()) {
                $return_err=array_merge_recursive((array)$return_err, (array)$return_errmsg_info);
                foreach ($validator->messages()->all() as $message) {//直接validator取的錯誤值
                    $return_errmsg_info['msg']="";
                    $return_errmsg_info['msg'] .= $message;// .=疊加
                    $return_err=array_merge_recursive($return_err, (array)$return_errmsg_info['msg']);
                }

                echo json_encode((array)$return_err);
            } else {
                $pro = Msgsub::where('id', $r['id'])->update([
                  "title"  =>$r['title'],
                  "msg"    =>$r['msg']
                ]);
                $update_msg = Msgsub::where('id', $r['id'])->first();
                $return_updat['id']=$update_msg['id'];
                $return_updat['email']=$update_msg['email'];
                $return_updat['updated_at']=date('Y-m-d H:i:s');
                $return_updat['msg']=$update_msg['msg'];
                $return_updat['title']=$update_msg['title'];
                event(new \App\Events\UpMsgSub($update_msg));
                echo json_encode((array)$return_updat);
            }
        }
    }
    public function deletemsg(Request $request)
    {//刪除
        $return_msg_info=array(
          "status" => "ture",
          "msg"    => "第"
        );
        $return_errmsg_info=array(
          "status" => "false",
          "msg"    => "錯誤訊息",
        );
        $return_err=array();
        set_time_limit(0);//設定運行時間
        //將資料由js轉成php
        $r=$request->all();
        if ($r == null||empty($r) ||!isset($r)) {//
            $return_errlogin_info['msg']="要加雙引號,逗號和{}";
            echo json_encode((array)$return_errlogin_info);
        } else {
            $usermail = Msgsub::where('id', $r['id'])->first();
            if (Gate::allows('update-post', $usermail)) {//查看是否有授權權限
                $log=Msgsub::where('id', $r['id'])->delete();
                event(new \App\Events\DelMsgSub($r));
                return json_encode((array)$r);//websocket還未設定刪除事件
            } else {
                $return_errlogin_info['msg']="只能編輯自己的留言";
                return json_encode((array)$return_errlogin_info);
            }
        }
    }
}
