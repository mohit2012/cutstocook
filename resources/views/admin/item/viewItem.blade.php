@extends('admin.master', ['title' => __('Items')])

@section('content')
    @include('admin.layout.topHeader', [
        'title' => __('Shop Item') ,
        'class' => 'col-lg-7'
    ]) 
    <div class="container-fluid mt--7">
           
        <div class="row">
            <div class="col">
                    <div class="card form-card shadow">
                        <div class="card-header border-0">
                            <div class="row align-items-center">
                                <div class="col-8">
                                    <h3 class="mb-0">{{ __('Shop Item') }}</h3>
                                </div>
                                <div class="col-4 text-right">
                                    <a href="{{url('owner/Item/create')}}" class="btn btn-sm btn-primary">{{ __('Add New Item') }}</a>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            @if(count($items)>0) 
                                <table class="table align-items-center table-flush">
                                    <thead class="thead-light">
                                        <tr>
                                            <th scope="col">{{ __('#') }}</th>
                                            <th scope="col">{{ __('Image') }}</th>
                                            <th scope="col">{{ __('Name') }}</th>
                                            <th scope="col">{{ __('Category') }}</th>   
                                            <th scope="col">{{ __('Shop') }}</th>    
                                            <th scope="col">{{ __('Price') }}</th> 
                                            <th scope="col">{{ __('Item is') }}</th> 
                                            <th scope="col">{{ __('Status') }}</th>   
                                            <th scope="col">{{ __('Action') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($items as $item)
                                            @if($item->isVeg==1)
                                            <?php $veg = 'Veg'; ?>
                                            @elseif($item->isVeg==2)
                                            <?php $veg = 'Includes egg'; ?>
                                            @else
                                            <?php $veg = 'Non-veg'; ?>
                                            @endif
                                            @if($item->shop->user_id==Auth::user()->id)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td><img class=" avatar-lg round-5" src="{{url('images/upload/'.$item->image)}}"></td>
                                                <td>{{ $item->name }}</td>
                                                <td>{{ $item->category->name }}</td>
                                                <td>{{ $item->shop->name }}</td>
                                                <td>{{ $currency.$item->price }}</td>
                                                <td>{{$veg}}</td>
                                                <td>
                                                    <span class="badge badge-dot mr-4">
                                                        <i class="{{$item->status==0?'bg-success': 'bg-danger'}}"></i>
                                                        <span class="status">{{$item->status==0?'Active': 'Inactive'}}</span>
                                                    </span>
                                                </td>
                                                <td>                                                                              

                                                    <div class="dropdown">
                                                        <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            <i class="fas fa-ellipsis-v"></i>
                                                        </a>
                                                        <div class="dropdown-menu dropdown-men
                                                        u-right dropdown-menu-arrow">
                                                            <a class="dropdown-item" href="{{url('owner/Item/'.$item->id)}}">{{ __('View Item') }}</a>
                                                            <a class="dropdown-item" href="{{url('owner/Item/'.$item->id.'/edit')}}">{{ __('Edit') }}</a>
                                                            <a class="dropdown-item" onclick="deleteData('owner/Item','{{$item->id}}');" href="#">{{ __('Delete') }}</a>
                                                            {{-- onclick="deleteData('Item','{{$item->id}}');" --}}
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                                <?php echo $items->render(); ?>
                            @else                             
                                <div class="empty-state text-center pb-3">
                                    <img src="{{url('images/empty3.png')}}" style="width:35%;height:220px;">
                                    <h2 class="pt-3 mb-0" style="font-size:25px;">Nothing!!</h2>
                                    <p style="font-weight:600;">Your Collection list in empty....</p>
                                </div>                        
                            @endif
                            </div>
                    </div>
            </div>
        </div>
       
    </div>

@endsection