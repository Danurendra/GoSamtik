<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RouteStop extends Model
{
    use HasFactory;

    protected $fillable = [
        'route_id',
        'collection_id',
        'sequence_order',
        'status',
        'arrived_at',
        'completed_at',
        'notes',
    ];

    protected $casts = [
        'arrived_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function route(): BelongsTo
    {
        return $this->belongsTo(Route::class);
    }

    public function collection(): BelongsTo
    {
        return $this->belongsTo(Collection::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Methods
    |--------------------------------------------------------------------------
    */

    public function markAsArrived(): void
    {
        $this->update([
            'status' => 'arrived',
            'arrived_at' => now(),
        ]);

        $this->collection->update(['status' => 'in_progress']);
    }

    public function markAsCompleted(? string $notes = null): void
    {
        $this->update([
            'status' => 'completed',
            'completed_at' => now(),
            'notes' => $notes,
        ]);

        $this->collection->update([
            'status' => 'completed',
            'completed_at' => now(),
            'driver_notes' => $notes,
        ]);

        // Update route progress
        $this->route->updateCompletedStops();
    }
}