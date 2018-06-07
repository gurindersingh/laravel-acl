<?php
namespace Gurinder\LaravelAcl\Package\Models;


use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $guarded = [];

    protected $slugSource = 'name';

    /**
     * @var string
     */
    protected $table = 'permissions';

    protected $hidden = [
        'pivot'
    ];

    /**
     * @var bool
     */
    public $timestamps = FALSE;

    protected $appends = ['editable'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    /**
     * @param int|array $role
     *
     * @return array
     */
    public function attachToRoles($role)
    {
        return $this->roles()->sync($role);
    }

    public function getEditableAttribute()
    {
        return !in_array($this->slug, $this->getFreezedPermissionsSlug());
    }

    public function getFreezedPermissionsSlug()
    {
        return ['manage-acl'];
    }
}
