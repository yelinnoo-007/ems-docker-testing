<?php

namespace App\Contracts;

use App\Http\Requests\AuthRequest;
use App\Http\Requests\TeamInviteDto;

interface PlatformUserInterface
{
  public function all();
  public function findByID(string $modelName, int $id);
  public function prepareData(array $validatedData): array;
  public function store(string $modelName, array $data);
  public function update(string $modelName, array $data, int $id);
  public function delete(string $modelName, int $id);
  public function findRole($tableRole, $searchRole);
  public function findActive($tableActive, $searchActive);
}
