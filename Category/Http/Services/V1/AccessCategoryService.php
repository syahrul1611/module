<?php

namespace Modules\Category\Http\Services\V1;

use Modules\Category\Entities\V1\User;
use Modules\Category\Entities\V1\Role;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;
use Carbon\Carbon;
use Storage;
use Illuminate\Support\Facades\Hash;
use App\Functions;
use Exception;
use Modules\Category\Entities\V1\Category;

class AccessCategoryService
{
    public function index(
        $q_paging = null,
        $q_name = null,
        $q_desc = null
    )
    {
        DB::beginTransaction();
        try{
            $datas['datas'] = Category::
            when($q_name,function($query) use($q_name){
                $query->whereNotNull('name')
                ->where('name','like','%'.$q_name.'%');
            })
            ->when($q_desc,function($query) use($q_desc){
                $query->whereNotNull('desc')
                ->where('desc','like','%'.$q_desc.'%');
            })
            ->paginate($q_paging);
            DB::commit();
            return $datas;
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

            $data = array_merge($data,$arrs);

            $result = Category::create($data);
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
            $result = Category::where('id',$id)->first();
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

    public function update($data,$id)
    {
        DB::beginTransaction();
        try{
            $result = Category::find($id);
            if(Functions::exception($result)){
                throw new Exception($result->getMessage(),is_string($result->getCode()) ? (int)$result->getCode() : $result->getCode());
            }else{
                if(!$result){
                    throw new Exception("",404);
                }
                $result = $result->update($data);
                if(Functions::exception($result)){
                    throw new Exception($result->getMessage(),is_string($result->getCode()) ? (int)$result->getCode() : $result->getCode());
                }else{
                    DB::commit();
                    return $result;
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
            $result = Category::find($id);
            if(Functions::exception($result)){
                throw new Exception($result->getMessage(),is_string($result->getCode()) ? (int)$result->getCode() : $result->getCode());
            }else{
                if(!$result){
                    throw new Exception("",404);
                }
                $result = $result->delete();
                if(Functions::exception($result)){
                    throw new Exception($result->getMessage(),is_string($result->getCode()) ? (int)$result->getCode() : $result->getCode());
                }else{
                    DB::commit();
                    return $result;
                }
            }
        }catch(\Exception $err){
            DB::rollback();
            return $err;
        }        
    }
}