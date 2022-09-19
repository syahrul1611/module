<?php

namespace Modules\Post\Http\Services\V1;

use Exception;
use App\Functions;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Facades\DB;
use Modules\Post\Entities\V1\Post;
use Illuminate\Support\Facades\Storage;
use Modules\Category\Entities\V1\Category;

class AccessPostService
{
    public function index(
        $q_paging = null,
        $q_title = null,
        $q_content = null,
        $q_category = null
    )
    {
        DB::beginTransaction();
        try{
            $datas['datas'] = Post::
            when($q_title,function($query) use($q_title){
                $query->whereNotNull('title')
                ->where('title','like','%'.$q_title.'%');
            })
            ->when($q_content,function($query) use($q_content){
                $query->whereNotNull('content')
                ->where('content','like','%'.$q_content.'%');
            })
            ->when($q_category,function($query) use($q_category){
                $query->whereHas('category',function($query) use($q_category){
                    $query->where('id',$q_category);
                });
            })
            ->paginate($q_paging);
            $datas['categories'] = Category::all();
            DB::commit();
            return $datas;
        }catch(\Exception $err){
            DB::rollback();
            return $err;
        }        
    }

    public function show($id)
    {
        DB::beginTransaction();
        try{
            $result = Post::with(['category'])->where('id',$id)->first();
            if(!$result){
                throw new Exception("",404);
            }
            DB::commit();
            return $result;
        }catch(\Exception $err){
            DB::rollback();
            return $err;
        }        
    }

    public function create()
    {
        DB::beginTransaction();
        try{
            $datas = Category::all();
            if(Functions::exception($datas)){
                throw new Exception($datas->getMessage(),is_string($datas->getCode()) ? (int)$datas->getCode() : $datas->getCode());
            }else{
                DB::commit();
                return $datas;
            }
        }catch(\Exception $err){
            DB::rollback();
            return $err;
        }
    }

    public function store($data)
    {
        DB::beginTransaction();
        try{
            $uuid = Uuid::uuid6();
            $arrs = [
                'id' => $uuid
            ];

            $image = [];
            $category = [];
            $new_array = [];
            $new_array = array_merge($new_array,$arrs);
            foreach($data as $key => $val){
                if($key == 'image'){
                    $image = $val;
                }elseif($key == 'category'){
                    $category = $val;
                }else{
                    $new_array[$key] = $val;
                }
            }

            if($image){
                $file_1 = $image;
                $file_name_1 = $uuid.'.'.$file_1->getClientOriginalExtension();
                $file_1->storeAs(config('access.private').'post',$file_name_1);
                $new_array = array_merge($new_array,['image' => $file_name_1]);
            }

            $category_result = Category::where('name',$category)->first();
            $new_array['category_id'] = $category_result->id;
    
            $result = Post::create($new_array);
            if(Functions::exception($result)){
                throw new Exception($result->getMessage(),is_string($result->getCode()) ? (int)$result->getCode() : $result->getCode());
            }else{
                DB::commit();
                return $result;
            }
        }catch(\Exception $err){
            DB::rollback();
            return $err;
        }            
    }

    public function edit($id)
    {
        DB::beginTransaction();
        try{
            $datas = [];
            $datas['data'] = Post::with(['category'])->where('id',$id)->first();
            if(!$datas['data']){
                throw new Exception("",404);
            }
            DB::commit();
            $datas['categories'] = Category::all();
            return $datas;
        }catch(\Exception $err){
            DB::rollback();
            return $err;
        }        
    }

    public function update($data, $id)
    {
        DB::beginTransaction();
        try{
            $image = [];
            $new_array = [];
            foreach($data as $key => $val){
                if($key == 'image'){
                    $image = $val;
                }else{
                    $new_array[$key] = $val;
                }
            }
   
            $result = Post::find($id);
            if(Functions::exception($result)){
                throw new Exception($result->getMessage(),is_string($result->getCode()) ? (int)$result->getCode() : $result->getCode());
            }else{
                if(!$result){
                    throw new Exception("",404);
                }
    
                if($image){
                    $file_1 = $image;
                    $file_name_1 = $result->id.'.'.$file_1->getClientOriginalExtension();
                    $file_1->storeAs(config('access.private').'post',$file_name_1);
                    Storage::delete(config('access.private').'post/'.$result->image);
                    $new_array = array_merge($new_array,['image' => $file_name_1]);
                }
    
                $result1 = $result->update($new_array);
                if(Functions::exception($result1)){
                    throw new Exception($result1->getMessage(),$result1->getCode());
                }else{
                    DB::commit();
                    return $result1;
                }
            }
        }catch(\Exception $err){
            DB::rollback();
            return $err;
        }            
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try{
            $result = Post::find($id);
            if(Functions::exception($result)){
                throw new Exception($result->getMessage(),is_string($result->getCode()) ? (int)$result->getCode() : $result->getCode());
            }else{
                if(!$result){
                    throw new Exception("",404);
                }
                $result1 = $result->delete();
                if(Functions::exception($result1)){
                    throw new Exception($result1->getMessage(),$result1->getCode());
                }else{
                    Storage::delete(config('access.private').'post/'.$result->image);
                    DB::commit();
                    return $result1;
                }    
            }
        }catch(\Exception $err){
            DB::rollback();
            return $err;
        }        
    }

    public function file($filename)
    {
        if(Storage::exists(config('access.private').'post/'.$filename)){
            return file_show(config('access.private').'post/'.$filename);
        }
        return abort(404);
    }
};