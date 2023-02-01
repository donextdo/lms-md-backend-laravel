<?php

namespace App\Providers;

use App\Modules\Session_email\Repositories\Session_emailRepository;
use App\Modules\Session_email\Repositories\Interfaces\Session_emailRepositoryInterface;
use App\Modules\Session\Repositories\SessionRepository;
use App\Modules\Session\Repositories\Interfaces\SessionRepositoryInterface;
use App\Modules\Student\Repositories\StudentRepository;
use App\Modules\Student\Repositories\Interfaces\StudentRepositoryInterface;
use App\Modules\Class\Repositories\ClassRepository;
use App\Modules\Class\Repositories\Interfaces\ClassRepositoryInterface;
use App\Modules\Tutor\Repositories\TutorRepository;
use App\Modules\Tutor\Repositories\Interfaces\TutorRepositoryInterface;
use App\Modules\Country\Repositories\CountryRepository;
use App\Modules\Country\Repositories\Interfaces\CountryRepositoryInterface;
use App\Modules\Grade\Repositories\GradeRepository;
use App\Modules\Grade\Repositories\Interfaces\GradeRepositoryInterface;
use App\Modules\Subject\Repositories\SubjectRepository;
use App\Modules\Subject\Repositories\Interfaces\SubjectRepositoryInterface;
use App\Modules\User\Repositories\Interfaces\UserRepositoryInterface;
use App\Modules\User\Repositories\UserRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(SubjectRepositoryInterface::class, SubjectRepository::class);
        $this->app->bind(GradeRepositoryInterface::class, GradeRepository::class);
        $this->app->bind(CountryRepositoryInterface::class, CountryRepository::class);
        $this->app->bind(TutorRepositoryInterface::class, TutorRepository::class);
        $this->app->bind(ClassRepositoryInterface::class, ClassRepository::class);
        $this->app->bind(StudentRepositoryInterface::class, StudentRepository::class);
        $this->app->bind(SessionRepositoryInterface::class, SessionRepository::class);
        $this->app->bind(Session_emailRepositoryInterface::class, Session_emailRepository::class);

    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
