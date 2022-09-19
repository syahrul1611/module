<?php

namespace Modules\Category\Http\Controllers\Web\V1;

use Exception;
use Validator;
use App\Functions;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
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
            $q_desc = $request->q_desc;

            $result = $service->index(
                $q_paging,
                $q_name,
                $q_desc
            );

            if(Functions::exception($result)){
                throw new Exception($result->getMessage(),is_string($result->getCode()) ? (int)$result->getCode() : $result->getCode());
            }else{
                $datas = $result['datas'];
                return view('category::'.config('app.be_view').'.category_index', compact(
                    'datas'
                ));
            }
        }catch(\Exception $err){
            return back()->with('error',$err->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        try{
            return view('category::'.config('app.be_view').'.category_create');
        }catch(\Exception $err){
            return back()->with('error',$err->getMessage());
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
            $request->merge(['created_by' => Auth::user()->id]);
            $validator = Validator::make($request->all(),$rules);
            if ($validator->fails()){
                return back()->withInput()->withErrors($validator);
            }else{
                try{
                    $data = $service->store($request->all());
                    if(Functions::exception($data)){
                        throw new Exception($data->getMessage(),is_string($data->getCode()) ? (int)$data->getCode() : $data->getCode());
                    }else{
                        return redirect(route('admin.v1.access.category.index'))->with('success',config('app.message_store'));
                    }
                }catch(\Exception $err){
                    return back()->withInput()->with('error',$err->getMessage());
                }
            }
        }catch(\Exception $err){
            return back()->withInput()->with('error',$err->getMessage());  
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
            $datas = $service->edit($id);
            if(Functions::exception($datas)){
                throw new Exception($datas->getMessage(),is_string($datas->getCode()) ? (int)$datas->getCode() : $datas->getCode());
            }else{
                $data = $datas['data'];
                return view('category::'.config('app.be_view').'.category_edit', compact(
                    'data'
                ));
            }
        }catch(\Exception $err){
            return back()->withInput()->with('error',$err->getMessage());
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
            $request->merge(['updated_by' => Auth::user()->id]);
            $validator = Validator::make($request->all(),$rules);
            if ($validator->fails()){
                return back()->withInput()->withErrors($validator);
            }else{
                try{
                    $data = $service->update($request->all(), $id);
                    if(Functions::exception($data)){
                        throw new Exception($data->getMessage(),is_string($data->getCode()) ? (int)$data->getCode() : $data->getCode());
                    }else{
                        return redirect(route('admin.v1.access.category.index'))->with('success',config('app.message_update'));
                    }
                }catch(\Exception $err){
                    return back()->withInput()->with('error',$err->getMessage());
                }
            }
        }catch(\Exception $err){
            return back()->withInput()->with('error',$err->getMessage());
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
                return back()->with('success',config('app.message_destroy'));
            }
        }catch(\Exception $err){
            return back()->with('error',$err->getMessage());
        }
    }

    public function destroy_selected(Request $request, AccessCategoryService $service)
    {
        try{
            foreach($request->selected as $id){
                $service->destroy($id);
            }
            return back()->with('success',config('app.message_destroy'));
        }catch(\Exception $err){
            return back()->with('error',$err->getMessage());
        }
    }
}