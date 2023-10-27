<?php

namespace App\Models;

use App\DB\Core\StringField;
use App\DB\Core\DatetimeField;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Config;


class PlatformUser extends Authenticatable
{
    use HasFactory, HasApiTokens, Notifiable, SoftDeletes;

    protected $fillable = [
        'dept_id', 'commercial_name', 'first_name', 'middle_name',
        'last_name', 'dob', 'gender', 'phone_no', 'email', 'password', 'join_date',
        'resign_date',  'role', 'active', 'logged'
    ];

    public function saveableFields(): array|StringField|DatetimeField
    {
        return [
            'role' => StringField::new(),
            'first_name' => StringField::new(),
            'middle_name' => StringField::new(),
            'last_name' => StringField::new(),
            'gender' => StringField::new(),
            'active' => StringField::new(),
            'email' => StringField::new(),
            'phone_no' => StringField::new(),
            'commercial_name' => StringField::new(),
            'password' => StringField::new(),
            'dob' => DateTimeField::new(),
            'dept_id' => StringField::new(),
            'join_date' => DateTimeField::new(),
            'resign_date' => DateTimeField::new(),

        ];
    }

    public static function isAdmin()
    {
        $userid = auth()->user()->id;
        $userDetais = PlatformUser::find($userid);
        if ($userDetais['role'] ===   Config::get('variables.TWENTY_THREE')) {
            return true;
        }
        return false;
    }

    public static function isPartner()
    {
        $userid = auth()->user()->id;
        $userDetais = PlatformUser::find($userid);
        if ($userDetais['role'] ===   Config::get('variables.THREE')) {
            return true;
        }
        return false;
    }

    public static function isNormalUser()
    {
        $userid = auth()->user()->id;
        $userDetais = PlatformUser::find($userid);
        if ($userDetais['role'] ===   Config::get('variables.ONE') || $userDetais['role'] ===   Config::get('variables.TWO')) {
            return true;
        }
        return false;
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function address()
    {
        return $this->hasMany(Address::class);
    }

    public function venue()
    {
        return $this->hasMany(Venue::class);
    }

    public function venueRatings()
    {
        return $this->hasMany(VenueRating::class);
    }

    public function venueComments()
    {
        return $this->hasMany(VenueComment::class);
    }

    public function booking()
    {
        return $this->hasMany(Booking::class);
    }

    public function profileViewImage() //view
    {
        return $this->hasOne(ProfileImage::class);
    }

    public function profileImage()
    {
        return $this->hasOne(Image::class, 'link_id', 'id');
    }

    public static function boot()
    {
        parent::boot();
    }

    public function checkBookStatus(int $platformUserID): bool
    {
        $platformUser = $this->with('venue.booking')->find($platformUserID);
        foreach ($platformUser->venue as $venue) {
            foreach ($venue->booking as $book) {
                if ($book->book_status !== Config::get('variables.ONE')) {
                    return true;
                }
            }
        }
        return false;
    }

    // public function forceDelete()
    // {
    //     $book_status = $this->checkBookStatus($this->id);
    //     if ($book_status) {
    //         return 'Currently, you cannot perform this action!';
    //     }
    //     $this->forceDeleting = true;
    //     $profileImage = $this->profileImage()->where('link_id', $this->id)->where('genre', Config::get('variables.ONE'))->first();
    //     $profileImage ? Storage::delete($profileImage->upload_url) : false;
    //     $profileImage ? $profileImage->forceDelete() : false;

    //     $addresses = $this->address()->where('platform_user_id', $this->id)->get();
    //     $addresses->each(function ($address) {
    //         $address->street->ward()->forceDelete();
    //         $address->street()->forceDelete();
    //     });

    //     //for partner
    //     $venues = $this->venue()->where('platform_user_id', $this->id)->get();
    //     foreach ($venues as $venue) {
    //         $venueImages = $venue->venueImage()->where('link_id', $venue->id)->where('genre', Config::get('variables.TWO'))->get();
    //         foreach ($venueImages as $venueImage) {
    //             $venueImage ? Storage::delete($venueImage->upload_url) : false;
    //             $venueImage ? $venueImage->forceDelete() : false;
    //         }
    //         $venue->forceDelete();
    //     }

    //     //for normal user & corporate
    //     $bookings = $this->booking()->where('platform_user_id', $this->id)->get();
    //     $bookings->each(function ($book) { //deleting event images
    //         $eventImage = $book->event->eventImage()->where('link_id', $book->event->id)->where('genre', Config::get('variables.THREE'))->first();
    //         $eventImage ? Storage::delete($eventImage->upload_url) : false;
    //         $eventImage ? $eventImage->forceDelete() : false;
    //         $book->event()->forceDelete();
    //     });

    //     parent::forceDelete();
    // }

    // public function customCascadeUser(int $id): bool //delete image&address when a user deleted
    // {
    //     $bookings = $this->booking()->where('platform_user_id', $id)->get();
    //     foreach ($bookings as $booking) {
    //         $booking->event()->delete();
    //     }

    //     $addresses = $this->address()->where('platform_user_id', $id)->get();
    //     foreach ($addresses as $address) {
    //         $address->street->ward()->delete();
    //     }

    //     $image = $this->userImage()->where('link_id', $id)->where('genre', Config::get('variables.ONE'))->first();
    //     Storage::delete($image->upload_url);
    //     return $image->delete();
    // }
}
