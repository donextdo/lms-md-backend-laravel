<?php

namespace App\Modules\Subject\Repositories;

use App\Models\Subject;
use App\Modules\Subject\Repositories\Interfaces\SubjectRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Str;

class SubjectRepository implements SubjectRepositoryInterface
{
    public function __construct(protected Subject $subject)
    {
    }

    public function create(array $data): Subject
    {
        return $this->subject::create([
            'name' => $data['name'],
            'slug' => Str::slug($data['name']),
        ]);
    }

    public function all(array $data)
    {
        if (array_key_exists('paginate', $data) && $data['paginate']) {
            return $this->subject::orderBy('updated_at', 'DESC')->paginate($data['per-page'] ?? 10);
        }

        return $this->subject::all();
    }

    public function get(int $id): Subject
    {
        return $this->getSubjectById($id);
    }

    public function update(int $id, array $data): Subject
    {
        $subject = $this->getSubjectById($id);

        if ($subject) {
            return $this->updateSubject($id, $data);
        }
    }

    public function delete(int $id): bool
    {
        $subject = $this->getSubjectById($id);

        if ($subject) {
            return throw_unless($this->subject::where('id', $id)->delete(), ModelNotFoundException::class);
        }
    }

    private function getSubjectById(int $id): ?Subject
    {
        return $this->subject::findOrFail($id);
    }

    private function updateSubject(int $id, array $data): Subject
    {
        $subject = $this->subject::find($id);
        $subject->name = $data['name'];
        $subject->save();

        return $subject;
    }
}
