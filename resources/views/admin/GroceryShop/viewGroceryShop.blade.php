@extends('admin.master', ['title' => __('Shops')])

@section('content')
    @include('admin.layout.topHeader', [
        'title' => __('Grocery Shops') ,
        'class' => 'col-lg-7'
    ])
    <div class="container-fluid mt--7">

        <div class="row">
            <div class="col">
                    <div class="card form-card shadow">
                        <div class="card-header border-0">
                            <div class="row align-items-center">
                                <div class="col-8">
                                    <h3 class="mb-0">{{ __('Grocery Shops') }}</h3>
                                </div>
                                <div class="col-4 text-right">
                                    <a href="{{url('owner/GroceryShop/create')}}" class="btn btn-sm btn-primary">{{ __('Add Grocery Shop') }}</a>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            @if(count($shops)>0)
                                <table class="table align-items-center table-flush">
                                    <thead class="thead-light">
                                        <tr>
                                            <th scope="col">{{ __('#') }}</th>
                                            <th scope="col">{{ __('Image') }}</th>
                                            <th scope="col">{{ __('Name') }}</th>
                                            <th scope="col">{{ __('Location') }}</th>
                                            <th scope="col">{{ __('Radius') }}</th>
                                            <th scope="col">{{ __('Status') }}</th>
                                            <th scope="col">{{ __('Action') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($shops as $shop)

                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td><img class="avatar-lg round-5" src="{{url('images/upload/'.$shop->image)}}"></td>
                                                <td>{{ $shop->name }}</td>
                                                <td>{{ $shop->locationData?$shop->locationData->name:'-'}}</td>

                                                <td>{{ $shop->radius.'km'}}</td>
                                                <td>
                                                    <span class="badge badge-dot mr-4">
                                                        <i class="{{$shop->status==0?'bg-success': 'bg-danger'}}"></i>
                                                        <span class="status">{{$shop->status==0?'Active': 'Inactive'}}</span>
                                                    </span>
                                                </td>
                                                <td>                                      
                                                    <div class="dropdown">
                                                        <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            <i class="fas fa-ellipsis-v"></i>
                                                        </a>
                                                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                                            <a class="dropdown-item" href="{{url('owner/GroceryShop/'.$shop->name)}}">{{ __('View') }}</a>
                                                            <a class="dropdown-item" href="{{url('owner/GroceryShop/'.$shop->id.'/edit')}}">{{ __('Edit') }}</a>
                                                            <a class="dropdown-item" onclick="deleteData('owner/GroceryShop','{{$shop->id}}');" href="#">{{ __('Delete') }}</a>
                                                            {{-- onclick="deleteData('Shop','{{$shop->id}}');" --}}
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <?php echo $shops->render(); ?>
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
