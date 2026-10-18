<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactUs extends Model
{
    /**
     * Eloquent would pluralise this to `contact_uses`, which is not the table name.
     */
    protected $table = 'contact_us';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'message',
    ];
}
