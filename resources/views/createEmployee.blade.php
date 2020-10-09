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
                    <form method="POST" action="{{ route('employees.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="form-group row">
                            <label for="fname" class="col-md-4 col-form-label text-md-right">{{ __('First Name') }}</label>

                            <div class="col-md-6">
                                <input id="fname" type="text" class="form-control @error('fname') is-invalid @enderror" name="fname" value="{{ old('fname') }}"  autocomplete="fname" autofocus>

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
                                <input id="lname" type="text" class="form-control @error('lname') is-invalid @enderror" name="lname" value="{{ old('lname') }}"  autocomplete="lname">

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
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}"  autocomplete="email">

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
                                <label class=""><input type="checkbox" name="hobbies[]" value="TV"/> {{ __('TV') }}</label><br>
                                <label class=""><input type="checkbox" name="hobbies[]" value="Reading"/> {{ __('Reading') }}</label><br>
                                <label class=""><input type="checkbox" name="hobbies[]" value="Coding"/> {{ __('Coding') }}</label><br>
                                <label class=""><input type="checkbox" name="hobbies[]" value="Skiing"/> {{ __('Skiing   ') }}</label>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="gender" class="col-md-4 col-form-label text-md-right">{{ __('Gender') }}</label>

                            <div class="col-md-6">

                                <input type="radio" name="gender" value="male" id="male" checked><label for="male">Male</label><br>
                                <input type="radio" name="gender" value="female" id="female"><label for="female">Female</label><br>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="photo" class="col-md-4 col-form-label text-md-right">{{ __('Photo') }}</label>

                            <div class="col-md-6">
                                <input type="file" id="photo" name="photo" onchange="readURL(this);">
                                <br><br><img id="blah" src="" alt="your image" height="300px" width="300px" hidden/>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Create') }}
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
