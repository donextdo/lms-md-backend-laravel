<?php

namespace App\Modules\Session_email\Repositories;

use App\Models\Session_email;
use App\Modules\Session_email\Repositories\Interfaces\Session_emailRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Str;

class Session_emailRepository implements Session_emailRepositoryInterface
{
    public function __construct(protected Session_email $session_email)
    {
    }

    public function create(array $data): Session_email
    {
        return $this->session_email::create([
            'session_id' => $data['session_id'],
            'title' => $data['title'],
            'body' => $data['body'],
            'type' => $data['type'],
            'date' => $data['date'],
            'time' => $data['time'],
            'status' => $data['status'],
        ]);
    }

    public function all(array $data)
    {
        if (array_key_exists('paginate', $data) && $data['paginate']) {
            return $this->session::orderBy('updated_at', 'DESC')->paginate($data['per-page'] ?? 10);
        }

        return $this->session_email::all();
    }

    public function get(int $id): Session_email
    {
        return $this->getSession_emailById($id);
    }

    public function update(int $id, array $data): Session_email
    {
        $session_email = $this->getSession_emailById($id);

        if ($session_email) {
            return $this->updateSession_email($id, $data);
        }
    }

    public function delete(int $id): bool
    {
        $session_email = $this->getSession_emailById($id);

        if ($session_email) {
            return throw_unless($this->session_email::where('id', $id)->delete(), ModelNotFoundException::class);
        }
    }

    private function getSession_emailById(int $id):Session_email
    {
        return $this->session_email::findOrFail($id);
    }

    private function updateSession_email(int $id, array $data): Session_email
    {
        $session_email = $this->session_email::find($id);
        $session_email->session_id = $data['session_id'];
        $session_email->title = $data['title'];
        $session_email->date = $data['body'];
        $session_email->time = $data['type'];
        $session_email->date = $data['date'];
        $session_email->time = $data['time'];
        $session_email->status = $data['status'];
        $session_email->save();

        return $session_email;
    }
}