<?php
/**
 * Created by PhpStorm.
 * User: wcx
 * Date: 2018/7/4
 * Time: 14:57
 */

namespace app\index\controller;


use think\controller\Rest;

class User extends Rest
{

    public function index(){

        echo 123;
    }
    public function shangKanImg()
    {
        exit;
        $imgName=$_FILES['image']['name'];

        $imgName=substr($imgName,0,strrpos($imgName,"."));
        $imgName=explode("_",$imgName);

        if(count($imgName)<3) self::putResult(503);
        if($imgName[0]=='') self::putResult(503);
        $macNum=M("premises_elevator")->where(["machine_num"=>$imgName[0]])->find();
        if(empty($macNum)) self::putResult(301);
        $city=M("premises")->where(["id"=>$macNum["premises_id"]])->field("city")->find();
        if(date('w')==6 || date('w')==0){
            if($imgName[3]){
                // $begintime = date('w') == 1 ? strtotime(date('Ymd' , time())):strtotime(date('Y-m-d',(time()-((date('w')==0?7:date('w'))-1)*24*3600)));
                // $endtime = $begintime + 604000;//上周6到本周5
                $endtime=strtotime('last friday');
                $begintime=$endtime-6*24*3600;
            }else{
                // $begintime = date('w') == 1 ? strtotime(date('Ymd' , time())):strtotime(date('Y-m-d',(time()-((date('w')==0?7:date('w'))-1)*24*3600)));
                // $begintime =$begintime+(7*24*3600);//本周6到下周星期5
                // $endtime = $begintime + 604000;

                $endtime=strtotime('next friday');
                $begintime=$endtime-6*24*3600;
            }
        }else{
            if($imgName[3]){
                // $begintime = date('w') == 1 ? strtotime(date('Ymd' , time())):strtotime(date('Y-m-d',(time()-((date('w')==0?7:date('w'))-1)*24*3600)));
                // $begintime =$begintime-(7*24*3600);//上上周星期6到上周星期5
                // $endtime = $begintime + 604000;

                $endtime=strtotime('last friday');
                $begintime=$endtime-6*24*3600;
            }else{
                // $begintime = date('w') == 1 ? strtotime(date('Ymd' , time())):strtotime(date('Y-m-d',(time()-((date('w')==0?7:date('w'))-1)*24*3600)));
                // $endtime = $begintime + 604000;//上周6到本周5

                $endtime=strtotime('friday');
                $begintime=$endtime-6*24*3600;
            }
        }

        $order=M("order")->where(["name"=>$imgName[1],"begintime"=>['between',[$begintime,$endtime]],'status'=>'003',"city"=>$city["city"]])->field("id")->find();

        $orderb=M("order_b")->where(["name"=>$imgName[2],"begintime"=>['between',[$begintime,$endtime]],'status'=>'003',"city"=>$city["city"]])->field("id")->find();


        if(empty($order) && empty($orderb)){
            self::putResult(501);
        }


        $date=date("Ymd",time())."/";

        $result = self::ossUploadImg(self::$customerPath.$date);
        if(!$result){
            self::putResult(401);
        }
        $imgPath=self::$customerPath.$date.$result;
        $model=M();
        $model->startTrans(); //开启事务
        $success=0;
        if(!empty($order)){
            $data=[
                "order_id"=>$order["id"],
                "premises_id"=>$macNum["premises_id"],
                "elevator_id"=>$macNum["id"],
                "machine_num"=>$macNum["machine_num"],
                "img_path"=>$imgPath,
                "addman"=>"app",
                "addtime"=>time()
            ];
            $result=M("order_img")->add($data);
            if($result===false) $success++;
        }


        if(!empty($orderb)){
            $data=[
                "order_id"=>$orderb["id"],
                "machine_num"=>$macNum["machine_num"],
                "premises_id"=>$macNum["premises_id"],
                "elevator_id"=>$macNum["id"],
                "img_path"=>$imgPath,
                "addman"=>"app",
                "addtime"=>time()
            ];

            $result=M("order_b_img")->add($data);
            if($result===false) $successb++;
        }


        if(!empty($order) && !empty($orderb)){
            if($success==2){
                $model->rollback();
                return 9;
            }else{
                $model->commit();
                return 0;
            }
        }else{
            if($success==1){
                $model->rollback();
                return 9;
            }else{
                $model->commit();
                return 0;
            }
        }

    }
}