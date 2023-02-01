<?php

namespace App\Modules\User\Services;

use Illuminate\Support\Facades\Auth;
use App\Modules\User\Repositories\Interfaces\UserRepositoryInterface as UserRepository;

class UserService
{
    public function __construct(protected UserRepository $userRepository)
    {
    }

    public function resetPassword(array $data)
    {
      $this->userRepository->updatePassword(Auth::user()->id,$data);  
    }
    public function resetInfo(array $data)
    {
      $this->userRepository->updateInfo(Auth::user()->id,$data);  
    }
    public function view()
    {
      return $this->userRepository->get(Auth::user()->id);  
    } 

}
