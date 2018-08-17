<?php
namespace Gurinder\LaravelAcl\Package\Models;


use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class Permission extends Model
{
    use Sluggable;

    protected $guarded = [];

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
