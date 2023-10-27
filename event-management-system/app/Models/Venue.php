<?php

namespace App\Models;

use App\DB\Core\DateTimeField;
use App\DB\Core\StringField;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;

class Venue extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'address_id', 'platform_user_id', 'type_id', 'venue_title', 'unit_type',
        'capacity', 'avail_start_date', 'avail_end_date', 'avail_start_time', 'avail_end_time',
        'price', 'description'
    ];

    public function saveableFields(): array
    {
        return [
            'address_id' => StringField::new(),
            'platform_user_id' => StringField::new(),
            'type_id' => StringField::new(),
            'venue_title' => StringField::new(),
            'unit_type' => StringField::new(),
            'capacity' => StringField::new(),
            'price' => StringField::new(),
            'description' => StringField::new(),
            'avail_start_date' => DateTimeField::new(),
            'avail_end_date' => DateTimeField::new(),
            'avail_start_time' => DateTimeField::new(),
            'avail_end_time' => DateTimeField::new(),
        ];
    }

    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    public function platformUser()
    {
        return $this->belongsTo(PlatformUser::class);
    }

    public function type()
    {
        return $this->belongsTo(Type::class);
    }

    public function venueRatings()
    {
        return $this->hasMany(VenueRating::class);
    }

    public function booking()
    {
        return $this->hasMany(Booking::class);
    }

    public function venueComments()
    {
        return $this->hasMany(VenueComment::class);
    }

    public function venueImage()
    {
        return $this->hasMany(Image::class, 'link_id', 'id');
    }

    public function venueViewImage()
    {
        return $this->hasMany(venueImage::class);
    }


    public static function boot()
    {
        parent::boot();
    }

    public static function booted()
    {
        static::updated(fn (Venue $venue) => cache()->forget('venue:' . $venue->id));
        static::deleted(fn (Venue $venue) => cache()->forget('venue:' . $venue->id));
        static::created(fn (Venue $venue) => cache()->forget('venue:' . $venue->id));
    }

    public function forceDelete()
    {
        $book_status = $this->checkBookStatus($this->id);
        //dd($book_status);
        if ($book_status) {
            return 'Currently, you cannot perform this action!';
        }
        $this->forceDeleting = true;
        $venueImages = $this->venueImage()->where('link_id', $this->id)->where('genre', Config::get('variables.TWO'))->get();
        foreach ($venueImages as $venueImage) {
            $venueImage ? Storage::delete($venueImage->upload_url) : false;
            $venueImage ? $venueImage->forceDelete() : false;
        }

        $this->booking()->each(function ($book) {
            $eventImage = $book->event->eventImage()->where('genre', Config::get('variables.THREE'))->first();
            $eventImage ? Storage::delete($eventImage->upload_url) : false;
            $eventImage ? $eventImage->forceDelete() : false;
            $book->event->forceDelete();
        });
        parent::forceDelete();
    }

    public function checkBookStatus(int $venueId): bool
    {
        $venues = $this->with('booking')->find($venueId);
        foreach ($venues->booking as $book) {
            if ($book->book_status !== 1) {
                return true;
            }
        }
        return false;
    }

    // public function customCascadingVenue(int $id): bool //delete images when a venue deleted
    // {
    //     $bookings = $this->booking()->where('platform_user_id', $id)->get();
    //     foreach ($bookings as $booking) {
    //         $booking->event->delete();
    //     }

    //     $images = $this->venueImage()->where('link_id', $id)->where('genre', Config::get('variables.TWO'))->get();
    //     foreach ($images as $image) {
    //         Storage::delete($image->upload_url);
    //         $image->delete();
    //     }
    //     return true;
    // }

    public function scopeSearchingVenue(Builder $query, $filters)
    {
        $query->when($filters['date'] ?? false, function ($query, $date) {
            $query->where('avail_start_date',  $date);
        });

        $query->when($filters['township'] ?? false, function ($query, $township_id) {
            $query->whereHas('address.street.ward.township', function ($query) use ($township_id) {
                $query->where('id', $township_id);
            });
        });

        $query->when($filters['event_type'] ?? false, function ($query, $event_type) {
            $query->whereHas('type', function ($query) use ($event_type) {
                $query->where('id', $event_type);
            });
        });
    }
}
