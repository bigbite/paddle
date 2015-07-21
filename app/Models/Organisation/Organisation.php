<?php

namespace App\Models\Organisation;

use Illuminate\Database\Eloquent\Model;
use App\Models\Repository\Repository;
use App\Models\User\User;

class Organisation extends Model
{
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'organisations';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'name'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * Get the organisation's repositories.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function repositories()
    {
        return $this->hasMany(Repository::class, 'organisation_id', 'id')
            ->orderBy('name', 'asc');
    }

    /**
     * Get the organisation's members.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function members()
    {
        return $this->belongsToMany(User::class, 'organisation_members')
            ->orderBy('name', 'asc');
    }

    /**
     * Get the value of the model's route key.
     *
     * @return mixed
     */
    public function getRouteKey()
    {
        return strtolower($this->name);
    }
}
