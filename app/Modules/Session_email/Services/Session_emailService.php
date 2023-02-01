<?php

namespace App\Modules\Session_email\Services;

use App\Http\Resources\Session_email;
use App\Http\Resources\Session_emailCollection;
use App\Modules\Session_email\Repositories\Interfaces\Session_emailRepositoryInterface as Session_emailRepository;
use App\Support\Enums\Response;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class Session_emailService
{
    public function __construct(protected Session_emailRepository $session_emailRepository)
    {
    }
    public function create(array $data): Session_email
    {
        try {
            $session_email = $this->session_emailRepository->create($data);

            return new Session_email($session_email);
        } catch (Exception $e) {dd($e);
            abort(500, Response::server_error());
        }
    }

    public function get(int $id): Session_email
    {
        try {
            $session_email = $this->session_emailRepository->get($id);

            return new Session_email($session_email);
        } catch(ModelNotFoundException $e) {
            abort(404, Response::not_found());
        } catch (Exception $e) {
            abort(500, Response::server_error());
        }
    }

    public function update(int $id, array $data): Session_email
    {
        try {
            $session_email = $this->session_emailRepository->update($id, $data);

            return new Session_email($session_email);
        } catch(ModelNotFoundException $e) {
            abort(404, Response::not_found());
        } catch (Exception $e) {
            abort(500, Response::server_error());
        }
    }

    public function delete(int $id)
    {
        try {
            return $this->session_emailRepository->delete($id);
        } catch(ModelNotFoundException $e) {
            abort(404, Response::not_found());
        } catch (Exception $e) {
            abort(500, Response::server_error());
        }
    }

    public function all(array $data)
    {
        try {
            $session_email = $this->session_emailRepository->all($data);

            return new Session_emailCollection($session_email);
        } catch (Exception $e) {
            abort(500, Response::server_error());
        }
    }
}
