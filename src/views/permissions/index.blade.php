@extends('acl::acl-master')

@section('acl::content')
	
	<div class="container mt-5">
		
		<div class="row">
			
			<div class="col-lg-12">
				
				<h1>Permissions</h1>
				
				<div class="{{ $permissions->total() > 1 ? 'accordion' : '' }}" id="permissions-list">
					@foreach($permissions->items() as $permission)
						<div class="card">
							<div class="card-header p-0" id="{{ $permission->id }}-role">
								<h6 class="mb-0 p-3" data-toggle="collapse"
								    data-target="#{{ $permission->id }}-permission-body">
									{{ ucwords($permission->name) }}
									<small>({{ $permission->slug  }})</small>
								</h6>
							</div>
							
							<div id="{{ $permission->id }}-permission-body"
							     class="collapse {{ $permissions->total() > 1 ? '' : 'show' }}"
							     data-parent="#permissions-list">
								<div class="card-body">
									
									@if(!$permission->roles->isEmpty())
										<h6 class="">Roles attached to the Permissions</h6>
										<hr>
										<div class="row">
											@foreach($permission->roles->chunk(5) as $chunk)
												
												<div class="col-lg-3">
													<ul class="mb-0">
														@foreach($chunk as $role)
															<li>{{ $role->name }}</li>
														@endforeach
													</ul>
												</div>
											
											@endforeach
										
										</div>
									
									@else
										<p class="mb-0">No role is attached to this permission yet.</p>
									@endif
								
								</div>
								
								<div class="card-footer bg-white d-flex justify-content-between">
									<form action="{{ route(config('acl.route_as') . 'permissions.destroy', $permission->id) }}"
									      method="POST">
										@method('DELETE')
										{{ csrf_field() }}
										<button class="btn btn-sm btn-outline-danger" type="submit">Delete</button>
									</form>
								</div>
							
							</div>
						</div>
					@endforeach
				</div>
				
				{{ $permissions->links() }}
			
			
			</div>
		
		</div>
	
	</div>

@endsection