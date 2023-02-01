<?php

namespace App\Modules\Session\Services;

use App\Http\Resources\Session;
use App\Http\Resources\SessionCollection;
use App\Modules\Session\Repositories\Interfaces\SessionRepositoryInterface as SessionRepository;
use App\Support\Enums\Response;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class SessionService
{
    public function __construct(protected SessionRepository $sessionRepository)
    {
    }
    public function create(array $data): Session
    {
        try {
            $session = $this->sessionRepository->create($data);

            return new Session($session);
        } catch (Exception $e) {
            abort(500, Response::server_error());
        }
    }

    public function get(int $id): Session
    {
        try {
            $session = $this->sessionRepository->get($id);

            return new Session($session);
        } catch(ModelNotFoundException $e) {
            abort(404, Response::not_found());
        } catch (Exception $e) {
            abort(500, Response::server_error());
        }
    }

    public function update(int $id, array $data): Session
    {
        try {
            $session = $this->sessionRepository->update($id, $data);

            return new Session($session);
        } catch(ModelNotFoundException $e) {
            abort(404, Response::not_found());
        } catch (Exception $e) {
            abort(500, $e);
        }
    }

    public function delete(int $id)
    {
        try {
            return $this->sessionRepository->delete($id);
        } catch(ModelNotFoundException $e) {
            abort(404, Response::not_found());
        } catch (Exception $e) {
            abort(500, Response::server_error());
        }
    }

    public function all(array $data)
    {       

        try {
            $session = $this->sessionRepository->all($data);

            return new SessionCollection($session);
        } catch (Exception $e) {
            abort(500, Response::server_error());
        }
    }

    public function upcoming(array $data)
    {
        try {
            $session = $this->sessionRepository->upcoming($data);

            return new SessionCollection($session);
        } catch (Exception $e) {
            abort(500, Response::server_error());
        }
    }

    public function postpone(array $data, int $id)
    {
        try {
            $status = $this->sessionRepository->postpone($data,$id);

            return $status;
        } catch (Exception $e) {

            abort(500, Response::server_error());
        } 
    }

    public function recording(int $id, array $data)
    {
        try {
            $session = $this->sessionRepository->recording($id, $data);

            return $session;
        } catch(ModelNotFoundException $e) {
            abort(404, Response::not_found());
        } catch (Exception $e) {
            abort(500, Response::server_error());
        }
    }
    public function getSessionNotes(int $id)
    {
        try {
            $session = $this->sessionRepository->getSessionNotes($id);
            return $session;
        } catch(ModelNotFoundException $e) {
            abort(404, Response::not_found());
        } catch (Exception $e) {dd($e);
            abort(500, Response::server_error());
        }
    }
    public function renew(){
        try {
            $session = $this->sessionRepository->renew();
            return $session;
        } catch(ModelNotFoundException $e) {
            abort(404, Response::not_found());
        } catch (Exception $e) {  
            abort(500, Response::server_error());
        }
    }
}
