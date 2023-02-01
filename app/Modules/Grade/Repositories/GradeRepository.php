<?php

namespace App\Modules\Grade\Repositories;

use App\Models\Grade;
use App\Modules\Grade\Repositories\Interfaces\GradeRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Str;

class GradeRepository implements GradeRepositoryInterface
{
    public function __construct(protected Grade $grade)
    {
    }

    public function create(array $data): Grade
    {
        return $this->grade::create([
            'name' => $data['name'],
            'slug' => Str::slug($data['name']),
        ]);
    }

    public function all(array $data)
    {
        if (array_key_exists('paginate', $data) && $data['paginate']) {
            return $this->grade::orderBy('updated_at', 'DESC')->paginate($data['per-page'] ?? 10);
        }

        return $this->grade::all();
    }

    public function get(int $id): Grade
    {
        return $this->getGradeById($id);
    }

    public function update(int $id, array $data): Grade
    {
        $grade = $this->getGradeById($id);

        if ($grade) {
            return $this->updateGrade($id, $data);
        }
    }

    public function delete(int $id): bool
    {
        $grade = $this->getGradeById($id);

        if ($grade) {
            return throw_unless($this->grade::where('id', $id)->delete(), ModelNotFoundException::class);
        }
    }

    private function getGradeById(int $id): ?Grade
    {
        return $this->grade::findOrFail($id);
    }

    private function updateGrade(int $id, array $data): Grade
    {
        $grade = $this->grade::find($id);
        $grade->name = $data['name'];
        $grade->save();

        return $grade;
    }
}
