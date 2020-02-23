<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Token extends Model
{
    //
    public const HEADER_KEY = "Authorization";
    protected $hidden = [
        'id', 'refresh_token_expiry', 'access_token_expiry', 'user_id', 'deleted', 'created_by', 'created_at', 'updated_at',
        'deleted_at', 'updated_by'
    ];

    /**
     * This binds a user to this Token
     * @return HasOne
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo('\App\User');
    }

}
