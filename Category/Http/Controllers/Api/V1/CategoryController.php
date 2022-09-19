<?php

namespace Modules\Category\Http\Controllers\Api\V1;

use Exception;
use Validator;
use App\Response;
use App\Functions;
use Illuminate\Http\Request;
use App\Http\Resources\Collection;
use Illuminate\Routing\Controller;
use App\Http\Resources\DetailResource;
use Illuminate\Contracts\Support\Renderable;
use Modules\Category\Http\Requests\V1\AccessCategoryRequest;
use Modules\Category\Http\Services\V1\AccessCategoryService;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request, AccessCategoryService $service)
    {
        try{
            if(!isset($request->q_paging)){
                $q_paging = 10;
            }else{
                $q_paging = $request->q_paging;
            }

            $q_name = $request->q_name;

            $result = $service->index(
                $q_paging,
                $q_name
            );

            if(Functions::exception($result)){
                throw new Exception($result->getMessage(),is_string($result->getCode()) ? (int)$result->getCode() : $result->getCode());
            }else{
                $datas = $result['datas'];
                return new Collection($datas);
            }
        }catch(\Exception $err){
            return Response::error($err);        
        }
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        try{
            return Response::true();
        }catch(\Exception $err){
            return Response::error($err);        
        }
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request, AccessCategoryService $service)
    {
        try{
            $rules = (new AccessCategoryRequest)->rules();
            $validator = Validator::make($request->all(),$rules);
            if ($validator->fails()){
                return Response::_400($validator->errors());
            }else{
                try{
                    $data = $service->store($request->all());
                    if(Functions::exception($data)){
                        throw new Exception($data->getMessage(),is_string($data->getCode()) ? (int)$data->getCode() : $data->getCode());
                    }else{
                        return Response::_201(data: $data);
                    }
                }catch(\Exception $err){
                    return Response::_500($err->getMessage());
                }
            }
        }catch(\Exception $err){
            return Response::error($err);        
        }
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id, AccessCategoryService $service)
    {
        try{
            $data = $service->edit($id);
            if(Functions::exception($data)){
                throw new Exception($data->getMessage(),is_string($data->getCode()) ? (int)$data->getCode() : $data->getCode());
            }else{
                return new DetailResource($data);
            }
        }catch(\Exception $err){
            return Response::error($err);        
        }
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id, AccessCategoryService $service)
    {
        try{
            $rules = (new AccessCategoryRequest)->rules($id);
            $validator = Validator::make($request->all(),$rules);
            if($validator->fails()){
                return Response::_400($validator->errors());
            }else{
                try{
                    $data = $service->update($request->all(),$id);
                    if(Functions::exception($data)){
                        throw new Exception($data->getMessage(),is_string($data->getCode()) ? (int)$data->getCode() : $data->getCode());
                    }else{
                        return Response::true();
                    }
                }catch(\Exception $err){
                    return Response::_500($err->getMessage());
                }
            }
        }catch(\Exception $err){
            return Response::error($err);        
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id, AccessCategoryService $service)
    {
        try{
            $data = $service->destroy($id);
            if(Functions::exception($data)){
                throw new Exception($data->getMessage(),is_string($data->getCode()) ? (int)$data->getCode() : $data->getCode());
            }else{
                return Response::true();
            }
        }catch(\Exception $err){
            return Response::error($err);        
        }
    }

    public function destroy_selected(Request $request, AccessCategoryService $service)
    {
        try{
            foreach($request->selected as $id){
                $service->destroy($id);
            }
            return Response::true();
        }catch(\Exception $err){
            return Response::error($err);        
        }
    }
}
