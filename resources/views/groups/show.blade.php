@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ $group->title }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class="row">
                    	<div class="col-md-6">
                    		<div class="card">
                    			<div class="card-header">{{ __('Task Lists') }}</div>

                    			<div class="card-body">
                    				<ul>
	                    				@foreach ($group->task_lists as $list)
		                    				<li><a href="{{ route('task_lists.show', ['task_list' => $list]) }}">{{ $list->title }}</a></li>
	                    				@endforeach
	                    			</ul>
	                    			<a href="{{ route('task_lists.create') }}">{{ __('add task list') }}</a>
                    			</div>
                    		</div>
                    	</div>
                    	<div class="col-md-6">
                    		<div class="card">
                    			<div class="card-header">{{ __('Group members') }}</div>

                    			<div class="card-body">
									<ul>
										@foreach ($group->users as $user)
											<li>{{ $user->name }}</li>
										@endforeach
									</ul>
									@can ('update', $group)
										<form action="{{ route('groups.users.store', compact('group')) }}" method="POST">
											{{ csrf_field() }}
											{{-- TODO: replace this with a simple input field for email, to be sent w/ json to invite a user --}}
											<select name="user_id">
												@foreach ($users as $user)
													@if (!$group->users->contains('id', $user->id))
														<option value="{{ $user->id }}">{{ $user->name }}</option>
													@endif
												@endforeach
											</select>
											<input type="submit" value="{{ __('add user') }}">
										</form>
									@endcan
								</div>
							</div>
						</div>
					</div>
					@can ('update', $group)
						<a href="{{ route('groups.edit', $group) }}">Edit group</a>
					@endcan
					@can ('delete', $group)
						<form action="{{ route('groups.destroy', $group) }}" method="POST">
							{{ csrf_field() }}
							{{ method_field('DELETE') }}
							<input type="submit" value="Delete group">
						</form>
					@endcan
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
