<?php

namespace App\Models;

use App\Accounts\Contracts\AccountPostInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Post extends Model implements AccountPostInterface
{
    use HasFactory;

    /**
     * The model's default values for attributes.
     *
     * @var array<string, string>
     */
    protected $attributes = [
        'response' => '{}',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'destination',
        'content',
        'scheduled_for',
        'response',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'response' => 'array',
    ];

    /**
     * All of the relationships to be touched.
     *
     * @var array<string>
     */
    protected $touches = [
        'account'
    ];

    /**
     * @return BelongsTo
     */
    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    // public function media(): HasMany
    // {
    //     return $this->hasMany();
    // }

    public function getDestination(): ?string
    {
        return $this->destination;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @return array<string>
     */
    public function getMedia(): array
    {
        return [];
    }
}
