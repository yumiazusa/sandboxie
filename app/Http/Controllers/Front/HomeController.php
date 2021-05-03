<?php

namespace App\Http\Controllers\Front;

use App\Model\Admin\Entity;
use App\Repository\Admin\ContentRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Auth;

class HomeController extends BaseController
{
    public function index()
    {
        $entities = Entity::query()->External()->get();
        $res = DB::table('front_nav')->get();
        return view('welcome', compact('entities','res'));
    }

    public function content($entityId)
    {
        $entity = Entity::query()->External()->findOrFail($entityId);

        ContentRepository::setTable($entity->table_name);
        $contents = ContentRepository::paginate();

        return view('front.content.list', compact('entity', 'contents'));
    }

    public function nav()
    {
        // $entities = Entity::query()->External()->get();
        $user = Auth::guard('member')->user();
        if(!$user){
            return view('welcome', compact('entities'));
        }
        $res = DB::table('card')->where('parent_id',$user->id)->first();
        $data=unserialize($res->carddata);
        // dd($data);
        // dd($data);
        shuffle($data);
        dd($data);
        return view('front.share.nav', ['data'=>$data]);
        // return view('front.share.nav', ['entities'=>$entities,'carddata'=>$carddata,'cardposition'=>$cardposition]);
    }

    public function plan()
    {
        $user = Auth::guard('member')->user();
        if(!$user){
            return view('welcome', compact('entities'));
        }
        $res = DB::table('card5')->where('parent_id',$user->id)->first();
        $data=unserialize($res->carddata);
        shuffle($data);
        return view('front.share.plan', ['data'=>$data]);
    }

    public function strategy()
    {
        $user = Auth::guard('member')->user();
        if(!$user){
            return view('welcome', compact('entities'));
        }
        $res = DB::table('card4')->where('parent_id',$user->id)->first();
        $data=unserialize($res->carddata);
        $content=array_pop($data);
        shuffle($data);
        return view('front.share.strategy', ['data'=>$data,'content'=>$content]);
    }

    public function feeNav()
    {
        $entities = Entity::query()->External()->get();
        $user = Auth::guard('member')->user();
        if(!$user){
            return view('welcome', compact('entities'));
        }
        $res = DB::table('card1')->where('parent_id',$user->id)->first();
        $data=unserialize($res->carddata);
        // dd($data);
        $sharetb=array_pop($data);
        // $sharetb=array('sharetb'=>1);
        // dd($sharetb);
        shuffle($data);
        return view('front.share.feenav', ['data'=>$data,'sharetb'=>$sharetb]);
    }

     public function saleNav()
    {
        $entities = Entity::query()->External()->get();
        $user = Auth::guard('member')->user();
        if(!$user){
            return view('welcome', compact('entities'));
        }
        $res = DB::table('card2')->where('parent_id',$user->id)->first();
        $data=unserialize($res->carddata);
        $sharetb=array_pop($data);
        shuffle($data);
        return view('front.share.salenav', ['data'=>$data,'sharetb'=>$sharetb]);
    }

     public function purchaseNav()
    {
        $entities = Entity::query()->External()->get();
        $user = Auth::guard('member')->user();
        if(!$user){
            return view('welcome', compact('entities'));
        }
        $res = DB::table('card3')->where('parent_id',$user->id)->first();
        $data=unserialize($res->carddata);
        $sharetb=array_pop($data);
        shuffle($data);
        return view('front.share.purchasenav', ['data'=>$data,'sharetb'=>$sharetb]);
    }

    public function flowSheet()
    {
        $entities = Entity::query()->External()->get();
        $user = Auth::guard('member')->user();
        if(!$user){
            return view('welcome', compact('entities'));
        }
        return view('front.share.flowsheet');
    }

    //保存费用卡片
    public function saveFee(Request $request){
        // $data=$request->get();
        $lala = $request->route()->getName();
        $user = Auth::guard('member')->user();
        $da =$request->all();
        if(!$user){
             return false;
        }
            $data = $this->listArray($da);
            $res = $this->saveCard($user->id,$data,$lala);
        if(!$res){
            return [
                'code' => 2,
                'msg' => '保存失败',
                'reload' => true
            ];
         }
         return [
                'code' => 1,
                'msg' => '保存成功',
                'reload' => true
            ];

    }

    //保存销售卡片
    public function saveSale(Request $request){
        // $data=$request->get();
        $lala = $request->route()->getName();
        $user = Auth::guard('member')->user();
        $da =$request->all();
        if(!$user){
             return false;
        }

        if($lala == 'web::savesale'){
            $data = $this->listArray($da);
            $res = $this->saveCard($user->id,$data,$lala);
                    }
        if(!$res){
            return [
                'code' => 2,
                'msg' => '保存失败',
                'reload' => true
            ];
         }
         return [
                'code' => 1,
                'msg' => '保存成功',
                'reload' => true
            ];

    }

     //保存采购卡片
    public function savePurchase(Request $request){
        // $data=$request->get();
        $lala = $request->route()->getName();
        $user = Auth::guard('member')->user();
        $da =$request->all();
        if(!$user){
             return false;
        }

        if($lala == 'web::savepurchase'){
            $data = $this->listArray($da);
            $res = $this->saveCard($user->id,$data,$lala);
                    }
        if(!$res){
            return [
                'code' => 2,
                'msg' => '保存失败',
                'reload' => true
            ];
         }
         return [
                'code' => 1,
                'msg' => '保存成功',
                'reload' => true
            ];

    }


    //整理流程卡片AJAX传递的数组
    public function listArray($da){
         $sharetb=array_pop($da);
         $sharetb=array('sharetb' => (int)$sharetb);
         for ($i=0; $i < count($da['cid']); $i++) {
            $data[$i] = array(
                'cid'=>(int)$da['cid'][$i],
                'kind'=>$da['kind'][$i],
                'name'=>$da['name'][$i],
                'isshare'=>(int)$da['isshare'][$i],
                'sharename'=>$da['sharename'][$i],
                'top'=>(float)$da['top'][$i],
                'left'=>(float)$da['left'][$i],
                'shared'=>(int)$da['shared'][$i],
            );
        }
        array_push($data,$sharetb);
        return $data;
    }

     //保存战略卡片
    public function saveStrategy(Request $request){
        $lala = $request->route()->getName();
        $user = Auth::guard('member')->user();
        $da =$request->all();
        if(!$user){
             return false;
        }
        if($lala == 'web::savestrategy'){
            $data = $this->listArraystrategy($da,$lala);
            $res = $this->saveCard($user->id,$data,$lala);
        }elseif($lala == 'web::saveplan'){
            $data = $this->listArraystrategy($da,$lala);
            $res = $this->saveCard($user->id,$data,$lala);
            }
        if(!$res){
            return [
                'code' => 2,
                'msg' => '保存失败',
                'reload' => true
            ];
         }
         return [
                'code' => 1,
                'msg' => '保存成功',
                'reload' => true
            ];

    }

      //整理流程卡片AJAX传递的数组
    public function listArraystrategy($da,$kind){
         if($kind == 'web::savestrategy'){
            for ($i=0; $i < count($da['cid']); $i++) {
            $data[$i] = array(
                'cid'=>(int)$da['cid'][$i],
                'kind'=>$da['kind'][$i],
                'name'=>$da['name'][$i],
                'top'=>(float)$da['top'][$i],
                'left'=>(float)$da['left'][$i],
            );
        }
        $data['content']=array(
             "kind"=>"textarea",
             "name"=>$da['title'],
             "reason"=>$da['reason'],
            );
        }elseif($kind == 'web::saveplan'){
            for ($i=0; $i < count($da['cid']); $i++) {
            $data[$i] = array(
                'cid'=>(int)$da['cid'][$i],
                'kind'=>$da['kind'][$i],
                'name'=>$da['name'][$i],
                'top'=>(float)$da['top'][$i],
                'left'=>(float)$da['left'][$i],
            );
          }
        }
        return $data;
    }

    //重置流程卡片
    public function renav(Request $request)
    {
        $lala = $request->route()->getName();
        $user = Auth::guard('member')->user();
        if(!$user){
             return false;
        }
        if($lala == 'web::renav'){
            $data = $this->feeArray();
            $res = $this->saveCard($user->id,$data,$lala);
        }elseif($lala == 'web::resale'){
            $data = $this->saleArray();
            $res = $this->saveCard($user->id,$data,$lala);
        }elseif($lala == 'web::repurchase'){
            $data = $this->purchaseArray();
            $res = $this->saveCard($user->id,$data,$lala);
        }
        if(!$res){
            return [
                'code' => 2,
                'msg' => '重置失败',
                'reload' => true
            ];
         }
         return [
                'code' => 1,
                'msg' => '重置成功',
                'reload' => true
            ];
    }

    public function reFee(){
        $user = Auth::guard('member')->user();
        if(!$user){
             return false;
        }
        $data=$this->strategyArray();
        $data=serialize($data);
        $user=DB::table('card4')->pluck('id');
        foreach ($user as $k => $v) {
            $card=[
            'carddata' => $data,
            // 'cardposition' => serialize($cardposition),
            // 'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
         ];
         $res = DB::table('card4')->where('id',$v)->update($card);
        }
        if(!$res){
            return [
                'code' => 2,
                'msg' => '刷新失败',
                'reload' => true
            ];
         }
         return [
                'code' => 1,
                'msg' => '刷新成功',
                'reload' => true
            ];
    }

    //重置规划卡片
    public function replan(Request $request)
    {
        $lala = $request->route()->getName();
        $user = Auth::guard('member')->user();
        if(!$user){
             return false;
        }
        if($lala == 'web::restrategy'){
            $data = $this->strategyArray();
            $res = $this->saveCard($user->id,$data,$lala);
        }elseif($lala == 'web::replan'){
            $data = $this->planArray();
            $res = $this->saveCard($user->id,$data,$lala);
        }
        if(!$res){
            return [
                'code' => 2,
                'msg' => '重置失败',
                'reload' => true
            ];
         }
         return [
                'code' => 1,
                'msg' => '重置成功',
                'reload' => true
            ];
    }

    //保存卡片数据到数据库方法
    public function saveCard($id,$data,$kind){

         $card=[
            'parent_id' => $id,
            'carddata' => serialize($data),
            // 'cardposition' => serialize($cardposition),
            // 'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
         ];
         if($kind == 'web::savefee'){
            $res = DB::table('card1')->where('parent_id',$id)->update($card);
         }elseif($kind == 'web::savesale'){
            $res = DB::table('card2')->where('parent_id',$id)->update($card);
         }elseif($kind == 'web::savepurchase'){
            $res = DB::table('card3')->where('parent_id',$id)->update($card);
         }elseif($kind == 'web::savestrategy'){
            $res = DB::table('card4')->where('parent_id',$id)->update($card);
         }elseif($kind == 'web::saveplan'){
            $res = DB::table('card5')->where('parent_id',$id)->update($card);
         }elseif($kind == 'web::replan'){
            $res = DB::table('card5')->where('parent_id',$id)->update($card);
         }elseif($kind == 'web::renav'){
            $res = DB::table('card1')->where('parent_id',$id)->update($card);
         }elseif($kind == 'web::resale'){
            $res = DB::table('card2')->where('parent_id',$id)->update($card);
         }elseif($kind == 'web::repurchase'){
            $res = DB::table('card3')->where('parent_id',$id)->update($card);
         }elseif($kind == 'web::restrategy'){
            $res = DB::table('card4')->where('parent_id',$id)->update($card);
         }
         if(!$res){
            return false;
         }
            return true;
    }

    public function feeArray(){
        $carddata = array
        (
            1 =>array
            (
                "cid"=>"1",
                "kind"=>"people",
                "name"=>"业务人员",
                "isshare"=> 0,
                "sharename"=>0,
                "top"=> 8.80,
                "left"=> 13.33,
                "shared" => 0
            ),
             2 =>array
            (
                "cid"=>"2",
                "kind"=>"people",
                "name"=>"扫描员",
                "isshare"=> 0,
                "sharename"=>0,
                "top"=> 8.80,
                "left"=> 13.53,
                "shared" => 0
            ),
            3 =>array
            (
                "cid"=>"3",
                "kind"=>"people",
                "name"=>"部门经理",
                "isshare"=> 0,
                "sharename"=>0,
                "top"=> 8.80,
                "left"=> 13.73,
                "shared" => 0
            ),
            4 =>array
            (
                "cid"=>"4",
                "kind"=>"people",
                "name"=>"总经理",
                "isshare"=> 0,
                "sharename"=>0,
                "top"=> 8.80,
                "left"=> 13.93,
                "shared" => 0
            ),
            5 =>array
            (
                "cid"=>"5",
                "kind"=>"people",
                "name"=>"公司财务会计",
                "isshare"=> 1,
                "sharename"=>"本地业务财务",
                 "top"=> 8.80,
                "left"=> 14.13,
                "shared" => 1
            ),
            6 =>array
            (
                "cid"=>"6",
                "kind"=>"people",
                "name"=>"公司财务经理",
                "isshare"=> 0,
                "sharename"=>0,
                "top"=> 8.80,
                "left"=> 14.33,
                "shared" => 0
            ),
            7 =>array
            (
                "cid"=>"7",
                "kind"=>"people",
                "name"=>"",
                "isshare"=> 1,
                "sharename"=>"FSSC财务审核岗",
                "top"=> 8.80,
                "left"=> 14.53,
                "shared" => 3
            ),
            8 =>array
            (
                "cid"=>"8",
                "kind"=>"people",
                "name"=>"公司出纳",
                "isshare"=> 1,
                "sharename"=>"FSSC出纳岗",
                "top"=> 8.80,
                "left"=> 14.73,
                "shared" => 1
            ),
            9 =>array
            (
                "cid"=>"9",
                "kind"=>"people",
                "name"=>"公司总账会计",
                "isshare"=> 1,
                "sharename"=>"FSSC总账主管岗",
                "top"=> 8.80,
                "left"=> 14.93,
                "shared" => 1
            ),
            10 =>array
            (
                "cid"=>"10",
                "kind"=>"people",
                "name"=>"公司档案管理员",
                "isshare"=> 1,
                "sharename"=>"FSSC档案管理岗",
                "top"=> 8.80,
                "left"=> 15.13,
                "shared" => 1
            ),
            11 =>array
            (
                "cid"=>"11",
                "kind"=>"action",
                "name"=>"填单报账",
                "isshare"=> 0,
                "sharename"=>0,
                 "top"=> 32.20,
                "left"=> 13.33,
                "shared" => 0
            ),
            12 =>array
            (
                "cid"=>"12",
                "kind"=>"action",
                "name"=>"业务审批",
                "isshare"=> 0,
                "sharename"=>0,
                "top"=> 32.20,
                "left"=> 13.53,
                "shared" => 0
            ),
             13 =>array
            (
                "cid"=>"13",
                "kind"=>"action",
                "name"=>"财务审核",
                "isshare"=> 0,
                "sharename"=>0,
                 "top"=> 32.20,
                "left"=> 13.73,
                "shared" => 0
            ),
             14 =>array
            (
                "cid"=>"14",
                "kind"=>"action",
                "name"=>"业务审批",
                "isshare"=> 0,
                "sharename"=>0,
                "top"=> 32.20,
                "left"=> 13.93,
                "shared" => 0
            ),
             15 =>array
            (
                "cid"=>"15",
                "kind"=>"action",
                "name"=>"线下支付",
                "isshare"=> 1,
                "sharename"=>"线上支付",
                 "top"=> 32.20,
                "left"=> 14.13,
                "shared" => 1
            ),
             16 =>array
            (
                "cid"=>"16",
                "kind"=>"action",
                "name"=>"凭证审核",
                "isshare"=> 0,
                "sharename"=>0,
                "top"=> 32.20,
                "left"=> 14.33,
                "shared" => 0
            ),
             17 =>array
            (
                "cid"=>"17",
                "kind"=>"action",
                "name"=>"实物档案归档",
                "isshare"=> 1,
                "sharename"=>"电子档案归档",
                "top"=> 32.20,
                "left"=> 14.53,
                "shared" => 1
            ),
            18 =>array
            (
                "cid"=>"18",
                "kind"=>"list",
                "name"=>"实物单据",
                "isshare"=> 1,
                "sharename"=>"电子影像单据",
                "top"=> 53.50,
                "left"=> 13.33,
                "shared" => 1
            ),
            19 =>array
            (
                "cid"=>"19",
                "kind"=>"list",
                "name"=>"实物单据",
                "isshare"=> 1,
                "sharename"=>"电子影像单据",
                "top"=> 53.50,
                "left"=> 13.53,
                "shared" => 1
            ),
            20 =>array
            (
                "cid"=>"20",
                "kind"=>"list",
                "name"=>"实物凭证",
                "isshare"=> 1,
                "sharename"=>"电子凭证",
                "top"=> 53.50,
                "left"=> 13.73,
                "shared" => 1
            ),
            21 =>array
            (
                "cid"=>"21",
                "kind"=>"list",
                "name"=>"实物档案",
                "isshare"=> 1,
                "sharename"=>"电子档案",
                "top"=> 53.50,
                "left"=> 13.93,
                "shared" => 1
            ),
            22 =>array
            (
                "cid"=>"22",
                "kind"=>"tech",
                "name"=>"商旅服务平台",
                "isshare"=> 0,
                "sharename"=>0,
                "top"=> 76.35,
                "left"=> 13.33,
                "shared" => 0
            ),
            23 =>array
            (
                "cid"=>"23",
                "kind"=>"tech",
                "name"=>"电子发票",
                "isshare"=> 0,
                "sharename"=>0,
                "top"=> 76.35,
                "left"=> 13.53,
                "shared" => 0
            ),
            24 =>array
            (
                "cid"=>"24",
                "kind"=>"tech",
                "name"=>"企业财务系统",
                "isshare"=> 0,
                "sharename"=>0,
                "top"=> 76.35,
                "left"=> 13.73,
                "shared" => 0
            ),
            25 =>array
            (
                "cid"=>"25",
                "kind"=>"tech",
                "name"=>"影像管理系统",
                "isshare"=> 0,
                "sharename"=>0,
                "top"=> 76.35,
                "left"=> 13.93,
                "shared" => 0
            ),
            26 =>array
            (
                "cid"=>"26",
                "kind"=>"tech",
                "name"=>"财务共享服务平台",
                "isshare"=> 0,
                "sharename"=>0,
                 "top"=> 76.35,
                "left"=> 14.13,
                "shared" => 0
            ),
            27 =>array
            (
                "cid"=>"27",
                "kind"=>"tech",
                "name"=>"银企直联",
                "isshare"=> 0,
                "sharename"=>0,
                "top"=> 76.35,
                "left"=> 14.33,
                "shared" => 0
            ),
            28 =>array
            (
                "cid"=>"28",
                "kind"=>"action",
                "name"=>"凭证审核",
                "isshare"=> 0,
                "sharename"=>0,
                "top"=> 32.20,
                "left"=> 14.33,
                "shared" => 0
            ),
            29 =>array
            (
                "cid"=>"29",
                "kind"=>"list",
                "name"=>"实物凭证",
                "isshare"=> 1,
                "sharename"=>"电子凭证",
                "top"=> 53.50,
                "left"=> 13.73,
                "shared" => 1
            ),
            30 =>array("sharetb"=> 1)
        );
        return $carddata;
    }

    public function saleArray(){
         $carddata = array
        (
            1 =>array
            (
                "cid"=>"1",
                "kind"=>"people",
                "name"=>"销售人员",
                "isshare"=> 0,
                "sharename"=>0,
                "top"=> 8.80,
                "left"=> 13.33,
                "shared" => 0
            ),
             2 =>array
            (
                "cid"=>"2",
                "kind"=>"people",
                "name"=>"扫描员",
                "isshare"=> 0,
                "sharename"=>0,
                "top"=> 8.80,
                "left"=> 13.53,
                "shared" => 0
            ),
            3 =>array
            (
                "cid"=>"3",
                "kind"=>"people",
                "name"=>"销售经理",
                "isshare"=> 0,
                "sharename"=>0,
                "top"=> 8.80,
                "left"=> 13.73,
                "shared" => 0
            ),
            4 =>array
            (
                "cid"=>"4",
                "kind"=>"people",
                "name"=>"公司销售会计",
                "isshare"=> 1,
                "sharename"=>"本地业务财务",
                "top"=> 8.80,
                "left"=> 13.93,
                "shared" => 1
            ),
            5 =>array
            (
                "cid"=>"5",
                "kind"=>"people",
                "name"=>"销售人员",
                "isshare"=> 0,
                "sharename"=>0,
                "top"=> 8.80,
                "left"=> 14.13,
                "shared" => 0
            ),
            6 =>array
            (
                "cid"=>"6",
                "kind"=>"people",
                "name"=>"销售经理",
                "isshare"=> 0,
                "sharename"=>0,
                "top"=> 8.80,
                "left"=> 14.33,
                "shared" => 0
            ),
            7 =>array
            (
                "cid"=>"7",
                "kind"=>"people",
                "name"=>0,
                "isshare"=> 1,
                "sharename"=>"FSSC财务审核岗",
                "top"=> 8.80,
                "left"=> 14.53,
                "shared" => 3
            ),
            8 =>array
            (
                "cid"=>"8",
                "kind"=>"people",
                "name"=>"公司仓管员",
                "isshare"=> 0,
                "sharename"=>0,
                 "top"=> 8.80,
                "left"=> 14.73,
                "shared" => 0
            ),
            9 =>array
            (
                "cid"=>"9",
                "kind"=>"people",
                "name"=>"公司销售会计",
                "isshare"=> 1,
                "sharename"=>"本地业务财务",
                 "top"=> 8.80,
                "left"=> 14.93,
                "shared" => 1
            ),
            10 =>array
            (
                "cid"=>"10",
                "kind"=>"people",
                "name"=>"公司档案管理员",
                "isshare"=> 1,
                "sharename"=>"FSSC档案管理岗",
                 "top"=> 8.80,
                "left"=> 15.13,
                "shared" => 1
            ),
            11 =>array
            (
                "cid"=>"11",
                "kind"=>"people",
                "name"=>"公司财务经理",
                "isshare"=> 0,
                "sharename"=>0,
                 "top"=> 8.80,
                "left"=> 15.33,
                "shared" => 0
            ),
            12 =>array
            (
                "cid"=>"12",
                "kind"=>"people",
                "name"=>"公司总账会计",
                "isshare"=> 1,
                "sharename"=>"FSSC总账主管岗",
                 "top"=> 8.80,
                "left"=> 15.53,
                "shared" => 1
            ),
             13 =>array
            (
                "cid"=>"13",
                "kind"=>"people",
                "name"=>"公司销售会计",
                "isshare"=> 1,
                "sharename"=>"本地业务财务",
                 "top"=> 8.80,
                "left"=> 15.73,
                "shared" => 1
            ),
             14 =>array
            (
                "cid"=>"14",
                "kind"=>"people",
                "name"=>"公司财务经理",
                "isshare"=> 0,
                "sharename"=>0,
                 "top"=> 8.80,
                "left"=> 15.93,
                "shared" => 0
            ),
             15 =>array
            (
                "cid"=>"15",
                "kind"=>"people",
                "name"=>0,
                "isshare"=> 1,
                "sharename"=>"FSSC财务审核岗",
                "top"=> 8.80,
                "left"=> 16.13,
                "shared" => 3
            ),
             16 =>array
            (
                "cid"=>"16",
                "kind"=>"people",
                "name"=>"公司出纳",
                "isshare"=> 1,
                "sharename"=>"FSSC出纳岗",
                 "top"=> 8.80,
                "left"=> 16.33,
                "shared" => 1
            ),
             17 =>array
            (
                "cid"=>"17",
                "kind"=>"people",
                "name"=>"公司总账会计",
                "isshare"=> 1,
                "sharename"=>"FSSC总账主管岗",
                 "top"=> 8.80,
                "left"=> 16.53,
                "shared" => 1
            ),
            18 =>array
            (
                "cid"=>"18",
                "kind"=>"action",
                "name"=>"签订销售合同",
                "isshare"=> 0,
                "sharename"=>0,
                 "top"=> 32.20,
                "left"=> 13.33,
                "shared" => 0
            ),
            19 =>array
            (
                "cid"=>"19",
                "kind"=>"action",
                "name"=>"审批销售合同",
                "isshare"=> 0,
                "sharename"=>0,
                 "top"=> 32.20,
                "left"=> 13.53,
                "shared" => 0
            ),
            20 =>array
            (
                "cid"=>"20",
                "kind"=>"action",
                "name"=>"销售财务审核",
                "isshare"=> 0,
                "sharename"=>0,
                 "top"=> 32.20,
                "left"=> 13.73,
                "shared" => 0
            ),
            21 =>array
            (
                "cid"=>"21",
                "kind"=>"action",
                "name"=>"生成销售订单",
                "isshare"=> 0,
                "sharename"=>0,
                 "top"=> 32.20,
                "left"=> 13.93,
                "shared" => 0
            ),
            22 =>array
            (
                "cid"=>"22",
                "kind"=>"action",
                "name"=>"审核销售订单",
                "isshare"=> 0,
                "sharename"=>0,
                 "top"=> 32.20,
                "left"=> 14.13,
                "shared" => 0
            ),
            23 =>array
            (
                "cid"=>"23",
                "kind"=>"action",
                "name"=>"办理销售发货",
                "isshare"=> 0,
                "sharename"=>0,
                 "top"=> 32.20,
                "left"=> 14.33,
                "shared" => 0
            ),
            24 =>array
            (
                "cid"=>"24",
                "kind"=>"action",
                "name"=>"开具销售发票",
                "isshare"=> 0,
                "sharename"=>0,
                "top"=> 32.20,
                "left"=> 14.53,
                "shared" => 0
            ),
            25 =>array
            (
                "cid"=>"25",
                "kind"=>"action",
                "name"=>"生成应收单",
                "isshare"=> 0,
                "sharename"=>0,
                "top"=> 32.20,
                "left"=> 14.73,
                "shared" => 0
            ),
            26 =>array
            (
                "cid"=>"26",
                "kind"=>"action",
                "name"=>"审核应收单",
                "isshare"=> 0,
                "sharename"=>0,
                 "top"=> 32.20,
                "left"=> 14.93,
                "shared" => 0
            ),
            27 =>array
            (
                "cid"=>"27",
                "kind"=>"action",
                "name"=>"生成转账凭证",
                "isshare"=> 0,
                "sharename"=>0,
                 "top"=> 32.20,
                "left"=> 15.13,
                "shared" => 0
            ),
             28 =>array
            (
                "cid"=>"28",
                "kind"=>"action",
                "name"=>"审核转账凭证",
                "isshare"=> 0,
                "sharename"=>0,
                "top"=> 32.20,
                "left"=> 15.33,
                "shared" => 0
            ),
             29 =>array
            (
                "cid"=>"29",
                "kind"=>"action",
                "name"=>"生成收款单",
                "isshare"=> 0,
                "sharename"=>0,
                "top"=> 32.20,
                "left"=> 15.53,
                "shared" => 0
            ),
             30 =>array
            (
                "cid"=>"30",
                "kind"=>"action",
                "name"=>"审核收款单",
                "isshare"=> 0,
                "sharename"=>0,
                "top"=> 32.20,
                "left"=> 15.73,
                "shared" => 0
            ),
             31 =>array
            (
                "cid"=>"31",
                "kind"=>"action",
                "name"=>"生成收款凭证",
                "isshare"=> 0,
                "sharename"=>0,
                "top"=> 32.20,
                "left"=> 15.93,
                "shared" => 0
            ),
             32 =>array
            (
                "cid"=>"32",
                "kind"=>"action",
                "name"=>"确认收款结算",
                "isshare"=> 1,
                "sharename"=>"线上集中结算",
               "top"=> 32.20,
                "left"=> 16.13,
                "shared" => 1
            ),
             33 =>array
            (
                "cid"=>"33",
                "kind"=>"action",
                "name"=>"凭证审核",
                "isshare"=> 0,
                "sharename"=>0,
                "top"=> 32.20,
                "left"=> 16.33,
                "shared" => 0
            ),
             34 =>array
            (
                "cid"=>"34",
                "kind"=>"action",
                "name"=>"实物档案归档",
                "isshare"=> 1,
                "sharename"=>"电子档案归档",
                "top"=> 32.20,
                "left"=> 16.53,
                "shared" => 1
            ),
             35 =>array
            (
                "cid"=>"35",
                "kind"=>"list",
                "name"=>"实物合同",
                "isshare"=> 1,
                "sharename"=>"电子合同影像",
                "top"=> 53.50,
                "left"=> 13.33,
                "shared" => 1
            ),
             36 =>array
            (
                "cid"=>"36",
                "kind"=>"list",
                "name"=>"实物销售订单",
                "isshare"=> 1,
                "sharename"=>"电子销售单据",
               "top"=> 53.50,
                "left"=> 13.53,
                "shared" => 1
            ),
             37 =>array
            (
                "cid"=>"37",
                "kind"=>"list",
                "name"=>"实物出库单",
                "isshare"=> 1,
                "sharename"=>"电子出库单",
                "top"=> 53.50,
                "left"=> 13.93,
                "shared" => 1
            ),
             38 =>array
            (
                "cid"=>"38",
                "kind"=>"list",
                "name"=>"实物发货单",
                "isshare"=> 1,
                "sharename"=>"电子发货单",
                "top"=> 53.50,
                "left"=> 14.13,
                "shared" => 1
            ),
             39 =>array
            (
                "cid"=>"39",
                "kind"=>"list",
                "name"=>"实物发票",
                "isshare"=> 1,
                "sharename"=>"电子发票",
                "top"=> 53.50,
                "left"=> 14.33,
                "shared" => 1
            ),
             40 =>array
            (
                "cid"=>"40",
                "kind"=>"list",
                "name"=>"实物应收单",
                "isshare"=> 1,
                "sharename"=>"电子应收单",
                "top"=> 53.50,
                "left"=> 14.53,
                "shared" => 1
            ),
             41 =>array
            (
                "cid"=>"41",
                "kind"=>"list",
                "name"=>"实物凭证",
                "isshare"=> 1,
                "sharename"=>"电子凭证",
               "top"=> 53.50,
                "left"=> 14.73,
                "shared" => 1
            ),
             42 =>array
            (
                "cid"=>"42",
                "kind"=>"list",
                "name"=>"实物收款单",
                "isshare"=> 1,
                "sharename"=>"电子收款单",
                "top"=> 53.50,
                "left"=> 14.93,
                "shared" => 1
            ),
             43 =>array
            (
                "cid"=>"43",
                "kind"=>"list",
                "name"=>"实物凭证",
                "isshare"=> 1,
                "sharename"=>"电子凭证",
                "top"=> 53.50,
                "left"=> 15.13,
                "shared" => 1
            ),
             44 =>array
            (
                "cid"=>"44",
                "kind"=>"list",
                "name"=>"实物档案",
                "isshare"=> 1,
                "sharename"=>"电子档案",
                "top"=> 53.50,
                "left"=> 15.33,
                "shared" => 1
            ),
             45 =>array
            (
                "cid"=>"45",
                "kind"=>"tech",
                "name"=>"企业财务系统",
                "isshare"=> 0,
                "sharename"=>0,
                "top"=> 76.35,
                "left"=> 13.33,
                "shared" => 0
            ),
             46 =>array
            (
                "cid"=>"46",
                "kind"=>"tech",
                "name"=>"影像管理系统",
                "isshare"=> 0,
                "sharename"=>0,
                "top"=> 76.35,
                "left"=> 13.53,
                "shared" => 0
            ),
             47 =>array
            (
                "cid"=>"47",
                "kind"=>"tech",
                "name"=>"企业ERP管理平台",
                "isshare"=> 0,
                "sharename"=>0,
                "top"=> 76.35,
                "left"=> 13.73,
                "shared" => 0
            ),
             48 =>array
            (
                "cid"=>"48",
                "kind"=>"tech",
                "name"=>"财务共享服务平台",
                "isshare"=> 0,
                "sharename"=>0,
                "top"=> 76.35,
                "left"=> 13.93,
                "shared" => 0
            ),
             49 =>array
            (
                "cid"=>"49",
                "kind"=>"tech",
                "name"=>"财务共享服务平台",
                "isshare"=> 0,
                "sharename"=>0,
                "top"=> 76.35,
                "left"=> 14.13,
                "shared" => 0
            ),
             50 =>array
            (
                "cid"=>"50",
                "kind"=>"tech",
                "name"=>"银企直联",
                "isshare"=> 0,
                "sharename"=>0,
                "top"=> 76.35,
                "left"=> 14.33,
                "shared" => 0
            ),
             51 =>array
            (
                "cid"=>"51",
                "kind"=>"tech",
                "name"=>"电子发票",
                "isshare"=> 0,
                "sharename"=>0,
                "top"=> 76.35,
                "left"=> 14.53,
                "shared" => 0
            ),
             52 =>array("sharetb"=> 1)
        );
        return $carddata;
    }

    public function purchaseArray(){
        $carddata = array
        (
            1 =>array
            (
                "cid"=>"1",
                "kind"=>"people",
                "name"=>"采购员",
                "isshare"=> 0,
                "sharename"=>0,
                "top"=> 8.80,
                "left"=> 13.33,
                "shared" => 0
            ),
             2 =>array
            (
                "cid"=>"2",
                "kind"=>"people",
                "name"=>"采购经理",
                "isshare"=> 0,
                "sharename"=>0,
                "top"=> 8.80,
                "left"=> 13.53,
                "shared" => 0
            ),
            3 =>array
            (
                "cid"=>"3",
                "kind"=>"people",
                "name"=>"本地档案管理岗",
                "isshare"=> 1,
                "sharename"=>"FSSC档案管理岗",
                "top"=> 8.80,
                "left"=> 13.73,
                "shared" => 1
            ),
            4 =>array
            (
                "cid"=>"4",
                "kind"=>"people",
                "name"=>"采购员",
                "isshare"=> 0,
                "sharename"=>0,
                "top"=> 8.80,
                "left"=> 13.93,
                "shared" => 0
            ),
            5 =>array
            (
                "cid"=>"5",
                "kind"=>"people",
                "name"=>"采购经理",
                "isshare"=> 0,
                "sharename"=>0,
                "top"=> 8.80,
                "left"=> 14.13,
                "shared" => 0
            ),
            6 =>array
            (
                "cid"=>"6",
                "kind"=>"people",
                "name"=>"采购员",
                "isshare"=> 0,
                "sharename"=>0,
               "top"=> 8.80,
                "left"=> 14.33,
                "shared" => 0
            ),
            7 =>array
            (
                "cid"=>"7",
                "kind"=>"people",
                "name"=>"采购经理",
                "isshare"=> 0,
                "sharename"=>0,
               "top"=> 8.80,
                "left"=> 14.53,
                "shared" => 0
            ),
            8 =>array
            (
                "cid"=>"8",
                "kind"=>"people",
                "name"=>"财务经理",
                "isshare"=> 0,
                "sharename"=>0,
                "top"=> 8.80,
                "left"=> 14.73,
                "shared" => 0
            ),
            9 =>array
            (
                "cid"=>"9",
                "kind"=>"people",
                "name"=>"公司存货会计",
                "isshare"=> 1,
                "sharename"=>"本地业务财务",
                "top"=> 8.80,
                "left"=> 14.93,
                "shared" => 1
            ),
            10 =>array
            (
                "cid"=>"10",
                "kind"=>"people",
                "name"=>"公司档案管理员",
                "isshare"=> 1,
                "sharename"=>"FSSC档案管理岗",
                "top"=> 8.80,
                "left"=> 15.13,
                "shared" => 1
            ),
            11 =>array
            (
                "cid"=>"11",
                "kind"=>"people",
                "name"=>"采购员",
                "isshare"=> 0,
                "sharename"=>0,
                "top"=> 8.80,
                "left"=> 15.33,
                "shared" => 0
            ),
            12 =>array
            (
                "cid"=>"12",
                "kind"=>"people",
                "name"=>"采购经理",
                "isshare"=> 0,
                "sharename"=>0,
                "top"=> 8.80,
                "left"=> 15.53,
                "shared" => 0
            ),
             13 =>array
            (
                "cid"=>"13",
                "kind"=>"people",
                "name"=>"仓管员",
                "isshare"=> 0,
                "sharename"=>0,
                "top"=> 8.80,
                "left"=> 15.73,
                "shared" => 0
            ),
             14 =>array
            (
                "cid"=>"14",
                "kind"=>"people",
                "name"=>"质检员",
                "isshare"=> 0,
                "sharename"=>0,
               "top"=> 8.80,
                "left"=> 15.93,
                "shared" => 0
            ),
             15 =>array
            (
                "cid"=>"15",
                "kind"=>"people",
                "name"=>"仓管员",
                "isshare"=> 0,
                "sharename"=>0,
                "top"=> 8.80,
                "left"=> 16.13,
                "shared" => 0
            ),
             16 =>array
            (
                "cid"=>"16",
                "kind"=>"people",
                "name"=>"公司存货会计",
                "isshare"=> 1,
                "sharename"=>"本地业务财务",
                 "top"=> 8.80,
                "left"=> 16.33,
                "shared" => 1
            ),
             17 =>array
            (
                "cid"=>"17",
                "kind"=>"people",
                "name"=>"财务经理",
                "isshare"=> 0,
                "sharename"=>0,
                 "top"=> 8.80,
                "left"=> 16.53,
                "shared" => 0
            ),
             18 =>array
            (
                "cid"=>"18",
                "kind"=>"people",
                "name"=>"",
                "isshare"=> 1,
                "sharename"=>"FSSC应付审核岗",
                 "top"=> 8.80,
                "left"=> 16.73,
                "shared" => 3
            ),
             19 =>array
            (
                "cid"=>"19",
                "kind"=>"people",
                "name"=>"公司总账会计",
                "isshare"=> 1,
                "sharename"=>"FSSC总账主管岗",
                "top"=> 8.80,
                "left"=> 16.93,
                "shared" => 1
            ),
             20 =>array
            (
                "cid"=>"20",
                "kind"=>"people",
                "name"=>"公司存货会计",
                "isshare"=> 1,
                "sharename"=>"本地业务财务",
                 "top"=> 8.80,
                "left"=> 17.13,
                "shared" => 1
            ),
             21 =>array
            (
                "cid"=>"21",
                "kind"=>"people",
                "name"=>"财务经理",
                "isshare"=> 0,
                "sharename"=>0,
                "top"=> 8.80,
                "left"=> 17.33,
                "shared" => 0
            ),
             22 =>array
            (
                "cid"=>"22",
                "kind"=>"people",
                "name"=>"公司存货会计",
                "isshare"=> 1,
                "sharename"=>"本地业务财务",
                 "top"=> 8.80,
                "left"=> 17.53,
                "shared" => 1
            ),
             23 =>array
            (
                "cid"=>"23",
                "kind"=>"people",
                "name"=>"",
                "isshare"=> 1,
                "sharename"=>"FSSC应付审核岗",
                 "top"=> 8.80,
                "left"=> 17.73,
                "shared" => 3
            ),
             24 =>array
            (
                "cid"=>"24",
                "kind"=>"people",
                "name"=>"公司出纳",
                "isshare"=> 1,
                "sharename"=>"FSSC出纳岗",
               "top"=> 8.80,
                "left"=> 17.93,
                "shared" => 1
            ),
             25 =>array
            (
                "cid"=>"25",
                "kind"=>"people",
                "name"=>"公司总账会计",
                "isshare"=> 1,
                "sharename"=>"FSSC总账主管岗",
                "top"=> 8.80,
                "left"=> 18.13,
                "shared" => 1
            ),
             26 =>array
            (
                "cid"=>"26",
                "kind"=>"action",
                "name"=>"选择/上报供应商",
                "isshare"=> 0,
                "sharename"=>0,
                 "top"=> 32.20,
                "left"=> 13.33,
                "shared" => 0
            ),
             27 =>array
            (
                "cid"=>"27",
                "kind"=>"action",
                "name"=>"审批供应商申请单",
                "isshare"=> 0,
                "sharename"=>0,
                 "top"=> 32.20,
                "left"=> 13.53,
                "shared" => 0
            ),
             28 =>array
            (
                "cid"=>"28",
                "kind"=>"action",
                "name"=>"归档供应商档案",
                "isshare"=> 0,
                "sharename"=>0,
                 "top"=> 32.20,
                "left"=> 13.73,
                "shared" => 0
            ),
             29 =>array
            (
                "cid"=>"29",
                "kind"=>"action",
                "name"=>"询价比价",
                "isshare"=> 0,
                "sharename"=>0,
                 "top"=> 32.20,
                "left"=> 13.93,
                "shared" => 0
            ),
             30 =>array
            (
                "cid"=>"30",
                "kind"=>"action",
                "name"=>"确定供应商/上报采购价额",
                "isshare"=> 0,
                "sharename"=>0,
                 "top"=> 32.20,
                "left"=> 14.13,
                "shared" => 0
            ),
             31 =>array
            (
                "cid"=>"31",
                "kind"=>"action",
                "name"=>"审批供应商/审批采购价格",
                "isshare"=> 0,
                "sharename"=>0,
                "top"=> 32.20,
                "left"=> 14.33,
                "shared" => 0
            ),
             32 =>array
            (
                "cid"=>"32",
                "kind"=>"action",
                "name"=>"签订采购合同",
                "isshare"=> 0,
                "sharename"=>0,
                "top"=> 32.20,
                "left"=> 14.53,
                "shared" => 0
            ),
             33 =>array
            (
                "cid"=>"33",
                "kind"=>"action",
                "name"=>"审批采购合同",
                "isshare"=> 0,
                "sharename"=>0,
                "top"=> 32.20,
                "left"=> 14.73,
                "shared" => 0
            ),
             34 =>array
            (
                "cid"=>"34",
                "kind"=>"action",
                "name"=>"采购合同财务审核",
                "isshare"=> 0,
                "sharename"=>0,
                "top"=> 32.20,
                "left"=> 14.93,
                "shared" => 0
            ),
             35 =>array
            (
                "cid"=>"35",
                "kind"=>"action",
                "name"=>"采购合同归档",
                "isshare"=> 0,
                "sharename"=>0,
                "top"=> 32.20,
                "left"=> 15.13,
                "shared" => 0
            ),
             36 =>array
            (
                "cid"=>"28",
                "kind"=>"action",
                "name"=>"签署采购订单",
                "isshare"=> 0,
                "sharename"=>0,
                "top"=> 32.20,
                "left"=> 15.33,
                "shared" => 0
            ),
             37 =>array
            (
                "cid"=>"37",
                "kind"=>"action",
                "name"=>"审批采购订单",
                "isshare"=> 0,
                "sharename"=>0,
                "top"=> 32.20,
                "left"=> 15.53,
                "shared" => 0
            ),
             38 =>array
            (
                "cid"=>"38",
                "kind"=>"action",
                "name"=>"办理采购/到货",
                "isshare"=> 0,
                "sharename"=>0,
               "top"=> 32.20,
                "left"=> 15.73,
                "shared" => 0
            ),
             39 =>array
            (
                "cid"=>"39",
                "kind"=>"action",
                "name"=>"到库质检",
                "isshare"=> 0,
                "sharename"=>0,
                "top"=> 32.20,
                "left"=> 15.93,
                "shared" => 0
            ),
             40 =>array
            (
                "cid"=>"40",
                "kind"=>"action",
                "name"=>"入库",
                "isshare"=> 0,
                "sharename"=>0,
                "top"=> 32.20,
                "left"=> 16.13,
                "shared" => 0
            ),
             41 =>array
            (
                "cid"=>"41",
                "kind"=>"action",
                "name"=>"录入采购发票/生成应付单",
                "isshare"=> 0,
                "sharename"=>0,
                "top"=> 32.20,
                "left"=> 16.33,
                "shared" => 0
            ),
             42 =>array
            (
                "cid"=>"42",
                "kind"=>"action",
                "name"=>"审批应付单",
                "isshare"=> 0,
                "sharename"=>0,
                "top"=> 32.20,
                "left"=> 16.53,
                "shared" => 0
            ),
             43 =>array
            (
                "cid"=>"43",
                "kind"=>"action",
                "name"=>"生成转账凭证",
                "isshare"=> 0,
                "sharename"=>0,
                "top"=> 32.20,
                "left"=> 16.73,
                "shared" => 0
            ),
             44 =>array
            (
                "cid"=>"44",
                "kind"=>"action",
                "name"=>"审核转账凭证",
                "isshare"=> 0,
                "sharename"=>0,
                "top"=> 32.20,
                "left"=> 16.93,
                "shared" => 0
            ),
             45 =>array
            (
                "cid"=>"45",
                "kind"=>"action",
                "name"=>"提交付款单",
                "isshare"=> 0,
                "sharename"=>0,
                "top"=> 32.20,
                "left"=> 17.13,
                "shared" => 0
            ),
             46 =>array
            (
                "cid"=>"46",
                "kind"=>"action",
                "name"=>"审核付款单",
                "isshare"=> 0,
                "sharename"=>0,
                "top"=> 32.20,
                "left"=> 17.33,
                "shared" => 0
            ),
             47 =>array
            (
                "cid"=>"47",
                "kind"=>"action",
                "name"=>"生成付款凭证",
                "isshare"=> 0,
                "sharename"=>0,
                "top"=> 32.20,
                "left"=> 17.53,
                "shared" => 0
            ),
              48 =>array
            (
                "cid"=>"48",
                "kind"=>"action",
                "name"=>"支付应付款",
                "isshare"=> 1,
                "sharename"=>"线上支付",
                "top"=> 32.20,
                "left"=> 17.73,
                "shared" => 1
            ),
             49 =>array
            (
                "cid"=>"49",
                "kind"=>"action",
                "name"=>"审核付款凭证",
                "isshare"=> 0,
                "sharename"=>0,
                "top"=> 32.20,
                "left"=> 17.93,
                "shared" => 0
            ),

             50 =>array
            (
                "cid"=>"50",
                "kind"=>"list",
                "name"=>"实物供应商申请单",
                "isshare"=> 1,
                "sharename"=>"电子供应商申请单",
                "top"=> 53.50,
                "left"=> 13.33,
                "shared" => 1
            ),
             51 =>array
            (
                "cid"=>"51",
                "kind"=>"list",
                "name"=>"实物供应商资质材料",
                "isshare"=> 1,
                "sharename"=>"电子供应商资质材料",
                "top"=> 53.50,
                "left"=> 13.53,
                "shared" => 1
            ),
             52 =>array
            (
                "cid"=>"52",
                "kind"=>"list",
                "name"=>"实物档案",
                "isshare"=> 1,
                "sharename"=>"电子档案",
               "top"=> 53.50,
                "left"=> 13.73,
                "shared" => 1
            ),
             53 =>array
            (
                "cid"=>"53",
                "kind"=>"list",
                "name"=>"实物询报价单",
                "isshare"=> 1,
                "sharename"=>"电子询报价单",
                "top"=> 53.50,
                "left"=> 13.93,
                "shared" => 1
            ),
             54 =>array
            (
                "cid"=>"54",
                "kind"=>"list",
                "name"=>"实物价格审批单",
                "isshare"=> 1,
                "sharename"=>"电子价格审批单",
                "top"=> 53.50,
                "left"=> 14.13,
                "shared" => 1
            ),
             55 =>array
            (
                "cid"=>"55",
                "kind"=>"list",
                "name"=>"实物采购合同",
                "isshare"=> 1,
                "sharename"=>"电子采购合同",
                "top"=> 53.50,
                "left"=> 14.33,
                "shared" => 1
            ),
             56 =>array
            (
                "cid"=>"56",
                "kind"=>"list",
                "name"=>"实物采购订单",
                "isshare"=> 1,
                "sharename"=>"电子采购订单",
                "top"=> 53.50,
                "left"=> 14.53,
                "shared" => 1
            ),
             57 =>array
            (
                "cid"=>"57",
                "kind"=>"list",
                "name"=>"实物采购入库单",
                "isshare"=> 1,
                "sharename"=>"电子采购入库单",
                "top"=> 53.50,
                "left"=> 14.73,
                "shared" => 1
            ),
             58 =>array
            (
                "cid"=>"58",
                "kind"=>"list",
                "name"=>"实物发票",
                "isshare"=> 1,
                "sharename"=>"电子发票",
                "top"=> 53.50,
                "left"=> 14.93,
                "shared" => 1
            ),
             59 =>array
            (
                "cid"=>"59",
                "kind"=>"list",
                "name"=>"实物凭证",
                "isshare"=> 1,
                "sharename"=>"电子凭证",
                "top"=> 53.50,
                "left"=> 15.13,
                "shared" => 1
            ),
             60 =>array
            (
                "cid"=>"60",
                "kind"=>"list",
                "name"=>"实物应收单",
                "isshare"=> 1,
                "sharename"=>"电子应收单",
                 "top"=> 53.50,
                "left"=> 15.33,
                "shared" => 1
            ),
             61 =>array
            (
                "cid"=>"61",
                "kind"=>"list",
                "name"=>"实物付款单",
                "isshare"=> 1,
                "sharename"=>"电子付款单",
                 "top"=> 53.50,
                "left"=> 15.53,
                "shared" => 1
            ),
             62 =>array
            (
                "cid"=>"62",
                "kind"=>"list",
                "name"=>"实物凭证",
                "isshare"=> 1,
                "sharename"=>"电子凭证",
                 "top"=> 53.50,
                "left"=> 15.73,
                "shared" => 1
            ),
             63 =>array
            (
                "cid"=>"63",
                "kind"=>"list",
                "name"=>"实物档案",
                "isshare"=> 1,
                "sharename"=>"电子档案",
                 "top"=> 53.50,
                "left"=> 15.93,
                "shared" => 1
            ),
             64 =>array
            (
                "cid"=>"64",
                "kind"=>"tech",
                "name"=>"企业财务系统",
                "isshare"=> 0,
                "sharename"=>0,
                "top"=> 76.35,
                "left"=> 13.33,
                "shared" => 0
            ),
             65 =>array
            (
                "cid"=>"65",
                "kind"=>"tech",
                "name"=>"影像管理系统",
                "isshare"=> 0,
                "sharename"=>0,
                "top"=> 76.35,
                "left"=> 13.53,
                "shared" => 0
            ),
             66 =>array
            (
                "cid"=>"66",
                "kind"=>"tech",
                "name"=>"企业ERP管理平台",
                "isshare"=> 0,
                "sharename"=>0,
                "top"=> 76.35,
                "left"=> 13.73,
                "shared" => 0
            ),
             67 =>array
            (
                "cid"=>"67",
                "kind"=>"tech",
                "name"=>"财务共享服务平台",
                "isshare"=> 0,
                "sharename"=>0,
                "top"=> 76.35,
                "left"=> 13.93,
                "shared" => 0
            ),
             68 =>array
            (
                "cid"=>"68",
                "kind"=>"tech",
                "name"=>"财务共享服务平台",
                "isshare"=> 0,
                "sharename"=>0,
               "top"=> 76.35,
                "left"=> 14.13,
                "shared" => 0
            ),
             69 =>array
            (
                "cid"=>"69",
                "kind"=>"tech",
                "name"=>"银企直联",
                "isshare"=> 0,
                "sharename"=>0,
                "top"=> 76.35,
                "left"=> 14.33,
                "shared" => 0
            ),
             70 =>array
            (
                "cid"=>"71",
                "kind"=>"tech",
                "name"=>"电子发票",
                "isshare"=> 0,
                "sharename"=>0,
                "top"=> 76.35,
                "left"=> 14.53,
                "shared" => 0
            ),
             71 =>array("sharetb"=> 1)
        );
        return $carddata;
    }

     public function strategyArray(){
         $carddata = array
        (
            1 =>array
            (
                "cid"=>"1",
                "kind"=>"function",
                "name"=>"成本中心",
                "top"=> 66.05,
                "left"=> 20.1,
            ),
            2 =>array
            (
                "cid"=>"2",
                "kind"=>"function",
                "name"=>"利润中心",
                "top"=> 66.05,
                "left"=> 23,
            ),
            3 =>array
            (
                "cid"=>"3",
                "kind"=>"function",
                "name"=>"财务服务公司",
                "top"=> 66.05,
                "left"=> 26,
            ),
            4 =>array
            (
                "cid"=>"4",
                "kind"=>"pattern",
                "name"=>"单中心",
                "top"=> 17.17,
                "left"=> 39.7,
            ),
            5 =>array
            (
                "cid"=>"5",
                "kind"=>"pattern",
                "name"=>"多中心-多业态",
                "top"=> 10.10,
                "left"=> 39.7,
            ),
            6 =>array
            (
                "cid"=>"6",
                "kind"=>"pattern",
                "name"=>"多中心-多区域",
                "top"=> 10.10,
                "left"=> 43.44,
            ),
            7 =>array
            (
                "cid"=>"7",
                "kind"=>"pattern",
                "name"=>"专长中心",
                "top"=> 17.17,
                "left"=> 43.44,
            ),
            8 =>array
            (
                "cid"=>"8",
                "kind"=>"object",
                "name"=>"鸿途集团水泥有限公司",
                "top"=> 24.33,
                "left"=> 10.02,
            ),
            9 =>array
            (
                "cid"=>"9",
                "kind"=>"object",
                "name"=>"鸿途集团股份有限公司",
                "top"=> 24.33,
                "left"=> 10.32,
            ),
            10 =>array
            (
                "cid"=>"10",
                "kind"=>"object",
                "name"=>"金州鸿途煤焦化有限公司",
                "top"=> 24.33,
                "left"=> 10.62,
            ),
            11 =>array
            (
                "cid"=>"11",
                "kind"=>"object",
                "name"=>"鸿途集团万象商贸物流公司",
                "top"=> 24.33,
                "left"=> 10.92,
            ),
            12 =>array
            (
                "cid"=>"12",
                "kind"=>"object",
                "name"=>"鸿途集团水泥中部区公司(4家)",
                "top"=> 24.33,
                "left"=> 11.22,
            ),
            13 =>array
            (
                "cid"=>"13",
                "kind"=>"object",
                "name"=>"鸿途集团铸造板块公司(4家)",
                "top"=> 24.33,
                "left"=> 11.52,
            ),
            14 =>array
            (
                "cid"=>"14",
                "kind"=>"object",
                "name"=>"鸿途集团水泥北部区公司(12家)",
                 "top"=> 24.33,
                "left"=> 11.82,
            ),
            15 =>array
            (
                "cid"=>"15",
                "kind"=>"object",
                "name"=>"鸿途集团水泥南部区公司(2家)",
                 "top"=> 24.33,
                "left"=> 12.12,
            ),
            16 =>array
            (
                "cid"=>"16",
                "kind"=>"object",
                "name"=>"鸿途集团旅游板块公司(3家)",
                "top"=> 24.33,
                "left"=> 12.42,
            ),
            17 =>array
            (
                "cid"=>"17",
                "kind"=>"object",
                "name"=>"中国鸿途(香港)有限公司",
                "top"=> 24.33,
                "left"=> 12.72,
            ),
            18 =>array
            (
                "cid"=>"18",
                "kind"=>"object",
                "name"=>"金州市火电厂",
                "top"=> 24.33,
                "left"=> 13.02,
            ),
            19 =>array
            (
                "cid"=>"19",
                "kind"=>"object",
                "name"=>"金州鸿途实业有限公司",
               "top"=> 24.33,
                "left"=> 13.32,
            ),
            20 =>array
            (
                "cid"=>"20",
                "kind"=>"object",
                "name"=>"中原大福国际机场有限公司",
                "top"=> 24.33,
                "left"=> 13.62,
            ),
            21 =>array
            (
                "cid"=>"21",
                "kind"=>"content",
                "name"=>"费用共享",
                "top"=> 38.57,
                "left"=> 10.02,
            ),
            22 =>array
            (
                "cid"=>"22",
                "kind"=>"content",
                "name"=>"采购到应付共享",
                "top"=> 38.57,
                "left"=> 10.32,
            ),
            23 =>array
            (
                "cid"=>"23",
                "kind"=>"content",
                "name"=>"销售到应收共享",
                "top"=> 38.57,
                "left"=> 10.62,
            ),
            24 =>array
            (
                "cid"=>"24",
                "kind"=>"content",
                "name"=>"总账报表共享",
                "top"=> 38.57,
                "left"=> 10.92,
            ),
            25 =>array
            (
                "cid"=>"25",
                "kind"=>"content",
                "name"=>"固定资产共享",
                "top"=> 38.57,
                "left"=> 11.22,
            ),
            26 =>array
            (
                "cid"=>"26",
                "kind"=>"content",
                "name"=>"资金结算共享",
                "top"=> 38.57,
                "left"=> 11.52,
            ),
            27 =>array
            (
                "cid"=>"27",
                "kind"=>"location",
                "name"=>"大连",
                "top"=> 73.00,
                "left"=> 69.17,
            ),
            28 =>array
            (
                "cid"=>"28",
                "kind"=>"location",
                "name"=>"郑州",
                "top"=> 73.00,
                "left"=> 76.41,
            ),
            29 =>array
            (
                "cid"=>"29",
                "kind"=>"location",
                "name"=>"天津",
                "top"=> 73.00,
                "left"=> 82.86,
            ),
            30 =>array
            (
                "cid"=>"30",
                "kind"=>"function",
                "name"=>"职责中心",
                "top"=> 66.05,
                "left"=> 29,
            ),
            31 =>array
            (
                "cid"=>"31",
                "kind"=>"function",
                "name"=>"降低财务成本",
                "top"=> 72.05,
                "left"=> 29,
            ),
            32 =>array
            (
                "cid"=>"32",
                "kind"=>"function",
                "name"=>"加强集团管控",
                "top"=> 72.05,
                "left"=> 26,
            ),
            33 =>array
            (
                "cid"=>"33",
                "kind"=>"function",
                "name"=>"促进财务转型",
                "top"=> 72.05,
                "left"=> 23,
            ),
            34 =>array
            (
                "cid"=>"34",
                "kind"=>"function",
                "name"=>"支持企业发展",
                "top"=> 72.05,
                "left"=> 20,
            ),
            'content' =>array
            (
                "kind"=>"textarea",
                "name"=>"",
                "reason"=> "",
            ),
        );
          return $carddata;
    }

    public function planArray(){
        $carddata = array
        (
            1 =>array
            (
                "cid"=>"1",
                "kind"=>"position",
                "name"=>"财务总监",
                "top"=> 22.13,
                "left"=> 9.11,
            ),
            2 =>array
            (
                "cid"=>"2",
                "kind"=>"department",
                "name"=>"预算与考核管理处",
                "top"=> 9.00,
                "left"=> 9.11,
            ),
            3 =>array
            (
                "cid"=>"3",
                "kind"=>"department",
                "name"=>"税务与资金管理处",
                "top"=> 9.00,
                "left"=> 9.21,
            ),
            4 =>array
            (
                "cid"=>"4",
                "kind"=>"department",
                "name"=>"信息化与综合处",
                "top"=> 9.00,
                "left"=> 9.31,
            ),
            5 =>array
            (
                "cid"=>"5",
                "kind"=>"position",
                "name"=>"预算与考核管理岗",
                "top"=> 22.13,
                "left"=> 9.21,
            ),
            6 =>array
            (
                "cid"=>"6",
                "kind"=>"position",
                "name"=>"税务与资金管理岗",
                "top"=> 22.13,
                "left"=> 9.31,
            ),
            7 =>array
            (
                "cid"=>"7",
                "kind"=>"position",
                "name"=>"信息化与综合管理岗",
                "top"=> 22.13,
                "left"=> 9.41,
            ),
            8 =>array
            (
                "cid"=>"8",
                "kind"=>"position",
                "name"=>"结算审核处长",
                "top"=> 22.13,
                "left"=> 9.51,
            ),
            9 =>array
            (
                "cid"=>"9",
                "kind"=>"position",
                "name"=>"会计核算处长",
                "top"=> 22.13,
                "left"=> 9.61,
            ),
            10 =>array
            (
                "cid"=>"10",
                "kind"=>"position",
                "name"=>"资产管理处长",
                "top"=> 22.13,
                "left"=> 9.71,
            ),
            11 =>array
            (
                "cid"=>"11",
                "kind"=>"position",
                "name"=>"结算会计",
                "top"=> 22.13,
                "left"=> 9.81,
            ),
            12 =>array
            (
                "cid"=>"12",
                "kind"=>"position",
                "name"=>"核算会计",
                "top"=> 22.13,
                "left"=> 9.91,
            ),
            13 =>array
            (
                "cid"=>"13",
                "kind"=>"position",
                "name"=>"资产管理会计",
               "top"=> 22.13,
                "left"=> 10.01,
            ),
            14 =>array
            (
                "cid"=>"14",
                "kind"=>"position",
                "name"=>"出纳",
                "top"=> 22.13,
                "left"=> 10.11,
            ),
            15 =>array
            (
                "cid"=>"15",
                "kind"=>"department",
                "name"=>"结算审核处",
                "top"=> 9.00,
                "left"=> 9.41,
            ),
            16 =>array
            (
                "cid"=>"16",
                "kind"=>"department",
                "name"=>"会计核算处",
                 "top"=> 9.00,
                "left"=> 9.51,
            ),
            17 =>array
            (
                "cid"=>"17",
                "kind"=>"department",
                "name"=>"资产管理处",
                 "top"=> 9.00,
                "left"=> 9.61,
            ),
            18 =>array
            (
                "cid"=>"18",
                "kind"=>"duty",
                "name"=>"财务战略规划",
                "top"=> 35.90,
                "left"=> 9.11,
            ),
            19 =>array
            (
                "cid"=>"19",
                "kind"=>"duty",
                "name"=>"财务政策制定",
                "top"=> 35.90,
                "left"=> 9.21,
            ),
            20 =>array
            (
                "cid"=>"20",
                "kind"=>"duty",
                "name"=>"预算管理与业绩考核",
                "top"=> 35.90,
                "left"=> 9.31,
            ),
            21 =>array
            (
                "cid"=>"21",
                "kind"=>"duty",
                "name"=>"纳税筹划与资金运作",
                "top"=> 35.90,
                "left"=> 9.41,
            ),
            22 =>array
            (
                "cid"=>"22",
                "kind"=>"duty",
                "name"=>"信息化与财务监督",
                "top"=> 35.90,
                "left"=> 9.51,
            ),
            23 =>array
            (
                "cid"=>"23",
                "kind"=>"duty",
                "name"=>"付款复核",
                "top"=> 35.90,
                "left"=> 9.61,
            ),
            24 =>array
            (
                "cid"=>"24",
                "kind"=>"duty",
                "name"=>"付款审核",
                "top"=> 35.90,
                "left"=> 9.71,
            ),
            25 =>array
            (
                "cid"=>"25",
                "kind"=>"duty",
                "name"=>"资金支付",
                "top"=> 35.90,
                "left"=> 9.81,
            ),
            26 =>array
            (
                "cid"=>"26",
                "kind"=>"duty",
                "name"=>"费用复核",
                "top"=> 35.90,
                "left"=> 9.91,
            ),
            27 =>array
            (
                "cid"=>"27",
                "kind"=>"duty",
                "name"=>"费用核算",
                "top"=> 35.90,
                "left"=> 10.11,
            ),
            28 =>array
            (
                "cid"=>"28",
                "kind"=>"duty",
                "name"=>"资产管理政策制定",
               "top"=> 35.90,
                "left"=> 10.01,
            ),
            29 =>array
            (
                "cid"=>"29",
                "kind"=>"duty",
                "name"=>"资产核算",
                "top"=> 35.90,
                "left"=> 10.31,
            ),
            30 =>array
            (
                "cid"=>"30",
                "kind"=>"gsdepartment",
                "name"=>"鸿途集团水泥财务部",
                "top"=> 9.00,
                "left"=> 56.31,
            ),
            31 =>array
            (
                "cid"=>"31",
                "kind"=>"gsdepartment",
                "name"=>"鸿途集团旅游财务部",
                "top"=> 9.00,
                "left"=> 56.41,
            ),
            32 =>array
            (
                "cid"=>"32",
                "kind"=>"gsdepartment",
                "name"=>"鸿途集团铸造财务部",
               "top"=> 9.00,
                "left"=> 56.51,
            ),
            33 =>array
            (
                "cid"=>"33",
                "kind"=>"gsdepartment",
                "name"=>"鸿途集团煤焦化财务部",
                "top"=> 9.00,
                "left"=> 56.61,
            ),
            34 =>array
            (
                "cid"=>"34",
                "kind"=>"gsposition",
                "name"=>"财务经理",
                "top"=> 22.13,
                "left"=> 56.31,
            ),
            35 =>array
            (
                "cid"=>"35",
                "kind"=>"gsposition",
                "name"=>"总账会计",
                "top"=> 22.13,
                "left"=> 56.41,
            ),
            36 =>array
            (
                "cid"=>"36",
                "kind"=>"gsposition",
                "name"=>"采购会计",
               "top"=> 22.13,
                "left"=> 56.51,
            ),
            36 =>array
            (
                "cid"=>"36",
                "kind"=>"gsposition",
                "name"=>"结算会计",
                "top"=> 22.13,
                "left"=> 56.61,
            ),
            37 =>array
            (
                "cid"=>"37",
                "kind"=>"gsposition",
                "name"=>"销售会计",
                "top"=> 22.13,
                "left"=> 56.71,
            ),
            38 =>array
            (
                "cid"=>"38",
                "kind"=>"gsposition",
                "name"=>"资产会计",
                "top"=> 22.13,
                "left"=> 56.81,
            ),
            39 =>array
            (
                "cid"=>"39",
                "kind"=>"gsposition",
                "name"=>"成本会计",
                "top"=> 22.13,
                "left"=> 56.91,
            ),
            40 =>array
            (
                "cid"=>"40",
                "kind"=>"gsposition",
                "name"=>"税务会计",
                "top"=> 22.13,
                "left"=> 57.01,
            ),
            41 =>array
            (
                "cid"=>"41",
                "kind"=>"gsposition",
                "name"=>"出纳",
                 "top"=> 22.13,
                "left"=> 57.11,
            ),
            42 =>array
            (
                "cid"=>"42",
                "kind"=>"gsposition",
                "name"=>"预算会计",
                 "top"=> 22.13,
                "left"=> 57.11,
            ),
            43 =>array
            (
                "cid"=>"43",
                "kind"=>"gsduty",
                "name"=>"财务分析",
                "top"=> 35.90,
                "left"=> 56.31,
            ),
            44 =>array
            (
                "cid"=>"44",
                "kind"=>"gsduty",
                "name"=>"总账核算",
                "top"=> 35.90,
                "left"=> 56.41,
            ),
            45 =>array
            (
                "cid"=>"45",
                "kind"=>"gsduty",
                "name"=>"应付审核/应付对账",
                "top"=> 35.90,
                "left"=> 56.51,
            ),
            46 =>array
            (
                "cid"=>"46",
                "kind"=>"gsduty",
                "name"=>"费用核算",
                "top"=> 35.90,
                "left"=> 56.61,
            ),
            47 =>array
            (
                "cid"=>"47",
                "kind"=>"gsduty",
                "name"=>"应收审核/应收对账",
               "top"=> 35.90,
                "left"=> 56.71,
            ),
            48 =>array
            (
                "cid"=>"48",
                "kind"=>"gsduty",
                "name"=>"资产核算",
                "top"=> 35.90,
                "left"=> 56.81,
            ),
            49 =>array
            (
                "cid"=>"49",
                "kind"=>"gsduty",
                "name"=>"成本分析/成本核算",
                "top"=> 35.90,
                "left"=> 56.91,
            ),
            50 =>array
            (
                "cid"=>"50",
                "kind"=>"gsduty",
                "name"=>"税务筹划",
                "top"=> 35.90,
                "left"=> 57.01,
            ),
            51 =>array
            (
                "cid"=>"51",
                "kind"=>"gsduty",
                "name"=>"收款付款",
                "top"=> 35.90,
                "left"=> 57.11,
            ),
            52 =>array
            (
                "cid"=>"52",
                "kind"=>"gsduty",
                "name"=>"预算编制",
                "top"=> 35.90,
                "left"=> 57.21,
            ),
             53 =>array
            (
                "cid"=>"53",
                "kind"=>"gsposition",
                "name"=>"采购会计",
                "top"=> 22.13,
                "left"=> 57.21,
            )
        );
        return $carddata;
    }
}
