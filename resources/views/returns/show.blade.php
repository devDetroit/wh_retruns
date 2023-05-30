@extends('bootstrap.layouts.layout')

@php
$canUpdate = Auth::user()->can('update-return');
$totalImages = 0;

@endphp

@section('content')

<div id="app">

    <div class="row justify-content-md-center mt-4">

        <div class="col-md-8">
            <div class="card shadow-sm p-2 bg-body rounded">
                <div class="card-body">
                    <h5 class="card-title text-center">Wharehouse Return Information </h5>
                    <div class="row m-2">
                        <div class="col-md-12 text-end">
                            <search-tracking></search-tracking>
                        </div>
                    </div>
                    <form enctype="multipart/form-data" method="post" action="/returns/{{$return->id}}">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="trakNumber" class="form-label">Tracking Number</label>
                            <input type="text" class="form-control" value="{{ $return->track_number }}" id="trakNumber" name="track_number" disabled>
                        </div>
                        <div class="mb-3">
                            <label for="orderNumber" class="form-label">Order Number</label>
                            <input type="text" class="form-control" value="{{ $return->order_number }}" id="orderNumber" name="order_number" {{ $canUpdate ? '' : 'disabled' }}>
                        </div>
                        <div class="mb-3">
                            <label for="stores" class="form-label">Store</label>
                            <select id="statusSelect" class="form-select" name="store" {{ $canUpdate ? '' : 'disabled' }} required>
                                @foreach ($stores as $store)
                                <option value="{{ $store->id }}" {{ $return->store_id == $store->id ? 'selected' : ''}}> {{ $store->name }} </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="trakNumber" class="form-label">Status</label>
                            <select id="statusSelect" class="form-select" name="returnstatus_id" {{ $canUpdate ? '' : 'disabled' }} required>
                                @foreach ($return_status as $status)
                                <option value="{{ $status->id }}" {{ $return->returnstatus_id == $status->id ? 'selected' : ''}}> {{ $status->description }} </option>
                                @endforeach
                            </select>
                        </div>

                        @if($canUpdate)
                        <div class="text-end">
                            <button type="submit" class="btn btn-sm btn-primary">Submit</button>
                        </div>
                        @endif
                    </form>
                </div>
            </div>

        </div>
    </div>


    <input type="hidden" id="bntHiddenModal" data-bs-toggle="modal" data-bs-target="#photosModal">
    <!-- Modal -->


    <div class="row justify-content-md-center mt-4">
        <div class="col-md-8">
            <div class="row row-cols-1 row-cols-md-3 g-4">

                @foreach($partnumbers as $partnumber)
                @php
                $totalImages = $partnumber->photos->count();
                @endphp
                <div class="col">
                    <div class="card h-100 shadow-sm p-1 mb-1 bg-body rounded">
                        <div class="card-header">
                            <strong> Part Number:</strong> {{ $partnumber->partnumber }}
                        </div>
                        @if(isset($partnumber->image))
                        <a href="/storage/PartNumbers/{{$partnumber->returns_id}}-{{$partnumber->image}}"> <img src="/storage/PartNumbers/{{$partnumber->returns_id}}-{{$partnumber->image}}" class="card-img-top" alt="{{$partnumber->returns_id}}-{{$partnumber->image}}"></a>
                        @php
                        $totalImages = 1;
                        @endphp
                        @else
                        @if($totalImages > 0)
                        @php
                        $totalImages = $totalImages;
                        @endphp
                        <a href="/storage/PartNumbers/{{$partnumber->photos[0]->image}}"> <img src="/storage/PartNumbers/{{$partnumber->photos[0]->image}}" class="card-img-top" alt="{{$partnumber->photos[0]->image}}"></a>
                        @else
                        <img src="/storage/PartNumbers/noimage.jpg" class="card-img-top">
                        @endif

                        @endif


                        <div class="card-body">
                            <div class="text-end">
                                <h6 class="card-subtitle mb-2 text-muted">Total images {{ $totalImages }} <i style="cursor: pointer;" class="fa-solid fa-eye" v-on:click='getPhotos({{ $partnumber->id}}, "{{$partnumber->partnumber}}")'></i></h6>
                            </div>
                            <h6 style="color:red; font-weight: bold;">Deposco PN</h6>
                            <ul>
                                <li><strong>{{ $partnumber->UPC == 0 ? 'Not Found' : $partnumber->item}}</strong></li>
                            </ul>
                            <h5 class=" card-title">Notes:</h5>
                            <p class="card-text">{{ $partnumber->note ?? 'No notes available' }}</p>
                        </div>
                        <div class="card-footer">
                            <strong> Status:</strong> {{ $partnumber->status->description }}
                        </div>
                    </div>
                </div>

                @endforeach
            </div>
        </div>
    </div>

    <div class="modal fade" id="photosModal" tabindex="-1" aria-labelledby="photosModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="photosModalLabel">Part Number: "<strong>@{{currentPartNumber}}</strong>" Photos</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            @verbatim
                            <div :class="index == 0 ? 'carousel-item active' : 'carousel-item'" v-for="(item, index) in photos" :key="index">
                                <img :src="'/storage/PartNumbers/'+item.image" class="d-block w-100" :alt="item.image">
                            </div>
                            @endverbatim
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection

@section('scripts')

<script src="/js/show.js"></script>

@endsection