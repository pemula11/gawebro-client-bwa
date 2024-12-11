<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'occupation',
        'connect'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
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

    public function wallet()
    {
        return $this->hasOne(Wallet::class);
    }

    public function projects()
    {
        return $this->hasMany(Project::class, 'client_id', 'id')->orderByDesc('id');
        // $user->projects  == menampilkan project yang dimiliki oleh user
    }
    public function proposals()
    {
        return $this->hasMany(ProjectApplicant::class, 'freelancer_id', 'id');
    }

    public function hasAppliedToProject($projectId)
    {
        return ProjectApplicant::where('project_id', $projectId)
            ->where('freelancer_id', $this->id)
            ->exists();
    }
    
    public function transactionHistory()
    {
        return $this->hasMany(WalletTransaction::class)->orderByDesc('id');
    }

}
