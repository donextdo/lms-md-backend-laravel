<?php

namespace App\Modules\User\Repositories;

use App\Models\User;
use App\Modules\User\Repositories\Interfaces\UserRepositoryInterface;
use Exception;
use Illuminate\Support\Facades\Hash;
use App\Support\Enums\Roles;

class UserRepository implements UserRepositoryInterface
{
    public function __construct(protected User $user)
    {
    }

    public function create(array $data): User
    {
        try {
            return $this->store($data);
        } catch (Exception $e) {dd($e);
            return abort(500);
        }
    }

    public function all()
    {
    }

    public function get(int $id): User
    {
        return $this->user->find($id);
    }

    public function updatePassword(int $id, array $data)
    {
      $user=$this->user->find($id);  
      $user->password=Hash::make($data['password']);
      $user->save();
    }
    public function updateInfo(int $id, array $data)
    {
      $user=$this->user->find($id);    
      $user->contact_no=$data['contact_no'];
      $user->email=$data['email'];
      $user->name=$data['name'];
      $user->save();
    }

    public function delete(int $id)
    {
    }

    protected function store(array $data): User
    {
        return $this->user::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'contact_no' => $data['contact_no'],
            'date_of_birth' => $data['date_of_birth'],
            'role_id' => Roles::student()->value,

        ]);
    }
}
