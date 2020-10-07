@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Import Employee data from CSV') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('employees.saveCSVData') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group row">
                            <label for="csv" class="col-md-4 col-form-label text-md-right">{{ __('Upload CSV file') }}</label>

                            <div class="col-md-6">
                                <input type="file" id="csv" name="csv" required>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Upload and Save') }}
                                </button>
                            </div>
                        </div>
                        <hr>
                        <h5>Column Format: first_name, last_name, email, gender, hobbies<br>(include these column names in first column)</h5>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
