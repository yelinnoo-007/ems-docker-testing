<?php

namespace App\Repositories;

use App\Models\User;
use App\Db\Core\Crud;
use App\Models\Role;
use App\Contracts\RoleInterface;

class RoleRepository implements RoleInterface
{
    public function all()
    {
        return Role::paginate(10);
    }

    public function findByID(string $modelName, int $id)
    {
        $model = app("App\Models\\{$modelName}");
        return $model::find($id);
    }

    public function store(string $modelName, array $data)
    {
        return (new Crud(new Role(), $data, null, false, false))->execute();
    }

    public function update($modelName, array $data, int $id)
    {
        return (new Crud(new Role(), $data, $id, true, false))->execute();
    }

    public function delete(string $modelName, int $id)
    {
        return (new Crud(new Role(), null, $id, false, true))->execute();
    }

    // public function delete(int $id)
    // {
    //     return (new Crud(
    //         new User(),
    //         null,
    //         $id,
    //         false,
    //         false,
    //         false,
    //         true
    //     ))->execute();
    // }
}
