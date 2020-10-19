<?php

namespace App\Models;

use App\Models\TemplateNotification;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Template extends Model
{
    use HasFactory;

    /**
     * Get the TemplateNotification associated with this Template
     */
    public function notifications()
    {
        return $this->hasMany(TemplateNotification::class);
    }
}
