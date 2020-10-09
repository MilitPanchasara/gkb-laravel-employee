@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            @if (count($errors) > 0)
                <div class = "alert alert-danger">
                    <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                    </ul>
                </div>
            @endif
            <div class="card">
                <div class="card-header">{{ __('Create Employee') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('employees.update', [$employee->id]) }}" enctype="multipart/form-data">
                        @csrf
                        {{method_field('PUT')}}
                        <div class="form-group row">
                            <label for="fname" class="col-md-4 col-form-label text-md-right">{{ __('First Name') }}</label>

                            <div class="col-md-6">
                                <input id="fname" type="text" class="form-control @error('fname') is-invalid @enderror" name="fname" value="{{ $employee->first_name }}" autocomplete="fname" autofocus>

                                @error('fname')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="lname" class="col-md-4 col-form-label text-md-right">{{ __('Last Name') }}</label>

                            <div class="col-md-6">
                                <input id="lname" type="text" class="form-control @error('lname') is-invalid @enderror" name="lname" value="{{$employee->last_name}}" autocomplete="lname">

                                @error('lmame')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $employee->email }}" autocomplete="email">

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="hobbies[]" class="col-md-4 col-form-label text-md-right">{{ __('Hobbies') }}</label>
                            <div class="col-md-6">
                                <label class=""><input type="checkbox" name="hobbies[]" value="TV" @if (in_array("TV",$hobbies))checked @endif /> {{ __('TV') }}</label><br>
                                <label class=""><input type="checkbox" name="hobbies[]" value="Reading" @if (in_array("Reading",$hobbies))checked @endif/> {{ __('Reading') }}</label><br>
                                <label class=""><input type="checkbox" name="hobbies[]" value="Coding" @if (in_array("Coding",$hobbies))checked @endif/> {{ __('Coding') }}</label><br>
                                <label class=""><input type="checkbox" name="hobbies[]" value="Skiing" @if (in_array("Skiing",$hobbies))checked @endif/> {{ __('Skiing   ') }}</label>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="gender" class="col-md-4 col-form-label text-md-right">{{ __('Gender') }}</label>

                            <div class="col-md-6">
                                @if ($employee->gender == "male")
                                <input type="radio" name="gender" value="male" id="male" checked><label for="male">Male</label><br>
                                <input type="radio" name="gender" value="female" id="female"><label for="female">Female</label><br>
                                @else
                                <input type="radio" name="gender" value="male" id="male"><label for="male">Male</label><br>
                                <input type="radio" name="gender" value="female" id="female" checked><label for="female">Female</label><br>    
                                @endif
                                
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="photo" class="col-md-4 col-form-label text-md-right">{{ __('Photo') }}</label>

                            <div class="col-md-6">
                                <input type="file" id="photo" name="photo" onchange="readURL(this);">
                                @if ($employee->profile_picture)
                                <br><br><img id="blah" src="/storage/profile_pictures/{{$employee->profile_picture}}" alt="your image" height="300px" width="300px"/>
                                @else
                                <br><br><img id="blah" src="" alt="your image" height="300px" width="300px" hidden/>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Save') }}
                                </button>
                            </div>
                        </div>
                    </form>
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
