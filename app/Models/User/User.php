<?php

namespace App\Models\User;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use App\Models\Repository\Repository;
use App\Models\Organisation\Organisation;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract
{
    use Authenticatable, CanResetPassword;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['uid', 'token', 'email', 'nickname', 'name'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['token'];

    /**
     * Get the user's repositories.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function repositories()
    {
        return $this->hasMany(Repository::class, 'user_id', 'id')
            ->orderBy('name', 'asc');
    }

    /**
     * Get the user's organisations.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function organisations()
    {
        return $this->belongsToMany(Organisation::class, 'organisation_members')
            ->orderBy('name', 'asc');
    }
}
