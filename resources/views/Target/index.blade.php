@extends('bootstrap.layouts.layout')

@section('content')

<div class="row mt-4">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                Registro Metas Diarias
            </div>
            <div class="card-body">
                <form method="POST" action="/target/create">
                    @csrf
                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">Cantidad a producir</label>
                        <select class="form-select" name="station">
                            @foreach($lines as $line)
                            <option value="{{$line}}">{{$line}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="goal" class="form-label">Cantidad a producir</label>
                        <input type="numeric" class="form-control" id="goal" name="goal" required>
                    </div>
                    <div class="text-end">
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                Registro
            </div>
            <div class="card-body">
                <table class="table text-center">
                    <thead>
                        <tr>
                            <th scope="col">Fecha</th>
                            <th scope="col">Estacion</th>
                            <th scope="col">Meta</th>
                            <th scope="col">Impresas</th>
                            <th scope="col"></th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach($recordGoals as $goals)
                        <tr>
                            <td>{{$goals->production_day}}</td>
                            <td>{{$goals->station}}</td>
                            <td>{{$goals->goal}}</td>
                            <td>{{$goals->total_printed}}</td>
                            <td><button class="btn btn-sm btn-secondary">Modificar</button></td>
                        </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection


@section('scripts')
@if (session('success'))
<script>
    let timerInterval;
    let message = '<?= session('success') ?>';
    Swal.fire({
        icon: 'success',
        title: message,
        showConfirmButton: false,
        timer: 1500,
        willClose: () => {
            clearInterval(timerInterval)
        }
    });
</script>
@endif
@endsection