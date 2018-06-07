{{ csrf_field() }}
<div class="input-group">
	<input type="text" name="permission_name" class="form-control" id="role_name" placeholder="Permission name...">
	<div class="input-group-append">
		<button class="btn btn-primary" type="submit">{{ $buttonText }}</button>
	</div>
</div>
<small><i>Permission will not be editable. You can only delete this permission</i></small>