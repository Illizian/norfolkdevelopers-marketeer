<?php

namespace App\Models;

use App\Accounts\Contracts\AccountModelInterface;
use App\Accounts\Traits\ProvidesAccountModelAttributes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Account extends Model implements AccountModelInterface
{
    use HasFactory;
    use ProvidesAccountModelAttributes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'name',
        'type',
        'profile_name',
        'token',
        'secret',
        'refresh',
    ];

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }
}
