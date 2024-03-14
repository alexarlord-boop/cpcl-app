<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OidcClient extends Model
{
    use HasFactory;

    protected $fillable = [
        'id', 'secret', 'name', 'description', 'auth_source', 'redirect_uri', 'scopes', 'is_enabled', 'is_confidential', 'owner', 'post_logout_redirect_uri', 'backchannel_logout_uri',
    ];

    // If the primary key is not 'id'
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $table = 'oidc_client';
    public $timestamps = false;
}
