@extends('acl::acl-master')

@section('acl:content')
	
	<div class="container mt-5">
		
		<div class="row">
			
			<div class="col-lg-12">
				<div class="card">
					<div class="card-header">
						Edit Permission - {{ $permission->name }}
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
						
						<form action="{{ route(config('acl.route_as') . 'permissions.update', $permission->id) }}"
						      method="POST" class="">
							@method('DELETE')
							{{ csrf_field()  }}
							
							<div class="d-flex justify-content-between">
								<button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
								<a href="{{ route(config('acl.route_as') . 'permissions.index') }}"
								   class="btn btn-sm btn-outline-primary"
								>Cancel</a>
							</div>
							
							{{--@include('acl::permissions._crud', ['buttonText' => 'Update'])--}}
						
						</form>
					</div>
				</div>
			</div>
		
		
		</div>
	
	</div>

@endsection