<?php

namespace App\Models;

use App\Http\Enums\NotificationType;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    const NOTIF_ALL = '*';
    const NOTIF_FROM = 'from';
    const NOTIF_TO = 'to';

    //Relationships
    /** @return \Illuminate\Database\Eloquent\Relations\BelongsTo */
    public function from() {
        return $this->belongsTo(User::class, Notification::NOTIF_FROM);
    }

    /** @return \Illuminate\Database\Eloquent\Relations\BelongsTo */
    public function to() {
        return $this->belongsTo(User::class, Notification::NOTIF_TO);
    }
}
