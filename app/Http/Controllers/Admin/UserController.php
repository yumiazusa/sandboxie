<?php
/**
 * @author  Eddy <cumtsjh@163.com>
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UserRequest;
use App\Model\Admin\User;
use App\Repository\Admin\UserRepository;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Imports\AdminsImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Front\HomeController;

class UserController extends Controller
{
    protected $formNames = ['phone', 'status'];

    public function __construct()
    {
        parent::__construct();

        $this->breadcrumb[] = ['title' => '会员列表', 'url' => route('admin::user.index')];
    }

    /**
     * 会员管理-会员列表
     *
     */
    public function index()
    {
        $this->breadcrumb[] = ['title' => '会员列表', 'url' => ''];
        return view('admin.user.index', ['breadcrumb' => $this->breadcrumb]);
    }

    /**
     * 会员管理-会员列表数据接口
     *
     * @param Request $request
     * @return array
     */
    public function list(Request $request)
    {
        $perPage = (int) $request->get('limit', 50);
        $this->formNames[] = 'created_at';
        $condition = $request->only($this->formNames);

        $data = UserRepository::list($perPage, $condition);

        return $data;
    }

    /**
     * 会员管理-新增会员
     *
     */
    public function create()
    {
        $this->breadcrumb[] = ['title' => '新增会员', 'url' => ''];
        return view('admin.user.add', ['breadcrumb' => $this->breadcrumb]);
    }

    /**
     * 会员管理-批量新增会员
     *
     */
    public function excel()
    {
        $this->breadcrumb[] = ['title' => '批量新增会员', 'url' => ''];
        return view('admin.user.addexcl', ['breadcrumb' => $this->breadcrumb]);
    }

    public function liuyuan()
    {
        $this->breadcrumb[] = ['title' => '柳渊的数据', 'url' => ''];
        DB::connection()->enableQueryLog();
        $res = DB::table('sheet1')->get();
        // $res = DB::table('sheet1')->limit(100)->get();
        // $res = DB::table('sheet1')->where('id',4)->count();
        // dump(DB::getQueryLog());
        // dd($res);
        $list = [];
        foreach($res as $k => $v){
            $list[$v->Stkcd]['Stkcd'] = $v->Stkcd;
            $list[$v->Stkcd]['Stknme'] = $v->Stknme;
            $list[$v->Stkcd]['list'][$v->year][$v->ItemNo] = $v->Classification;
        }
        // dd($list);
        $diff = [];
        foreach($list as $k => $v){
            $size = sizeof($v['list']);
            $first = array_key_first($v['list']);
            for($i = $first; $i < $first+$size; $i++) {
                if(isset($v['list'][$i]) && isset($v['list'][$i-1])){
                    $res =$this->compare($v['list'][$i],$v['list'][$i-1],$i-1);
                    dump($res);
                }else{
                    continue;
                }
            }
        }

        return view('admin.user.liuyuan', ['breadcrumb' => $this->breadcrumb]);

    }

    public function compare($arr1, $arr2,$year){
        if(is_array($arr1) && is_array($arr2) ){     
            $res =  array_diff($arr1,$arr2);
            $size1= sizeof($arr1);
            $size2= sizeof($arr2);
            if(!$res){
                if($size1 < $size2){
                    $rs =  array_diff($arr2,$arr1);
                    return [
                            'code' => 1,
                            'msg' => "较".$year."年有精简,简化了:".implode("，",$rs),
                            ];
                }elseif($size1 = $size2){
                    return [
                        'code' => 0,
                        'msg' => "较".$year."年没改变",
                        ];
                }
            }else{
                return [
                    'code' => 2,
                    'msg' => "较".$year."年有改变,改变项为:".implode("，",$res),
                    ]; 
            }
        }else{
            return [
                'code' => 3,
                'msg' => "有错误，请检查",
                ];
        }
    }
  
  

        //上传文件
    // public function addex(Request $request)
    // {

    //     if (!$request->hasFile('file')) {
    //         return [
    //             'code' => 2,
    //             'msg' => '非法请求'
    //         ];
    //     }
    //     $file = $request->file('file');
    //     if (!$this->isValidFile($file)) {
    //         return [
    //             'code' => 3,
    //             'msg' => '文件不合要求'
    //         ];
    //     }

    //     $result = $file->store('file/' . date('Ym'), config('light.neditor.disk'));
    //     if (!$result) {
    //         return [
    //             'code' => 3,
    //             'msg' => '上传失败'
    //         ];
    //     }
    //     // return $result;
    //     // return [
    //     //     'code' => 200,
    //     //     'state' => 'SUCCESS', // 兼容ueditor
    //     //     'msg' => '',
    //     //     'url' => Storage::disk(config('light.neditor.disk'))->url($result),
    //     // ];

          // 存储并读取文件
    //     Storage::disk(config('light.neditor.disk'))->url($result);
    //     $url= 'upload/image/'.$result;
    //     Excel::import(new AdminsImport, $url);
        
    // }
    public function addex(Request $request)
    {
            $file = $request->file('file');
            // $result = $file->store('file/' . date('Ym'), config('light.neditor.disk'));
            // Storage::disk(config('light.neditor.disk'))->url($result);
            // $url= 'upload/image/'.$result;
            $data =(new AdminsImport)->toArray($file)[0];
            for($i = 1; $i < count($data); $i++) {
             $da = [
            'studentid' => (int)$data[$i][0],
            'password' => bcrypt($data[$i][1]),
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
             ];
             $id = DB::table('users')->insertGetId($da);
             $daa = new HomeController;
             $datafee = $daa->feeArray();
             $card1=[
            'parent_id' => $id,
            'carddata' => serialize($datafee),
            // 'cardposition' => serialize($cardposition),
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
            ];
            $datasale = $daa->saleArray();
             $card2=[
            'parent_id' => $id,
            'carddata' => serialize($datasale),
            // 'cardposition' => serialize($cardposition),
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
            ];
             $datapurchase = $daa->purchaseArray();
             $card3=[
            'parent_id' => $id,
            'carddata' => serialize($datapurchase),
            // 'cardposition' => serialize($cardposition),
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
            ];
            $datastrategy = $daa->strategyArray();
             $card4=[
            'parent_id' => $id,
            'carddata' => serialize($datastrategy),
            // 'cardposition' => serialize($cardposition),
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
            ];
            $dataplan = $daa->planArray();
             $card5=[
            'parent_id' => $id,
            'carddata' => serialize($dataplan),
            // 'cardposition' => serialize($cardposition),
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
            ];
            $res = DB::table('card1')->updateOrInsert($card1);
            $res2 = DB::table('card2')->updateOrInsert($card2);
            $res3 = DB::table('card3')->updateOrInsert($card3);
            $res4 = DB::table('card4')->updateOrInsert($card4);
            $res5 = DB::table('card5')->updateOrInsert($card5);
            }

            // $res = UserRepository::insert($user);
            // dd($res);
            // $res = DB::table('users')->insert($user);
            if($res && $res2 && $res3 && $res4 && $res5){
                return [
                'code' => 0,
                'msg' => '新增成功',
                'redirect' => true
            ];
            }else{
                return [
                'code' => 1,
                'msg' => '新增失败',
                'redirect' => false
             ];
            }
    }

    protected function isValidFile(UploadedFile $file)
    {
        $c = config('light.neditor.upload');
        $config = [
            'maxSize' => $c['fileMaxSize'],
            'AllowFiles' => $c['fileAllowFiles'],
        ];

        return $this->isValidUploadedFile($file, $config);
    }

    protected function isValidUploadedFile(UploadedFile $file, array $config)
    {
        if (!$file->isValid() ||
            $file->getSize() > $config['maxSize'] ||
            !in_array(
                '.' . strtolower($file->getClientOriginalExtension()),
                $config['AllowFiles']
            ) ||
            !in_array(
                '.' . strtolower($file->guessExtension()),
                $config['AllowFiles']
            )
        ) {
            return false;
        }

        return true;
    }

    /**
     * 会员管理-保存会员
     *
     * @param UserRequest $request
     * @return array
     */
    public function save(UserRequest $request)
    {
        try {
            array_push($this->formNames, 'password','studentid');
            UserRepository::add($request->only($this->formNames));
            return [
                'code' => 0,
                'msg' => '新增成功',
                'redirect' => true,
            ];
        }
        catch (QueryException $e) {
            return [
                'code' => 1,
                'msg' => '新增失败：' . (Str::contains($e->getMessage(), 'Duplicate entry') ? '当前会员已存在' : '其它错误'),
                'redirect' => false
            ];
        }
    }

    /**
     * 会员管理-编辑会员
     *
     * @param int $id
     * @return View
     */
    public function edit($id)
    {
        $this->breadcrumb[] = ['title' => '编辑会员', 'url' => ''];

        $model = UserRepository::find($id);
        return view('admin.user.add', ['id' => $id, 'model' => $model, 'breadcrumb' => $this->breadcrumb]);
    }

    /**
     * 会员管理-更新会员
     *
     * @param UserRequest $request
     * @param int $id
     * @return array
     */
    public function update(UserRequest $request, $id)
    {
        $data = $request->only($this->formNames);
        if (!isset($data['status'])) {
            $data['status'] = User::STATUS_DISABLE;
        }
        try {
            UserRepository::update($id, $data);
            return [
                'code' => 0,
                'msg' => '编辑成功',
                'redirect' => true
            ];
        } catch (QueryException $e) {
            return [
                'code' => 1,
                'msg' => '编辑失败：' . (Str::contains($e->getMessage(), 'Duplicate entry') ? '当前会员已存在' : '其它错误'),
                'redirect' => false
            ];
        }
    }

    /**
     * 会员管理-删除会员
     *
     * @param int $id
     */
    public function delete($id)
    {
        try {
            UserRepository::delete($id);
            return [
                'code' => 0,
                'msg' => '删除成功',
                'redirect' => route('admin::user.index')
            ];
        } catch (\RuntimeException $e) {
            return [
                'code' => 1,
                'msg' => '删除失败：' . $e->getMessage(),
                'redirect' => false
            ];
        }
    }

    public function createNav(Request $request){
        if(!$request->id){
            return [
                'code' => 2,
                'msg' => '初始失败',
                'reload' => true
            ];
        }
         $re =  DB::table('card1')->where('parent_id',$request->id)->exists();
         $re2 =  DB::table('card2')->where('parent_id',$request->id)->exists();
         $re3 =  DB::table('card3')->where('parent_id',$request->id)->exists();
         $re4 =  DB::table('card4')->where('parent_id',$request->id)->exists();
         $re5 =  DB::table('card5')->where('parent_id',$request->id)->exists();
         if($re && $re2 && $re3 && $re4 && $re5){
            return [
                'code' => 2,
                'msg' => '用户已初始过沙盘',
                'reload' => true
            ];
         }
         $da = new HomeController;
         $data = $da->feeArray();
         $data2 = $da->saleArray();
         $data3 = $da->purchaseArray();
         $data4 = $da->strategyArray();
         $data5 = $da->planArray();
         $card=[
            'parent_id' => $request->id,
            'carddata' => serialize($data),
            // 'cardposition' => serialize($cardposition),
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
         ];
         $card2=[
            'parent_id' => $request->id,
            'carddata' => serialize($data2),
            // 'cardposition' => serialize($cardposition),
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
         ];
         $card3=[
            'parent_id' => $request->id,
            'carddata' => serialize($data3),
            // 'cardposition' => serialize($cardposition),
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
         ];
         $card4=[
            'parent_id' => $request->id,
            'carddata' => serialize($data4),
            // 'cardposition' => serialize($cardposition),
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
         ];
          $card5=[
            'parent_id' => $request->id,
            'carddata' => serialize($data5),
            // 'cardposition' => serialize($cardposition),
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
         ];
            $res = DB::table('card1')->updateOrInsert($card);
            $res2 = DB::table('card2')->updateOrInsert($card2);
            $res3 = DB::table('card3')->updateOrInsert($card3);
            $res4 = DB::table('card4')->updateOrInsert($card4);
            $res5 = DB::table('card5')->updateOrInsert($card5);
         if(!$res && !$res2 && !$res3 && !$res4 && !$res5){
            return [
                'code' => 2,
                'msg' => '初始失败',
                'reload' => true
            ];
         }
        return [
                'code' => 1,
                'msg' => '初始成功',
                'reload' => true
            ];
    }
}
