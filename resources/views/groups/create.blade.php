@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Create Group') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                	<form action="{{ route('groups.store') }}" method="POST">
                		{{ csrf_field() }}
                		<input type="text" name="title" value="{{ old('title') }}">
                		<input type="submit" value="Add group">
                	</form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
