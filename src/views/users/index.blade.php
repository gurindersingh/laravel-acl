@extends('acl::acl-master')

@section('acl::content')
	
	<div class="container mt-5">
		
		<div class="row mb-5">
			
			<div class="col-lg-12">
				
				<div class="row">
					<div class="col-lg-6 col-md-8 col-sm-12">
						<div class="">
							<form action="{{ route(config('acl.route_as') . 'users.index') }}" method="GET">
								{{ csrf_field() }}
								<div class="input-group mb-3">
									<input type="text" class="form-control" name="q" placeholder="Search user...">
									<div class="input-group-append">
										<button class="btn btn-outline-secondary" type="submit">Search</button>
									</div>
									<div class="input-group-append">
										<a href="{{ route(config('acl.route_as') . 'users.index') }}" class="btn btn-outline-secondary">Reset</a>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
				
				<table class="table">
					<thead>
					<tr>
						<th scope="col">#</th>
						<th scope="col">Name</th>
						<th scope="col">Actions</th>
					</tr>
					</thead>
					<tbody>
					@foreach($users->items() as $user)
						<tr>
							<th scope="row">{{ $user->id }}</th>
							<td>{{ $user->name }}</td>
							<td>
								<a href="{{ route(config('acl.route_as') . 'users.edit', $user->id) }}" class="btn btn-sm btn-secondary">Edit Acl</a>
							</td>
						</tr>
					@endforeach
					</tbody>
				</table>
				
				{{ $users->links() }}
			
			</div>
		
		</div>
	
	</div>

@endsection