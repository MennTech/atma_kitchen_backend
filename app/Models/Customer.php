<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;


class Customer extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens,CanResetPassword;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    public $timestamps = false;
    protected $table = 'customers';
    protected $primaryKey = 'id_customer';
    protected $fillable = [
        'nama_customer',
        'email_customer',
        'password',
        'tanggal_lahir',
        'no_telp',
        'poin',
        'saldo',
    ];
    public function getEmailForPasswordReset(): string
    {
        return $this->email_customer;
    }
    public function routeNotificationFor($driver, $notification = null)
    {
        if(method_exists($this, 'routeNotificationFor'.ucfirst($driver))){
            return call_user_func([$this, 'routeNotificationFor'.ucfirst($driver)], $notification);
        }
        switch ($driver) {
            case 'database':
                return $this->notifications();
            case 'mail':
                return $this->email_customer;
        }
    }
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
}
