{{ csrf_field() }}

<div class="form-group">
	<label for="role_name">Role Name</label>
	<input type="text"
	       class="form-control"
	       id="role_name"
	       name="role_name"
	       value="{{ isset($role) && ! old('role_name')? $role->name : old('role_name') }}"
	       placeholder="Role name...">
	<small id="roleNameHelp" class="form-text text-muted">
		Role name must be unique
	</small>
</div>

<hr>
@if(!$permissions->isEmpty())
	<label class="label mb-0">Associate following permissions with the role</label>
	<div class="row">
		
		@php($rolePermissions = isset($role) ? $role->permissions->pluck('slug') : collect())
		
		@foreach($permissions as $letter => $permissionCollection)
			
			<div class="col-lg-3 mt-4">
				@foreach($permissionCollection as $permission)
					<div class="form-group form-check mb-2">
						<input type="checkbox" name="permissions[]" class="form-check-input"
						       id="{{ $permission->id }}-permission"
						       {{ $rolePermissions->contains($permission->slug) ? 'checked' : '' }}
						       value="{{ $permission->id }}">
						<label class="form-check-label"
						       for="{{ $permission->id }}-permission">{{ $permission->name }} </label>
					</div>
				@endforeach
			</div>
		
		@endforeach
	</div>
@else
	<p>No permissions added yet</p>
@endif

<hr>

<div class="input-group-append">
	<button class="btn btn-primary" type="submit">{{ $buttonText }}</button>
</div>