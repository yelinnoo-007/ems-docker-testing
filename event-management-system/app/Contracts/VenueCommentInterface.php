<?php

namespace App\Contracts;

interface VenueCommentInterface
{
    public function all();
    public function findByID(string $modelName, int $id);
    public function store(string $modelName, array $data);
    public function relationStore(string $modelName, object $parentModelInstance, string $relationship, array $data);
    public function update(string $modelName, array $data, int $id);
    public function delete(string $modelName, int $id);
}
