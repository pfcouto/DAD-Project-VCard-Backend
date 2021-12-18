<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Resources\AdministratorResource;
use App\Models\Administrator;
use App\Http\Requests\UpdateAdministratorRequest;
use App\Http\Requests\StoreAdministratorRequest;
use App\Http\Requests\UpdateAdministratorPasswordRequest;

class AdministratorController extends Controller
{
    public function index()
    {
        return AdministratorResource::collection(Administrator::all());
    }

    public function show(Administrator $administrator)
    {
        return new AdministratorResource($administrator);
    }

    public function update(UpdateAdministratorRequest $request, Administrator $administrator)
    {
        $administrator->update($request->validated());
        return new AdministratorResource($administrator);
    }

    public function store(StoreAdministratorRequest $request)
    {
        $newAdministrator = Administrator::create($request->validated());
        $newAdministrator->password = bcrypt($request->validated()['password']);
        $newAdministrator->save();
        return new AdministratorResource($newAdministrator);
    }

    public function update_password(UpdateAdministratorPasswordRequest $request, Administrator $administrator)
    {
        $administrator->password = bcrypt($request->validated()['password']);
        $administrator->save();
        return new AdministratorResource($administrator);
    }

    public function delete(Administrator $administrator)
    {
        $administrator->delete();
        return response("",200);
    }
}
