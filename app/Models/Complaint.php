<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'collection_id',
        'ticket_number',
        'category',
        'subject',
        'description',
        'attachments',
        'priority',
        'status',
        'assigned_to',
        'resolution',
        'resolved_at',
    ];

    protected $casts = [
        'attachments' => 'array',
        'resolved_at' => 'datetime',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User:: class);
    }

    public function collection()
    {
        return $this->belongsTo(Collection::class);
    }

    public function assignee()
    {
        return $this->belongsTo(User:: class, 'assigned_to');
    }

    // Generate ticket number
    public static function generateTicketNumber(): string
    {
        $date = now()->format('Ymd');
        $count = self::whereDate('created_at', now())->count() + 1;
        
        return "TKT-{$date}-" . str_pad($count, 3, '0', STR_PAD_LEFT);
    }

    // Scopes
    public function scopeOpen($query)
    {
        return $query->whereIn('status', ['open', 'in_progress']);
    }

    // Get priority badge color
    public function getPriorityColorAttribute(): string
    {
        return match($this->priority) {
            'low' => 'gray',
            'medium' => 'yellow',
            'high' => 'orange',
            'urgent' => 'red',
            default => 'gray',
        };
    }
}