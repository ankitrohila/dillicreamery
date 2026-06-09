<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'avatar',
        'date_of_birth',
        'gender',
        'bio',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'date_of_birth' => 'date',
        ];
    }

    public function orders() { return $this->hasMany(Order::class); }
    public function subscriptions() { return $this->hasMany(Subscription::class); }
    public function addresses() { return $this->hasMany(Address::class); }
    public function reviews() { return $this->hasMany(ProductReview::class); }
    public function enrollments() { return $this->hasMany(CourseEnrollment::class); }
    public function wishlistProducts() { return $this->belongsToMany(Product::class, 'wishlists'); }
    public function consultancyBookings() { return $this->hasMany(ConsultancyBooking::class); }
    public function getDefaultAddressAttribute() { return $this->addresses()->where('is_default', true)->first(); }
}
