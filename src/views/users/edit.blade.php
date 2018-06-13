@extends('acl::acl-master')

@section('acl::content')
	
	<div class="container mt-5">
		
		<div class="row mb-5">
			
			<div class="col-lg-12">
				
				<form action="{{ route(config('acl.route_as') . 'users.update', $user->id) }}" method="POST">
					@method('PUT')
					{{ csrf_field() }}
					
					<div class="card">
						<div class="card-header">
							Edit ACL for - <strong>{{ $user->name }}</strong>
						</div>
						<div class="card-body">
							<div class="row">
								
								@foreach($roles->chunk(5) as $rolesGroup)
									
									<div class="col-lg-4">
										
										<ul class="list-unstyled mb-0">
											@foreach($rolesGroup as $role)
												<li class="mb-0">
													<div class="form-group form-check mb-1">
														<input type="checkbox"
														       class="form-check-input"
														       name="roles[]"
														       value="{{ $role->id }}"
														       {{ $user->roles->contains($role) ? 'checked' : '' }}
														       id="{{ $role->slug }}-role">
														<label class="form-check-label" for="{{ $role->slug }}-role">
															{{ $role->name }}
														</label>
													</div>
												</li>
											@endforeach
										</ul>
									
									</div>
								
								@endforeach
							
							</div>
						</div>
						<div class="card-footer">
							<button type="submit" class="btn btn-primary btn-sm">Update</button>
						</div>
					</div>
				</form>
			
			</div>
		
		</div>
	
	</div>

@endsection