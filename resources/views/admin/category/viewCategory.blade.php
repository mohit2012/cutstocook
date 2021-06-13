@extends('admin.master', ['title' => __('Manage Category')])

@section('content')
    @include('admin.layout.topHeader', [
        'title' => __('Category') ,
        'class' => 'col-lg-7'
    ]) 
    <div class="container-fluid mt--7">
           
        <div class="row">
            <div class="col">
                    <div class="card form-card shadow">
                        <div class="card-header border-0">
                            <div class="row align-items-center">
                                <div class="col-8">
                                    <h3 class="mb-0">{{ __('Category') }}</h3>
                                </div>
                                <div class="col-4 text-right">
                                    <a href="{{url('owner/Category/create')}}" class="btn btn-sm btn-primary">{{ __('Add New Category') }}</a>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            @if(count($categories)>0)
                                <table class="table align-items-center table-flush">
                                    <thead class="thead-light">
                                        <tr>
                                            <th scope="col">{{ __('Id') }}</th>
                                            <th scope="col">{{ __('Image') }}</th>
                                            <th scope="col">{{ __('Name') }}</th>
                                            <th scope="col">{{ __('Status') }}</th>    
                                            <th scope="col">{{ __('Action') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($categories as $category)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td><img class=" avatar-lg round-5" src="{{url('images/upload/'.$category->image)}}"></td>
                                                <td>{{ $category->name }}</td>                                              
                                                <td>
                                                    <span class="badge badge-dot mr-4">
                                                        <i class="{{$category->status==0?'bg-success': 'bg-danger'}}"></i>
                                                        <span class="status">{{$category->status==0?'Active': 'Inactive'}}</span>
                                                    </span>
                                                </td>
                                                <td>                            
                                          
                                                    <div class="dropdown">
                                                            <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                <i class="fas fa-ellipsis-v"></i>
                                                            </a>
                                                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                                                <a class="dropdown-item" href="{{url('owner/Category/'.$category->id.'/edit')}}">{{ __('Edit') }}</a>
                                                                <a class="dropdown-item " onclick="deleteData('owner/Category','{{$category->id}}');" href="#">{{ __('Delete') }}</a>                                                               
                                                            </div>
                                                        </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <?php echo $categories->render(); ?>
                                @else 
                                    <div class="empty-state text-center pb-3">
                                        <img src="{{url('images/empty3.png')}}" style="width:35%;height:220px;">
                                        <h2 class="pt-3 mb-0" style="font-size:25px;">Nothing!!</h2>
                                        <p style="font-weight:600;">Your Collection list is empty....</p>
                                    </div> 
                                @endif
                            </div>
                    </div>
            </div>
        </div>
       
    </div>

@endsection