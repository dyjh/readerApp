<?php

namespace App\Providers;

use App\Models\shares\PrivateBook;
use App\Models\shares\SharedBook;
use App\Models\shares\StudentsBooksRent;
use App\Policies\shares\CommentSharedBookPolicy;
use App\Policies\shares\PrivateBookPolicy;
use App\Policies\shares\RentBookStatePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
        StudentsBooksRent::class => RentBookStatePolicy::class,
        PrivateBook::class => PrivateBookPolicy::class,
        SharedBook::class => CommentSharedBookPolicy::class
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
