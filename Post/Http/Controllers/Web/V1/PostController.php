<?php

namespace Modules\Post\Http\Controllers\Web\V1;

use Exception;
use Validator;
use App\Functions;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Support\Renderable;
use Modules\Post\Http\Requests\V1\AccessPostRequest;
use Modules\Post\Http\Services\V1\AccessPostService;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request, AccessPostService $service)
    {
        try{
            if(!isset($request->q_paging)){
                $q_paging = 10;
            }else{
                $q_paging = $request->q_paging;
            }

            $q_title = $request->q_title;
            $q_content = $request->q_content;
            $q_category = $request->q_category;

            $result = $service->index(
                $q_paging,
                $q_title,
                $q_content,
                $q_category
            );

            if(Functions::exception($result)){
                throw new Exception($result->getMessage(),is_string($result->getCode()) ? (int)$result->getCode() : $result->getCode());
            }else{
                $datas = $result['datas'];
                $categories = $result['categories'];
                return view('post::'.config('app.be_view').'.post_index', compact(
                    'datas',
                    'categories'
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
    public function create(AccessPostService $service)
    {
        try{
            $categories = $service->create();
            if(Functions::exception($categories)){
                throw new Exception($categories->getMessage(),$categories->getCode());
            }else{
                return view('post::'.config('app.be_view').'.post_create',compact('categories'));
            }
        }catch(\Exception $err){
            return back()->with('error',$err->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request, AccessPostService $service)
    {
        try{
            $rules = (new AccessPostRequest)->rules();
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
                        return redirect(route('admin.v1.access.post.index'))->with('success',config('app.message_store'));
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
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id, AccessPostService $service)
    {
        try{
            $data = $service->show($id);
            if(Functions::exception($data)){
                throw new Exception($data->getMessage(),is_string($data->getCode()) ? (int)$data->getCode() : $data->getCode());
            }else{
                return view('post::'.config('app.be_view').'.post_show',compact('data'));
            }
        }catch(\Exception $err){
            return back()->with('error',$err->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id, AccessPostService $service)
    {
        try{
            $datas = $service->edit($id);
            if(Functions::exception($datas)){
                throw new Exception($datas->getMessage(),is_string($datas->getCode()) ? (int)$datas->getCode() : $datas->getCode());
            }else{
                $data = $datas['data'];
                $categories = $datas['categories'];
                return view('post::'.config('app.be_view').'.post_edit', compact(
                    'data',
                    'categories'
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
    public function update(Request $request, $id, AccessPostService $service)
    {
        try{
            $rules = (new AccessPostRequest)->rules($id);
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
                        return redirect(route('admin.v1.access.post.index'))->with('success',config('app.message_update'));
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
    public function destroy($id, AccessPostService $service)
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

    public function destroy_selected(Request $request, AccessPostService $service)
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

    public function file(AccessPostService $service, $filename)
    {
        return $service->file($filename);
    }
}
