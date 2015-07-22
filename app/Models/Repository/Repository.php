<?php

namespace App\Models\Repository;

use Crypt;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Models\User\User;
use App\Models\Organisation\Organisation;

class Repository extends Model
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
    protected $table = 'repositories';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'hook_id', 'email', 'name', 'svn', 'username', 'password', 'processing', 'pushed_at'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * Gets the hooked attribute.
     *
     * @return bool
     */
    public function getHookedAttribute()
    {
        return $this->hook_id !== null;
    }

    /**
     * Gets the rigged attribute.
     *
     * @return bool
     */
    public function getRiggedAttribute()
    {
        $password = array_get($this->attributes, 'password');

        return ($this->svn !== null && trim($this->svn) !== '')
                && ($this->use_ssh || (
                    ($this->username !== null && trim($this->username) !== '')
                    && ($password !== null && trim($password) !== '')
                ));
    }

    /**
     * Get if the repository is using SSH.
     *
     * @return bool
     */
    public function getUseSshAttribute()
    {
        $password = array_get($this->attributes, 'password');

        return env('SSH_KEY_PATH', false) !== false
            && ($password === null || trim($password) === '');
    }

    /**
     * Gets the display name attribute.
     *
     * @return string
     */
    public function getDisplayNameAttribute()
    {
        return $this->owner->name.'/'.$this->name;
    }

    /**
     * Gets the password attribute.
     *
     * @return string
     */
    public function getPasswordAttribute()
    {
        try {
            return Crypt::decrypt($this->attributes['password']);
        } catch (Exception $e) {
        }

        return '';
    }

    /**
     * Sets the password attribute.
     *
     * @param string $password
     *
     * @return string
     */
    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = Crypt::encrypt($password);
    }

    /**
     * Get a token for the repository.
     *
     * @return string
     */
    public function getTokenAttribute()
    {
        $owner = $this->owner;

        if ($owner instanceof User) {
            return $owner->token;
        }

        $user = $owner->members()
            ->orderBy('updated_at', 'desc')
            ->whereNotNull('token')
            ->first();

        return $user === null ? null : $user->token;
    }

    /**
     * Get the value of the model's route key.
     *
     * @return mixed
     */
    public function getRouteKey()
    {
        return strtolower($this->owner->name.'/'.$this->name);
    }

    /**
     * Scopes to non-hooked repositories.
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeNotHooked(Builder $builder)
    {
        return $builder->whereNull('hook_id');
    }

    /**
     * Get the package's owner.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function owner()
    {
        return $this->user_id ? $this->belongsTo(User::class, 'user_id')
            : $this->belongsTo(Organisation::class, 'organisation_id');
    }
}
