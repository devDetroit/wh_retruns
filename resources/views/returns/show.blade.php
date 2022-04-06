@extends('bootstrap.layouts.layout')

@section('content')

<div id="app">

    <div class="row justify-content-md-center mt-4">
        <div class="col-md-8">
            <div class="card shadow-sm p-2 bg-body rounded">
                <div class="card-body">
                    <h5 class="card-title text-center">Wharehouse Return Information</h5>
                    <form enctype="multipart/form-data" method="post">
                        <div class="mb-3">
                            <label for="trakNumber" class="form-label">Tracking Number</label>
                            <input type="text" class="form-control" value="{{ $return->track_number }}" id="trakNumber" name="track_number" v-model="track_number" disabled>
                        </div>
                        <div class="mb-3">
                            <label for="trakNumber" class="form-label">Status</label>
                            <select id="statusSelect" class="form-select" v-on:change="setStatusDescription" required v-model="partNumber.status_id">
                                @foreach ($statuses as $status)
                                <option value="{{ $status->id }}"> {{ $status->description }} </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="text-end">
                            <button type="button" class="btn btn-sm btn-secondary">New Return</button>
                            <button type="submit" class="btn btn-sm btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>

    <div class="row justify-content-md-center mt-4">
        <div class="col-md-8">
            <div class="row row-cols-1 row-cols-md-3 g-4">

                @foreach($return->partnumbers as $partnumber)

                <div class="col">
                    <div class="card h-100 shadow-sm p-1 mb-1 bg-body rounded">
                        <div class="card-header">
                            Part Number: {{ $partnumber->partnumber }}
                        </div>
                        <a href="/storage/PartNumbers/{{$partnumber->returns_id}}-{{$partnumber->image}}"> <img src="/storage/PartNumbers/{{$partnumber->returns_id}}-{{$partnumber->image}}" class="card-img-top" alt="{{$partnumber->image}}"></a>
                        <div class="card-body">
                            <h5 class="card-title">Notes:</h5><br>
                            <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
                        </div>
                        <div class="card-footer">
                            Status: <strong>{{ $partnumber->status->description }}</strong>
                        </div>
                    </div>
                </div>

                @endforeach

            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')

<script>
</script>


@endsection