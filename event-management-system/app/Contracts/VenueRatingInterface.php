<?php

namespace App\Contracts;

use App\Http\Requests\VenueReviewRequest;

interface VenueRatingInterface
{
    /* Contracts or Interface is some kind of restricting standard format */
    /* This is called magic signature */

    public function all();
    public function findByID(string $modelName, int $id);
    public function store(string $modleName, array $data);
    public function relationStore(object $parentModelInstance, VenueReviewRequest $request, array $data);
    public function update(string $modleName, array $data, int $id);
    public function delete(string $modleName, int $id);
}
