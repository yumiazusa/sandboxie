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

    //??????????????????
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
                'msg' => '????????????',
                'reload' => true
            ];
         }
         return [
                'code' => 1,
                'msg' => '????????????',
                'reload' => true
            ];

    }

    //??????????????????
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
                'msg' => '????????????',
                'reload' => true
            ];
         }
         return [
                'code' => 1,
                'msg' => '????????????',
                'reload' => true
            ];

    }

     //??????????????????
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
                'msg' => '????????????',
                'reload' => true
            ];
         }
         return [
                'code' => 1,
                'msg' => '????????????',
                'reload' => true
            ];

    }


    //??????????????????AJAX???????????????
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

     //??????????????????
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
                'msg' => '????????????',
                'reload' => true
            ];
         }
         return [
                'code' => 1,
                'msg' => '????????????',
                'reload' => true
            ];

    }

      //??????????????????AJAX???????????????
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

    //??????????????????
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
                'msg' => '????????????',
                'reload' => true
            ];
         }
         return [
                'code' => 1,
                'msg' => '????????????',
                'reload' => true
            ];
    }

    //??????????????????
    public function reFee(Request $request){
        if(!$request['kind']){
             return [
                'code' => 2,
                'msg' => '????????????',
                'reload' => true
            ];
        }
        if($request['kind'] === 'strategy'){
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
        }elseif($request['kind'] === 'plan'){
            $data=$this->planArray();
            $data=serialize($data);
            $user=DB::table('card5')->pluck('id');
            foreach ($user as $k => $v) {
            $card=[
            'carddata' => $data,
            // 'cardposition' => serialize($cardposition),
            // 'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
             ];
            $res = DB::table('card5')->where('id',$v)->update($card);
             }
        }elseif($request['kind'] === 'fee'){
            $data=$this->feeArray();
            $data=serialize($data);
            $user=DB::table('card1')->pluck('id');
            foreach ($user as $k => $v) {
            $card=[
            'carddata' => $data,
            // 'cardposition' => serialize($cardposition),
            // 'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
             ];
            $res = DB::table('card1')->where('id',$v)->update($card);
             }
        }elseif($request['kind'] === 'sale'){
            $data=$this->saleArray();
            $data=serialize($data);
            $user=DB::table('card2')->pluck('id');
            foreach ($user as $k => $v) {
            $card=[
            'carddata' => $data,
            // 'cardposition' => serialize($cardposition),
            // 'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
             ];
            $res = DB::table('card2')->where('id',$v)->update($card);
             }
        }elseif($request['kind'] === 'purchase'){
            $data=$this->purchaseArray();
            $data=serialize($data);
            $user=DB::table('card3')->pluck('id');
            foreach ($user as $k => $v) {
            $card=[
            'carddata' => $data,
            // 'cardposition' => serialize($cardposition),
            // 'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
             ];
            $res = DB::table('card3')->where('id',$v)->update($card);
             }
        }
        // $data=$this->strategyArray();
        // $data=serialize($data);
        // $user=DB::table('card4')->pluck('id');
        // foreach ($user as $k => $v) {
        //     $card=[
        //     'carddata' => $data,
        //     // 'cardposition' => serialize($cardposition),
        //     // 'created_at' => date("Y-m-d H:i:s"),
        //     'updated_at' => date("Y-m-d H:i:s"),
        //  ];
        //  $res = DB::table('card4')->where('id',$v)->update($card);
        // }
        if(!$res){
            return [
                'code' => 2,
                'msg' => '????????????',
                'reload' => true
            ];
         }
         return [
                'code' => 1,
                'msg' => '????????????',
                'reload' => true
            ];
    }

    //??????????????????
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
                'msg' => '????????????',
                'reload' => true
            ];
         }
         return [
                'code' => 1,
                'msg' => '????????????',
                'reload' => true
            ];
    }

    //????????????????????????????????????
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
                "name"=>"????????????",
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
                "name"=>"?????????",
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
                "name"=>"????????????",
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
                "name"=>"?????????",
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
                "name"=>"??????????????????",
                "isshare"=> 1,
                "sharename"=>"??????????????????",
                 "top"=> 8.80,
                "left"=> 14.13,
                "shared" => 1
            ),
            6 =>array
            (
                "cid"=>"6",
                "kind"=>"people",
                "name"=>"??????????????????",
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
                "sharename"=>"FSSC???????????????",
                "top"=> 8.80,
                "left"=> 14.53,
                "shared" => 3
            ),
            8 =>array
            (
                "cid"=>"8",
                "kind"=>"people",
                "name"=>"????????????",
                "isshare"=> 1,
                "sharename"=>"FSSC?????????",
                "top"=> 8.80,
                "left"=> 14.73,
                "shared" => 1
            ),
            9 =>array
            (
                "cid"=>"9",
                "kind"=>"people",
                "name"=>"??????????????????",
                "isshare"=> 1,
                "sharename"=>"FSSC???????????????",
                "top"=> 8.80,
                "left"=> 14.93,
                "shared" => 1
            ),
            10 =>array
            (
                "cid"=>"10",
                "kind"=>"people",
                "name"=>"?????????????????????",
                "isshare"=> 1,
                "sharename"=>"FSSC???????????????",
                "top"=> 8.80,
                "left"=> 15.13,
                "shared" => 1
            ),
            11 =>array
            (
                "cid"=>"11",
                "kind"=>"action",
                "name"=>"????????????",
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
                "name"=>"????????????",
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
                "name"=>"????????????",
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
                "name"=>"????????????",
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
                "name"=>"????????????",
                "isshare"=> 1,
                "sharename"=>"????????????",
                 "top"=> 32.20,
                "left"=> 14.13,
                "shared" => 1
            ),
             16 =>array
            (
                "cid"=>"16",
                "kind"=>"action",
                "name"=>"????????????",
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
                "name"=>"??????????????????",
                "isshare"=> 1,
                "sharename"=>"??????????????????",
                "top"=> 32.20,
                "left"=> 14.53,
                "shared" => 1
            ),
            18 =>array
            (
                "cid"=>"18",
                "kind"=>"list",
                "name"=>"????????????",
                "isshare"=> 1,
                "sharename"=>"??????????????????",
                "top"=> 53.50,
                "left"=> 13.33,
                "shared" => 1
            ),
            19 =>array
            (
                "cid"=>"19",
                "kind"=>"list",
                "name"=>"????????????",
                "isshare"=> 1,
                "sharename"=>"??????????????????",
                "top"=> 53.50,
                "left"=> 13.53,
                "shared" => 1
            ),
            20 =>array
            (
                "cid"=>"20",
                "kind"=>"list",
                "name"=>"????????????",
                "isshare"=> 1,
                "sharename"=>"????????????",
                "top"=> 53.50,
                "left"=> 13.73,
                "shared" => 1
            ),
            21 =>array
            (
                "cid"=>"21",
                "kind"=>"list",
                "name"=>"????????????",
                "isshare"=> 1,
                "sharename"=>"????????????",
                "top"=> 53.50,
                "left"=> 13.93,
                "shared" => 1
            ),
            22 =>array
            (
                "cid"=>"22",
                "kind"=>"tech",
                "name"=>"??????????????????",
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
                "name"=>"????????????",
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
                "name"=>"??????????????????",
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
                "name"=>"??????????????????",
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
                "name"=>"????????????????????????",
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
                "name"=>"????????????",
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
                "name"=>"????????????",
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
                "name"=>"????????????",
                "isshare"=> 1,
                "sharename"=>"????????????",
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
                "name"=>"????????????",
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
                "name"=>"?????????",
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
                "name"=>"????????????",
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
                "name"=>"??????????????????",
                "isshare"=> 1,
                "sharename"=>"??????????????????",
                "top"=> 8.80,
                "left"=> 13.93,
                "shared" => 1
            ),
            5 =>array
            (
                "cid"=>"5",
                "kind"=>"people",
                "name"=>"????????????",
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
                "name"=>"????????????",
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
                "sharename"=>"FSSC???????????????",
                "top"=> 8.80,
                "left"=> 14.53,
                "shared" => 3
            ),
            8 =>array
            (
                "cid"=>"8",
                "kind"=>"people",
                "name"=>"???????????????",
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
                "name"=>"??????????????????",
                "isshare"=> 1,
                "sharename"=>"??????????????????",
                 "top"=> 8.80,
                "left"=> 14.93,
                "shared" => 1
            ),
            10 =>array
            (
                "cid"=>"10",
                "kind"=>"people",
                "name"=>"?????????????????????",
                "isshare"=> 1,
                "sharename"=>"FSSC???????????????",
                 "top"=> 8.80,
                "left"=> 15.13,
                "shared" => 1
            ),
            11 =>array
            (
                "cid"=>"11",
                "kind"=>"people",
                "name"=>"??????????????????",
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
                "name"=>"??????????????????",
                "isshare"=> 1,
                "sharename"=>"FSSC???????????????",
                 "top"=> 8.80,
                "left"=> 15.53,
                "shared" => 1
            ),
             13 =>array
            (
                "cid"=>"13",
                "kind"=>"people",
                "name"=>"??????????????????",
                "isshare"=> 1,
                "sharename"=>"??????????????????",
                 "top"=> 8.80,
                "left"=> 15.73,
                "shared" => 1
            ),
             14 =>array
            (
                "cid"=>"14",
                "kind"=>"people",
                "name"=>"??????????????????",
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
                "sharename"=>"FSSC???????????????",
                "top"=> 8.80,
                "left"=> 16.13,
                "shared" => 3
            ),
             16 =>array
            (
                "cid"=>"16",
                "kind"=>"people",
                "name"=>"????????????",
                "isshare"=> 1,
                "sharename"=>"FSSC?????????",
                 "top"=> 8.80,
                "left"=> 16.33,
                "shared" => 1
            ),
             17 =>array
            (
                "cid"=>"17",
                "kind"=>"people",
                "name"=>"??????????????????",
                "isshare"=> 1,
                "sharename"=>"FSSC???????????????",
                 "top"=> 8.80,
                "left"=> 16.53,
                "shared" => 1
            ),
            18 =>array
            (
                "cid"=>"18",
                "kind"=>"action",
                "name"=>"??????????????????",
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
                "name"=>"??????????????????",
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
                "name"=>"??????????????????",
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
                "name"=>"??????????????????",
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
                "name"=>"??????????????????",
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
                "name"=>"??????????????????",
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
                "name"=>"??????????????????",
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
                "name"=>"???????????????",
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
                "name"=>"???????????????",
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
                "name"=>"??????????????????",
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
                "name"=>"??????????????????",
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
                "name"=>"???????????????",
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
                "name"=>"???????????????",
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
                "name"=>"??????????????????",
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
                "name"=>"??????????????????",
                "isshare"=> 1,
                "sharename"=>"??????????????????",
               "top"=> 32.20,
                "left"=> 16.13,
                "shared" => 1
            ),
             33 =>array
            (
                "cid"=>"33",
                "kind"=>"action",
                "name"=>"????????????",
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
                "name"=>"??????????????????",
                "isshare"=> 1,
                "sharename"=>"??????????????????",
                "top"=> 32.20,
                "left"=> 16.53,
                "shared" => 1
            ),
             35 =>array
            (
                "cid"=>"35",
                "kind"=>"list",
                "name"=>"????????????",
                "isshare"=> 1,
                "sharename"=>"??????????????????",
                "top"=> 53.50,
                "left"=> 13.33,
                "shared" => 1
            ),
             36 =>array
            (
                "cid"=>"36",
                "kind"=>"list",
                "name"=>"??????????????????",
                "isshare"=> 1,
                "sharename"=>"??????????????????",
               "top"=> 53.50,
                "left"=> 13.53,
                "shared" => 1
            ),
             37 =>array
            (
                "cid"=>"37",
                "kind"=>"list",
                "name"=>"???????????????",
                "isshare"=> 1,
                "sharename"=>"???????????????",
                "top"=> 53.50,
                "left"=> 13.93,
                "shared" => 1
            ),
             38 =>array
            (
                "cid"=>"38",
                "kind"=>"list",
                "name"=>"???????????????",
                "isshare"=> 1,
                "sharename"=>"???????????????",
                "top"=> 53.50,
                "left"=> 14.13,
                "shared" => 1
            ),
             39 =>array
            (
                "cid"=>"39",
                "kind"=>"list",
                "name"=>"????????????",
                "isshare"=> 1,
                "sharename"=>"????????????",
                "top"=> 53.50,
                "left"=> 14.33,
                "shared" => 1
            ),
             40 =>array
            (
                "cid"=>"40",
                "kind"=>"list",
                "name"=>"???????????????",
                "isshare"=> 1,
                "sharename"=>"???????????????",
                "top"=> 53.50,
                "left"=> 14.53,
                "shared" => 1
            ),
             41 =>array
            (
                "cid"=>"41",
                "kind"=>"list",
                "name"=>"????????????",
                "isshare"=> 1,
                "sharename"=>"????????????",
               "top"=> 53.50,
                "left"=> 14.73,
                "shared" => 1
            ),
             42 =>array
            (
                "cid"=>"42",
                "kind"=>"list",
                "name"=>"???????????????",
                "isshare"=> 1,
                "sharename"=>"???????????????",
                "top"=> 53.50,
                "left"=> 14.93,
                "shared" => 1
            ),
             43 =>array
            (
                "cid"=>"43",
                "kind"=>"list",
                "name"=>"????????????",
                "isshare"=> 1,
                "sharename"=>"????????????",
                "top"=> 53.50,
                "left"=> 15.13,
                "shared" => 1
            ),
             44 =>array
            (
                "cid"=>"44",
                "kind"=>"list",
                "name"=>"????????????",
                "isshare"=> 1,
                "sharename"=>"????????????",
                "top"=> 53.50,
                "left"=> 15.33,
                "shared" => 1
            ),
             45 =>array
            (
                "cid"=>"45",
                "kind"=>"tech",
                "name"=>"??????????????????",
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
                "name"=>"??????????????????",
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
                "name"=>"??????ERP????????????",
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
                "name"=>"????????????????????????",
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
                "name"=>"????????????????????????",
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
                "name"=>"????????????",
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
                "name"=>"????????????",
                "isshare"=> 0,
                "sharename"=>0,
                "top"=> 76.35,
                "left"=> 14.53,
                "shared" => 0
            ),
            52 =>array
            (
                "cid"=>"52",
                "kind"=>"list",
                "name"=>"????????????",
                "isshare"=> 1,
                "sharename"=>"??????????????????",
                "top"=> 53.50,
                "left"=> 15.53,
                "shared" => 1
            ),
             53 =>array
            (
                "cid"=>"53",
                "kind"=>"action",
                "name"=>"??????????????????",
                "isshare"=> 0,
                "sharename"=>0,
                 "top"=> 32.20,
                "left"=> 13.53,
                "shared" => 0
            ),
             54 =>array
            (
                "cid"=>"54",
                "kind"=>"action",
                "name"=>"??????????????????/??????",
                "isshare"=> 0,
                "sharename"=>0,
                 "top"=> 32.20,
                "left"=> 13.93,
                "shared" => 0
            ),
             55 =>array
            (
                "cid"=>"55",
                "kind"=>"action",
                "name"=>"???????????????",
                "isshare"=> 0,
                "sharename"=>0,
                 "top"=> 32.20,
                "left"=> 13.43,
                "shared" => 0
            ),
             56 =>array
            (
                "cid"=>"56",
                "kind"=>"list",
                "name"=>"???????????????",
                "isshare"=> 1,
                "sharename"=>"???????????????",
                "top"=> 53.50,
                "left"=> 14.63,
                "shared" => 1
            ),
            57 =>array
            (
                "cid"=>"57",
                "kind"=>"action",
                "name"=>"???????????????",
                "isshare"=> 0,
                "sharename"=>0,
                 "top"=> 32.20,
                "left"=> 13.23,
                "shared" => 0
            ),
             58 =>array("sharetb"=> 1)
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
                "name"=>"?????????",
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
                "name"=>"????????????",
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
                "name"=>"?????????????????????",
                "isshare"=> 1,
                "sharename"=>"FSSC???????????????",
                "top"=> 8.80,
                "left"=> 13.73,
                "shared" => 1
            ),
            4 =>array
            (
                "cid"=>"4",
                "kind"=>"people",
                "name"=>"?????????",
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
                "name"=>"????????????",
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
                "name"=>"?????????",
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
                "name"=>"????????????",
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
                "name"=>"????????????",
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
                "name"=>"??????????????????",
                "isshare"=> 1,
                "sharename"=>"??????????????????",
                "top"=> 8.80,
                "left"=> 14.93,
                "shared" => 1
            ),
            10 =>array
            (
                "cid"=>"10",
                "kind"=>"people",
                "name"=>"?????????????????????",
                "isshare"=> 1,
                "sharename"=>"FSSC???????????????",
                "top"=> 8.80,
                "left"=> 15.13,
                "shared" => 1
            ),
            11 =>array
            (
                "cid"=>"11",
                "kind"=>"people",
                "name"=>"?????????",
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
                "name"=>"????????????",
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
                "name"=>"?????????",
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
                "name"=>"?????????",
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
                "name"=>"?????????",
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
                "name"=>"??????????????????",
                "isshare"=> 1,
                "sharename"=>"??????????????????",
                 "top"=> 8.80,
                "left"=> 16.33,
                "shared" => 1
            ),
             17 =>array
            (
                "cid"=>"17",
                "kind"=>"people",
                "name"=>"????????????",
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
                "sharename"=>"FSSC???????????????",
                 "top"=> 8.80,
                "left"=> 16.73,
                "shared" => 3
            ),
             19 =>array
            (
                "cid"=>"19",
                "kind"=>"people",
                "name"=>"??????????????????",
                "isshare"=> 1,
                "sharename"=>"FSSC???????????????",
                "top"=> 8.80,
                "left"=> 16.93,
                "shared" => 1
            ),
             20 =>array
            (
                "cid"=>"20",
                "kind"=>"people",
                "name"=>"??????????????????",
                "isshare"=> 1,
                "sharename"=>"??????????????????",
                 "top"=> 8.80,
                "left"=> 17.13,
                "shared" => 1
            ),
             21 =>array
            (
                "cid"=>"21",
                "kind"=>"people",
                "name"=>"????????????",
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
                "name"=>"??????????????????",
                "isshare"=> 1,
                "sharename"=>"??????????????????",
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
                "sharename"=>"FSSC???????????????",
                 "top"=> 8.80,
                "left"=> 17.73,
                "shared" => 3
            ),
             24 =>array
            (
                "cid"=>"24",
                "kind"=>"people",
                "name"=>"????????????",
                "isshare"=> 1,
                "sharename"=>"FSSC?????????",
               "top"=> 8.80,
                "left"=> 17.93,
                "shared" => 1
            ),
             25 =>array
            (
                "cid"=>"25",
                "kind"=>"people",
                "name"=>"??????????????????",
                "isshare"=> 1,
                "sharename"=>"FSSC???????????????",
                "top"=> 8.80,
                "left"=> 18.13,
                "shared" => 1
            ),
             26 =>array
            (
                "cid"=>"26",
                "kind"=>"action",
                "name"=>"??????/???????????????",
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
                "name"=>"????????????????????????",
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
                "name"=>"?????????????????????",
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
                "name"=>"????????????",
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
                "name"=>"???????????????/??????????????????",
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
                "name"=>"???????????????/??????????????????",
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
                "name"=>"??????????????????",
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
                "name"=>"??????????????????",
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
                "name"=>"????????????????????????",
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
                "name"=>"??????????????????",
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
                "name"=>"??????????????????",
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
                "name"=>"??????????????????",
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
                "name"=>"????????????/??????",
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
                "name"=>"????????????",
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
                "name"=>"??????",
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
                "name"=>"??????????????????/???????????????",
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
                "name"=>"???????????????",
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
                "name"=>"??????????????????",
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
                "name"=>"??????????????????",
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
                "name"=>"???????????????",
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
                "name"=>"???????????????",
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
                "name"=>"??????????????????",
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
                "name"=>"???????????????",
                "isshare"=> 1,
                "sharename"=>"????????????",
                "top"=> 32.20,
                "left"=> 17.73,
                "shared" => 1
            ),
             49 =>array
            (
                "cid"=>"49",
                "kind"=>"action",
                "name"=>"??????????????????",
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
                "name"=>"????????????????????????",
                "isshare"=> 1,
                "sharename"=>"????????????????????????",
                "top"=> 53.50,
                "left"=> 13.33,
                "shared" => 1
            ),
             51 =>array
            (
                "cid"=>"51",
                "kind"=>"list",
                "name"=>"???????????????????????????",
                "isshare"=> 1,
                "sharename"=>"???????????????????????????",
                "top"=> 53.50,
                "left"=> 13.53,
                "shared" => 1
            ),
             52 =>array
            (
                "cid"=>"52",
                "kind"=>"list",
                "name"=>"????????????",
                "isshare"=> 1,
                "sharename"=>"????????????",
               "top"=> 53.50,
                "left"=> 13.73,
                "shared" => 1
            ),
             53 =>array
            (
                "cid"=>"53",
                "kind"=>"list",
                "name"=>"??????????????????",
                "isshare"=> 1,
                "sharename"=>"??????????????????",
                "top"=> 53.50,
                "left"=> 13.93,
                "shared" => 1
            ),
             54 =>array
            (
                "cid"=>"54",
                "kind"=>"list",
                "name"=>"?????????????????????",
                "isshare"=> 1,
                "sharename"=>"?????????????????????",
                "top"=> 53.50,
                "left"=> 14.13,
                "shared" => 1
            ),
             55 =>array
            (
                "cid"=>"55",
                "kind"=>"list",
                "name"=>"??????????????????",
                "isshare"=> 1,
                "sharename"=>"??????????????????",
                "top"=> 53.50,
                "left"=> 14.33,
                "shared" => 1
            ),
             56 =>array
            (
                "cid"=>"56",
                "kind"=>"list",
                "name"=>"??????????????????",
                "isshare"=> 1,
                "sharename"=>"??????????????????",
                "top"=> 53.50,
                "left"=> 14.53,
                "shared" => 1
            ),
             57 =>array
            (
                "cid"=>"57",
                "kind"=>"list",
                "name"=>"?????????????????????",
                "isshare"=> 1,
                "sharename"=>"?????????????????????",
                "top"=> 53.50,
                "left"=> 14.73,
                "shared" => 1
            ),
             58 =>array
            (
                "cid"=>"58",
                "kind"=>"list",
                "name"=>"????????????",
                "isshare"=> 1,
                "sharename"=>"????????????",
                "top"=> 53.50,
                "left"=> 14.93,
                "shared" => 1
            ),
             59 =>array
            (
                "cid"=>"59",
                "kind"=>"list",
                "name"=>"????????????",
                "isshare"=> 1,
                "sharename"=>"????????????",
                "top"=> 53.50,
                "left"=> 15.13,
                "shared" => 1
            ),
             60 =>array
            (
                "cid"=>"60",
                "kind"=>"list",
                "name"=>"???????????????",
                "isshare"=> 1,
                "sharename"=>"???????????????",
                 "top"=> 53.50,
                "left"=> 15.33,
                "shared" => 1
            ),
             61 =>array
            (
                "cid"=>"61",
                "kind"=>"list",
                "name"=>"???????????????",
                "isshare"=> 1,
                "sharename"=>"???????????????",
                 "top"=> 53.50,
                "left"=> 15.53,
                "shared" => 1
            ),
             62 =>array
            (
                "cid"=>"62",
                "kind"=>"list",
                "name"=>"????????????",
                "isshare"=> 1,
                "sharename"=>"????????????",
                 "top"=> 53.50,
                "left"=> 15.73,
                "shared" => 1
            ),
             63 =>array
            (
                "cid"=>"63",
                "kind"=>"list",
                "name"=>"????????????",
                "isshare"=> 1,
                "sharename"=>"????????????",
                 "top"=> 53.50,
                "left"=> 15.93,
                "shared" => 1
            ),
             64 =>array
            (
                "cid"=>"64",
                "kind"=>"tech",
                "name"=>"??????????????????",
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
                "name"=>"??????????????????",
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
                "name"=>"??????ERP????????????",
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
                "name"=>"????????????????????????",
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
                "name"=>"????????????????????????",
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
                "name"=>"????????????",
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
                "name"=>"????????????",
                "isshare"=> 0,
                "sharename"=>0,
                "top"=> 76.35,
                "left"=> 14.53,
                "shared" => 0
            ),
             71 =>array
            (
                "cid"=>"71",
                "kind"=>"action",
                "name"=>"???????????????",
                "isshare"=> 0,
                "sharename"=>0,
                "top"=> 32.20,
                "left"=> 16.23,
                "shared" => 0
            ),
             72 =>array("sharetb"=> 1)
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
                "name"=>"????????????",
                "top"=> 66.05,
                "left"=> 20.1,
            ),
            2 =>array
            (
                "cid"=>"2",
                "kind"=>"function",
                "name"=>"????????????",
                "top"=> 66.05,
                "left"=> 23,
            ),
            3 =>array
            (
                "cid"=>"3",
                "kind"=>"function",
                "name"=>"??????????????????",
                "top"=> 66.05,
                "left"=> 26,
            ),
            4 =>array
            (
                "cid"=>"4",
                "kind"=>"pattern",
                "name"=>"?????????",
                "top"=> 17.17,
                "left"=> 39.7,
            ),
            5 =>array
            (
                "cid"=>"5",
                "kind"=>"pattern",
                "name"=>"?????????-?????????",
                "top"=> 10.10,
                "left"=> 39.7,
            ),
            6 =>array
            (
                "cid"=>"6",
                "kind"=>"pattern",
                "name"=>"?????????-?????????",
                "top"=> 10.10,
                "left"=> 43.44,
            ),
            7 =>array
            (
                "cid"=>"7",
                "kind"=>"pattern",
                "name"=>"????????????",
                "top"=> 17.17,
                "left"=> 43.44,
            ),
            8 =>array
            (
                "cid"=>"8",
                "kind"=>"object",
                "name"=>"??????????????????????????????",
                "top"=> 24.33,
                "left"=> 10.02,
            ),
            9 =>array
            (
                "cid"=>"9",
                "kind"=>"object",
                "name"=>"??????????????????????????????",
                "top"=> 24.33,
                "left"=> 10.32,
            ),
            10 =>array
            (
                "cid"=>"10",
                "kind"=>"object",
                "name"=>"?????????????????????????????????",
                "top"=> 24.33,
                "left"=> 10.62,
            ),
            11 =>array
            (
                "cid"=>"11",
                "kind"=>"object",
                "name"=>"????????????????????????????????????",
                "top"=> 24.33,
                "left"=> 10.92,
            ),
            12 =>array
            (
                "cid"=>"12",
                "kind"=>"object",
                "name"=>"?????????????????????????????????(4???)",
                "top"=> 24.33,
                "left"=> 11.22,
            ),
            13 =>array
            (
                "cid"=>"13",
                "kind"=>"object",
                "name"=>"??????????????????????????????(4???)",
                "top"=> 24.33,
                "left"=> 11.52,
            ),
            14 =>array
            (
                "cid"=>"14",
                "kind"=>"object",
                "name"=>"?????????????????????????????????(12???)",
                 "top"=> 24.33,
                "left"=> 11.82,
            ),
            15 =>array
            (
                "cid"=>"15",
                "kind"=>"object",
                "name"=>"?????????????????????????????????(2???)",
                 "top"=> 24.33,
                "left"=> 12.12,
            ),
            16 =>array
            (
                "cid"=>"16",
                "kind"=>"object",
                "name"=>"??????????????????????????????(3???)",
                "top"=> 24.33,
                "left"=> 12.42,
            ),
            17 =>array
            (
                "cid"=>"17",
                "kind"=>"object",
                "name"=>"????????????(??????)????????????",
                "top"=> 24.33,
                "left"=> 12.72,
            ),
            18 =>array
            (
                "cid"=>"18",
                "kind"=>"object",
                "name"=>"??????????????????",
                "top"=> 24.33,
                "left"=> 13.02,
            ),
            19 =>array
            (
                "cid"=>"19",
                "kind"=>"object",
                "name"=>"??????????????????????????????",
               "top"=> 24.33,
                "left"=> 13.32,
            ),
            20 =>array
            (
                "cid"=>"20",
                "kind"=>"object",
                "name"=>"????????????????????????????????????",
                "top"=> 24.33,
                "left"=> 13.62,
            ),
            21 =>array
            (
                "cid"=>"21",
                "kind"=>"content",
                "name"=>"????????????",
                "top"=> 38.57,
                "left"=> 10.02,
            ),
            22 =>array
            (
                "cid"=>"22",
                "kind"=>"content",
                "name"=>"?????????????????????",
                "top"=> 38.57,
                "left"=> 10.32,
            ),
            23 =>array
            (
                "cid"=>"23",
                "kind"=>"content",
                "name"=>"?????????????????????",
                "top"=> 38.57,
                "left"=> 10.62,
            ),
            24 =>array
            (
                "cid"=>"24",
                "kind"=>"content",
                "name"=>"??????????????????",
                "top"=> 38.57,
                "left"=> 10.92,
            ),
            25 =>array
            (
                "cid"=>"25",
                "kind"=>"content",
                "name"=>"??????????????????",
                "top"=> 38.57,
                "left"=> 11.22,
            ),
            26 =>array
            (
                "cid"=>"26",
                "kind"=>"content",
                "name"=>"??????????????????",
                "top"=> 38.57,
                "left"=> 11.52,
            ),
            27 =>array
            (
                "cid"=>"27",
                "kind"=>"location",
                "name"=>"??????",
                "top"=> 73.00,
                "left"=> 69.17,
            ),
            28 =>array
            (
                "cid"=>"28",
                "kind"=>"location",
                "name"=>"??????",
                "top"=> 73.00,
                "left"=> 76.41,
            ),
            29 =>array
            (
                "cid"=>"29",
                "kind"=>"location",
                "name"=>"??????",
                "top"=> 73.00,
                "left"=> 82.86,
            ),
            30 =>array
            (
                "cid"=>"30",
                "kind"=>"function",
                "name"=>"????????????",
                "top"=> 66.05,
                "left"=> 29,
            ),
            31 =>array
            (
                "cid"=>"31",
                "kind"=>"function",
                "name"=>"??????????????????",
                "top"=> 72.05,
                "left"=> 29,
            ),
            32 =>array
            (
                "cid"=>"32",
                "kind"=>"function",
                "name"=>"??????????????????",
                "top"=> 72.05,
                "left"=> 26,
            ),
            33 =>array
            (
                "cid"=>"33",
                "kind"=>"function",
                "name"=>"??????????????????",
                "top"=> 72.05,
                "left"=> 23,
            ),
            34 =>array
            (
                "cid"=>"34",
                "kind"=>"function",
                "name"=>"??????????????????",
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
                "name"=>"????????????",
                "top"=> 22.13,
                "left"=> 9.11,
            ),
            2 =>array
            (
                "cid"=>"2",
                "kind"=>"department",
                "name"=>"????????????????????????",
                "top"=> 9.00,
                "left"=> 9.11,
            ),
            3 =>array
            (
                "cid"=>"3",
                "kind"=>"department",
                "name"=>"????????????????????????",
                "top"=> 9.00,
                "left"=> 9.21,
            ),
            4 =>array
            (
                "cid"=>"4",
                "kind"=>"department",
                "name"=>"?????????????????????",
                "top"=> 9.00,
                "left"=> 9.31,
            ),
            5 =>array
            (
                "cid"=>"5",
                "kind"=>"position",
                "name"=>"????????????????????????",
                "top"=> 22.13,
                "left"=> 9.21,
            ),
            6 =>array
            (
                "cid"=>"6",
                "kind"=>"position",
                "name"=>"????????????????????????",
                "top"=> 22.13,
                "left"=> 9.31,
            ),
            7 =>array
            (
                "cid"=>"7",
                "kind"=>"position",
                "name"=>"???????????????????????????",
                "top"=> 22.13,
                "left"=> 9.41,
            ),
            8 =>array
            (
                "cid"=>"8",
                "kind"=>"position",
                "name"=>"??????????????????",
                "top"=> 22.13,
                "left"=> 9.51,
            ),
            9 =>array
            (
                "cid"=>"9",
                "kind"=>"position",
                "name"=>"??????????????????",
                "top"=> 22.13,
                "left"=> 9.61,
            ),
            10 =>array
            (
                "cid"=>"10",
                "kind"=>"position",
                "name"=>"??????????????????",
                "top"=> 22.13,
                "left"=> 9.71,
            ),
            11 =>array
            (
                "cid"=>"11",
                "kind"=>"position",
                "name"=>"????????????",
                "top"=> 22.13,
                "left"=> 9.81,
            ),
            12 =>array
            (
                "cid"=>"12",
                "kind"=>"position",
                "name"=>"????????????",
                "top"=> 22.13,
                "left"=> 9.91,
            ),
            13 =>array
            (
                "cid"=>"13",
                "kind"=>"position",
                "name"=>"??????????????????",
               "top"=> 22.13,
                "left"=> 10.01,
            ),
            14 =>array
            (
                "cid"=>"14",
                "kind"=>"position",
                "name"=>"??????",
                "top"=> 22.13,
                "left"=> 10.11,
            ),
            15 =>array
            (
                "cid"=>"15",
                "kind"=>"department",
                "name"=>"???????????????",
                "top"=> 9.00,
                "left"=> 9.41,
            ),
            16 =>array
            (
                "cid"=>"16",
                "kind"=>"department",
                "name"=>"???????????????",
                 "top"=> 9.00,
                "left"=> 9.51,
            ),
            17 =>array
            (
                "cid"=>"17",
                "kind"=>"department",
                "name"=>"???????????????",
                 "top"=> 9.00,
                "left"=> 9.61,
            ),
            18 =>array
            (
                "cid"=>"18",
                "kind"=>"duty",
                "name"=>"??????????????????",
                "top"=> 35.90,
                "left"=> 9.11,
            ),
            19 =>array
            (
                "cid"=>"19",
                "kind"=>"duty",
                "name"=>"??????????????????",
                "top"=> 35.90,
                "left"=> 9.21,
            ),
            20 =>array
            (
                "cid"=>"20",
                "kind"=>"duty",
                "name"=>"???????????????????????????",
                "top"=> 35.90,
                "left"=> 9.31,
            ),
            21 =>array
            (
                "cid"=>"21",
                "kind"=>"duty",
                "name"=>"???????????????????????????",
                "top"=> 35.90,
                "left"=> 9.41,
            ),
            22 =>array
            (
                "cid"=>"22",
                "kind"=>"duty",
                "name"=>"????????????????????????",
                "top"=> 35.90,
                "left"=> 9.51,
            ),
            23 =>array
            (
                "cid"=>"23",
                "kind"=>"duty",
                "name"=>"????????????",
                "top"=> 35.90,
                "left"=> 9.61,
            ),
            24 =>array
            (
                "cid"=>"24",
                "kind"=>"duty",
                "name"=>"????????????",
                "top"=> 35.90,
                "left"=> 9.71,
            ),
            25 =>array
            (
                "cid"=>"25",
                "kind"=>"duty",
                "name"=>"????????????",
                "top"=> 35.90,
                "left"=> 9.81,
            ),
            26 =>array
            (
                "cid"=>"26",
                "kind"=>"duty",
                "name"=>"????????????",
                "top"=> 35.90,
                "left"=> 9.91,
            ),
            27 =>array
            (
                "cid"=>"27",
                "kind"=>"duty",
                "name"=>"????????????",
                "top"=> 35.90,
                "left"=> 10.11,
            ),
            28 =>array
            (
                "cid"=>"28",
                "kind"=>"duty",
                "name"=>"????????????????????????",
               "top"=> 35.90,
                "left"=> 10.01,
            ),
            29 =>array
            (
                "cid"=>"29",
                "kind"=>"duty",
                "name"=>"????????????",
                "top"=> 35.90,
                "left"=> 10.31,
            ),
            30 =>array
            (
                "cid"=>"30",
                "kind"=>"gsdepartment",
                "name"=>"???????????????????????????",
                "top"=> 9.00,
                "left"=> 56.31,
            ),
            31 =>array
            (
                "cid"=>"31",
                "kind"=>"gsdepartment",
                "name"=>"???????????????????????????",
                "top"=> 9.00,
                "left"=> 56.41,
            ),
            32 =>array
            (
                "cid"=>"32",
                "kind"=>"gsdepartment",
                "name"=>"???????????????????????????",
               "top"=> 9.00,
                "left"=> 56.51,
            ),
            33 =>array
            (
                "cid"=>"33",
                "kind"=>"gsdepartment",
                "name"=>"??????????????????????????????",
                "top"=> 9.00,
                "left"=> 56.61,
            ),
            34 =>array
            (
                "cid"=>"34",
                "kind"=>"gsposition",
                "name"=>"????????????",
                "top"=> 22.13,
                "left"=> 56.31,
            ),
            35 =>array
            (
                "cid"=>"35",
                "kind"=>"gsposition",
                "name"=>"????????????",
                "top"=> 22.13,
                "left"=> 56.41,
            ),
            36 =>array
            (
                "cid"=>"36",
                "kind"=>"gsposition",
                "name"=>"????????????",
               "top"=> 22.13,
                "left"=> 56.51,
            ),
            36 =>array
            (
                "cid"=>"36",
                "kind"=>"gsposition",
                "name"=>"????????????",
                "top"=> 22.13,
                "left"=> 56.61,
            ),
            37 =>array
            (
                "cid"=>"37",
                "kind"=>"gsposition",
                "name"=>"????????????",
                "top"=> 22.13,
                "left"=> 56.71,
            ),
            38 =>array
            (
                "cid"=>"38",
                "kind"=>"gsposition",
                "name"=>"????????????",
                "top"=> 22.13,
                "left"=> 56.81,
            ),
            39 =>array
            (
                "cid"=>"39",
                "kind"=>"gsposition",
                "name"=>"????????????",
                "top"=> 22.13,
                "left"=> 56.91,
            ),
            40 =>array
            (
                "cid"=>"40",
                "kind"=>"gsposition",
                "name"=>"????????????",
                "top"=> 22.13,
                "left"=> 57.01,
            ),
            41 =>array
            (
                "cid"=>"41",
                "kind"=>"gsposition",
                "name"=>"??????",
                 "top"=> 22.13,
                "left"=> 57.11,
            ),
            42 =>array
            (
                "cid"=>"42",
                "kind"=>"gsposition",
                "name"=>"????????????",
                 "top"=> 22.13,
                "left"=> 57.11,
            ),
            43 =>array
            (
                "cid"=>"43",
                "kind"=>"gsduty",
                "name"=>"????????????",
                "top"=> 35.90,
                "left"=> 56.31,
            ),
            44 =>array
            (
                "cid"=>"44",
                "kind"=>"gsduty",
                "name"=>"????????????",
                "top"=> 35.90,
                "left"=> 56.41,
            ),
            45 =>array
            (
                "cid"=>"45",
                "kind"=>"gsduty",
                "name"=>"????????????/????????????",
                "top"=> 35.90,
                "left"=> 56.51,
            ),
            46 =>array
            (
                "cid"=>"46",
                "kind"=>"gsduty",
                "name"=>"????????????",
                "top"=> 35.90,
                "left"=> 56.61,
            ),
            47 =>array
            (
                "cid"=>"47",
                "kind"=>"gsduty",
                "name"=>"????????????/????????????",
               "top"=> 35.90,
                "left"=> 56.71,
            ),
            48 =>array
            (
                "cid"=>"48",
                "kind"=>"gsduty",
                "name"=>"????????????",
                "top"=> 35.90,
                "left"=> 56.81,
            ),
            49 =>array
            (
                "cid"=>"49",
                "kind"=>"gsduty",
                "name"=>"????????????/????????????",
                "top"=> 35.90,
                "left"=> 56.91,
            ),
            50 =>array
            (
                "cid"=>"50",
                "kind"=>"gsduty",
                "name"=>"????????????",
                "top"=> 35.90,
                "left"=> 57.01,
            ),
            51 =>array
            (
                "cid"=>"51",
                "kind"=>"gsduty",
                "name"=>"????????????",
                "top"=> 35.90,
                "left"=> 57.11,
            ),
            52 =>array
            (
                "cid"=>"52",
                "kind"=>"gsduty",
                "name"=>"????????????",
                "top"=> 35.90,
                "left"=> 57.21,
            ),
             53 =>array
            (
                "cid"=>"53",
                "kind"=>"gsposition",
                "name"=>"????????????",
                "top"=> 22.13,
                "left"=> 57.21,
            )
        );
        return $carddata;
    }
}
