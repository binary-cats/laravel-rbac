<?php

namespace BinaryCats\LaravelRbac\Tests\Fixtures;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;

class TeamUser extends Model
{
    use HasRoles;

    protected $guarded = [];

    protected $table = 'users';
}
