@extends('acl::acl-master')

@section('acl::content')
	
	<div class="container mt-5">
		
		<div class="row">
			
			<div class="col-lg-12">
				<div class="card">
					<div class="card-header d-flex justify-content-between align-items-center">
						Edit Role - {{ $role->name }}
						<form action="{{ route(config('acl.route_as') . 'roles.destroy', $role->id) }}" method="POST">
							@method('DELETE')
							{{ csrf_field() }}
							<button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
						</form>
					</div>
					<div class="card-body">
						
						@if ($errors->any())
							<div class="alert alert-danger">
								<ul class="mb-0">
									@foreach ($errors->all() as $error)
										<li>{{ $error }}</li>
									@endforeach
								</ul>
							</div>
						@endif
						
						<form action="{{ route(config('acl.route_as') . 'roles.update', $role->id) }}" method="POST"
						      class="">
							@method('PUT')
							
							@include('acl::roles._crud', ['buttonText' => 'Update'])
						
						</form>
					</div>
				
				</div>
			</div>
		
		
		</div>
	
	</div>

@endsection