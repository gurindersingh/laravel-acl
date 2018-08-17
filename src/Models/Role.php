<?php

namespace Gurinder\LaravelAcl\Package\Models;


use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class Role extends Model
{
    use Sluggable;

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @var string
     */
    protected $table = 'roles';

    /**
     * @var bool
     */
    public $timestamps = FALSE;

    protected $hidden = [
        'pivot'
    ];

    protected $appends = ['editable'];

    /**
     * The users that belong to the role.
     */
    public function users()
    {
        return $this->belongsToMany(config('acl.user_model'));
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }

    public function attachPermission($permission)
    {
        if ($permission instanceof Permission) {
            $this->permissions()->attach($permission->id);
        }

        if (is_numeric($permission)) {
            $this->permissions()->attach(Permission::whereId($permission)->firstOrFail()->id);
        }

        if (is_string($permission)) {
            $this->permissions()->attach(Permission::whereSlug($permission)->firstOrFail()->id);
        }

    }

    public function attachPermissions($permissions = [])
    {
        if (is_array($permissions)) {
            foreach ($permissions as $permission) {
                $this->attachPermission($permission);
            }
        }
    }

    public function syncPermissions($permissionsIds = [])
    {
        $this->permissions()->detach();

        return $this->permissions()->sync($permissionsIds);
    }

    public function getEditableAttribute()
    {
        return !in_array($this->slug, $this->getFreezedRolesSlug());
    }

    public function getFreezedRolesSlug()
    {
        return ['admin'];
    }

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => ['name']
            ]
        ];
    }
}
