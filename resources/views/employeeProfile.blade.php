@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Employee ID: {{$employee->id}}</div>

                <div class="card-body">
                    @if ($employee->profile_picture)
                    <br><br><img id="blah" src="/storage/profile_pictures/{{$employee->profile_picture}}" alt="your image" height="200px" width="200px"/><hr>
                    @else
                    <br><br><h2>No image uploaded</h2>
                    @endif
                    
                    <div class="row m-1">
                        <p>Name: {{ $employee->first_name }} {{ $employee->last_name }}</p>
                    </div>

                    <div class="row m-1">
                        <p>E-mail: {{ $employee->email }}</p>
                    </div>

                    <div class="row m-1">
                        <p>Hobbies:</p>
                        <ul>
                            @foreach ($hobbies as $hobby)
                                <li>{{$hobby->hobby}}</li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="row m-1">
                        <p>Gender: {{$employee->gender}}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                document.getElementById('blah').hidden = false;
                reader.onload = function (e) {
                    
                    $('#blah')
                        .attr('src', e.target.result);
                };

                reader.readAsDataURL(input.files[0]);
            }
        }
</script>
@endsection
