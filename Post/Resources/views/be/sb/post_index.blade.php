@extends(config('app.be_layout').'.main')
@section('content')
            @if(session('error'))
                <div class="card shadow bg-danger text-white" style="margin-bottom:20px;">
                    <div class="card-body" style="overflow-x:auto;padding:10px;">{!! session('error') !!}</div>
                </div>
            @endif
            @if(session('success'))
                <div class="card shadow bg-success text-white" style="margin-bottom:20px;">
                    <div class="card-body" style="overflow-x:auto;padding:10px;">{!! session('success') !!}</div>
                </div>
            @endif
          <!-- Page Heading -->
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Post Management</h1>
            <div class="d-none d-sm-inline-block">
            </div>
          </div>
          <!-- Content Row -->
          <div class="row">
            <!-- Area Chart -->
            <div class="col-xl-12 col-lg-12">
                <div class="card shadow mb-4">
                    <!-- Card Header - Dropdown -->
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                      <h6 class="m-0 font-weight-bold text-primary">Post List</h6>
                      <div class="btn-group btn-sm nospiner" >
                        <a  href="{{ route('admin.v1.access.post.create') }}"  class="btn btn-success btn-sm" >
                          <i class="fas fa-fw fa-plus"></i> Add Post
                        </a>
                        <button type="submit" form="selection" formmethod="post" formaction="{{ route('admin.v1.access.post.delete.selected') }}" onclick="return confirm('Confirm Delete')" class="btn btn-danger btn-sm">
                          <i class="fas fa-fw fa-times"></i> Delete Selected
                        </button>
                      </div>                                      
                    </div>
                    <!-- Card Body -->
                    <div class="card-body" style="overflow-x:auto;padding:20px;">          
                    <table class="table table-bordered table-hover">
                            <thead>
                              <form id="searchform">
                                @csrf
                                <tr>
                                    <th width="5%"></th>
                                    <th width="10%">
                                      <select name="paging" id="paging"  class="form-control form-control-user @error('paging') is-invalid @enderror" value="{{ old('paging') }}" autocomplete="paging">
                                          <option value="10" {{ ((!isset($paging)) ? 'selected' : ($paging == 10)) ? 'selected' : ''}}>10</option>
                                          <option value="20" {{ ((!isset($paging)) ? '' : ($paging == 20)) ? 'selected' : ''}}>20</option>
                                          <option value="50" {{ ((!isset($paging)) ? '' : ($paging == 50)) ? 'selected' : ''}}>50</option>
                                          <option value="100" {{ ((!isset($paging)) ? '' : ($paging == 100)) ? 'selected' : ''}}>100</option>
                                      </select>
                                    </th>
                                    <th width="15%">
                                      <input id="q_title" type="text" class="form-control form-control-user @error('q_title') is-invalid @enderror" name="q_title" value="{{ (isset($q_title)) ? $q_title : '' }}" autocomplete="q_title">
                                    </th>
                                    <th width="20%">
                                    </th>
                                    <th width="35%">
                                      <input id="q_content" type="text" class="form-control form-control-user @error('q_content') is-invalid @enderror" name="q_content" value="{{ (isset($q_content)) ? $q_content : '' }}" autocomplete="q_content">
                                    </th>
                                    <th width="15%">
                                      <select name="q_category" id="q_category"  class="form-control form-control-user @error('q_category') is-invalid @enderror" value="{{ old('q_category') }}" autocomplete="q_category">
                                          <option disabled="disabled" selected="selected">Select</option>                                        
                                          @foreach($categories as $category)
                                              <option value="{{$category->id}}" {{ ((!isset($q_category)) ? '' : ($q_category == $category->id)) ? 'selected' : ''}}>{{$category->name}}</option>
                                          @endforeach
                                      </select>
                                    </th>
                                    <th width="5%" class="text-center align-middle">
                                      <div class="btn-group nospiner" >
                                        <button type="submit" form="searchform" class="btn btn-sm btn-success">
                                          <i class="fas fa-fw fa-search"></i>
                                        </button>
                                        <a  href="{{ route('admin.v1.access.post.index') }}"  class="btn btn-sm btn-danger">
                                          <i class="fas fa-fw fa-times"></i>                                            
                                        </a>
                                      </div>                                      
                                    </th>
                                </tr>                              
                                <tr>
                                    <th width="5%"><input type="checkbox" id="checkall"/></th>
                                    <th width="10%">No</th>
                                    <th width="15%">Title</th>
                                    <th width="20%">Image</th>
                                    <th width="30%">Content</th>
                                    <th width="20%">Category</th>
                                    <th>Action</th>
                                </tr>
                              </form>
                            </thead>
                            <tbody>
                              <form id="selection">
                              @csrf
                                @foreach ($datas as $data)
                                    <tr>
                                        <td><input name="selected[]" value="{{ $data->id }}" type="checkbox" /></td>
                                        <td>{{($datas->currentPage() - 1) * $datas->perPage() + $loop->iteration}}</td>
                                        <td>{{ $data->title }}</td>
                                        <td align="center"><img src="{{ route('admin.v1.access.post.file',$data->image) }}" width="100px" height="100px"/></td>
                                        <td>{!! $data->content !!}</td>
                                        <td>{{ $data->category->name }}</td>
                                        <td align="center">
                                          <div class="btn-group">
                                            <button type="button" class="btn btn-sm btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                              Action
                                            </button>
                                            <div class="dropdown-menu">
                                              <a href="{{ route('admin.v1.access.post.show',$data->id) }}" class="dropdown-item">View</a>
                                              <a href="{{ route('admin.v1.access.post.edit',$data->id) }}" class="dropdown-item">Edit</a>
                                              <a href="{{ route('admin.v1.access.post.delete',$data->id) }}" onclick="return confirm('Confirm Delete')" class="dropdown-item">Delete</a>                                              
                                            </div>
                                          </div>
                                        </td>
                                    </tr>
                                @endforeach
                              </form>
                            </tbody>
                        </table>
                        <div class="d-none d-sm-inline-block" style="float:right;">
                          {!! $datas->appends(request()->input())->links('vendor.pagination.bootstrap-4') !!}
                        </div>          
                    </div>
                </div>                
            </div>
          </div>
        <script>
          $("#checkall").click(function(){
              $('input:checkbox').not(this).prop('checked', this.checked);
          });        
        </script>
        @endsection