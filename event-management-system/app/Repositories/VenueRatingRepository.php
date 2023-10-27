<?php

namespace App\Repositories;

use App\Contracts\VenueRatingInterface;
use App\Db\Core\Crud;
use App\Http\Requests\VenueReviewRequest;
use App\Models\Venue;
use Illuminate\Support\Facades\Auth;

use function PHPUnit\Framework\isType;

class VenueRatingRepository implements VenueRatingInterface
{
    public function all()
    {
        return Venue::paginate(5);
    }

    public function findByID(string $modelName, int $id)
    {
        $model = app("App\\Models\\{$modelName}");
        return $model::find($id);
    }

    public function store(string $modelName, array $data)
    {
        $model = app("App\\Models\\{$modelName}");
        return (new Crud($model, $data, null, false, false))->execute();
    }

    public function relationStore(object $parentModelInstance, VenueReviewRequest $request, array $data)
    {
        $data['platform_user_id'] = Auth::user()->id;
        unset($data['rating_id']);
        $parentModelInstance->venueComments()->create($data);

        unset($data['user_comment']);
        $data['rating_id'] = $request->rating_id;
        return $parentModelInstance->venueRatings()->create($data);

        // $crud = new Crud($model, $data, null, false, false);
        // $crud->relationshipSaving($parentModelInstance, $relationship);
        // return $crud->execute();
    }

    public function update(string $modelName, array $data, int $id)
    {
        $model = app("App\\Models\\{$modelName}");
        return (new Crud($model, $data, $id, true, false))->execute();
    }

    public function delete(string $modelName, int $id)
    {
        $model = app("App\\Models\\{$modelName}");
        return (new Crud($model, null, $id, false, true))->execute();
    }
}
