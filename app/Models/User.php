<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Notifications\VerifyEmailNotification;

// Email verification is disabled (see config/fortify.php). User no longer
// implements MustVerifyEmail, so no verification notification is sent on
// registration. The sendEmailVerificationNotification() override below is
// kept inert for the day verification is reintroduced with a proper flow.
class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;
    use HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'lastname', 'firstname', 'email', 'password', 'phone', 'role', 'genre', 'is_active', 'photo','timing', 'first_login_planning','first_login_dashboard'
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
        ];
    }

    public function learnerProfile(): HasOne
    {
        return $this->hasOne(LearnerProfile::class);
    }

    public function instructorProfile(): HasOne
    {
        return $this->hasOne(InstructorProfile::class);
    }

    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmailNotification());
    }

    public function bank(): HasOne
    {
        return $this->hasOne(BankAccount::class);
    }

    public function examensEnTantQueMoniteur()
    {
        return $this->hasMany(Examen::class, 'instructor_id');
    }
    public function examensEnTantQueEleve()
    {
        return $this->hasMany(Examen::class, 'learner_id');
    }
}
