<?php

namespace App\Http\Controllers;

use App\Contracts\AddressInterface;
use App\Contracts\PlatformUserInterface;
use App\Mail\RegisterMailable;
use App\Contracts\StreetInterface;
use App\Contracts\WardInterface;
use App\Http\Requests\AuthRequest;
use App\Http\Requests\EmsDto;
use App\Http\Requests\PlatformUserRequest;
use App\Http\Resources\PlatformUserResource;
use App\Models\PlatformUser;
use App\Traits\HelperTrait;
use App\Traits\ImageTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class PlatformUserController extends Controller
{
    use ImageTrait, HelperTrait;
    private $genre, $individual, $corporate, $partner;
    public function __construct(
        private PlatformUserInterface $platformUserInterface,
        private WardInterface $wardInterface,
        private StreetInterface $streetInterface,
        private AddressInterface $addressInterface
    ) {
        $this->middleware('auth:sanctum')->except('store');
        $this->genre = Config::get('variables.ONE');
        $this->individual = Config::get('variables.ONE');
        $this->corporate = Config::get('variables.TWO');
        $this->partner = Config::get('variables.THREE');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $platform_users = $this->platformUserInterface->all();
        return PlatformUserResource::collection($platform_users);
    }

    public function store(AuthRequest $request)
    {
        $validatedData = $request->validated();
        if ($request->role === Config::get('variables.INDIVIDUAL')) {
            $validatedData['role'] = $this->individual;
        } else if ($request->role === Config::get('variables.CORPORATE')) {
            $validatedData['role'] = $this->corporate;
        } else {
            $validatedData['role'] = $this->partner;
        }

        $validatedData['password'] = Hash::make($request->password);

        $find_user = PlatformUser::where('email', $request->email)->first();
        if ($find_user) {
            return response()->json('Email is already exist');
        }
        $user = $this->platformUserInterface->store('PlatformUser', $validatedData);
        // Mail::to($user->email)->send(new RegisterMailable($user));
        $return_data = new PlatformUserResource($user);
        return response()->json([
            'data' => $return_data,
            'message' => 'Successfully Registered',
        ], 200);
    }

    // public function store(AuthRequest $request)
    // {
    //     $userFields = ['role', 'first_name', 'middle_name', 'last_name', 'email', 'password', 'dob', 'gender', 'phone'];
    //     $validatedData = EmsDto::formRequest($request);
    //     $data = $this->platformUserInterface->prepareData($validatedData);
    //     $userData = [];
    //     foreach ($userFields as $key) {
    //         if (array_key_exists($key, $data)) {
    //             $userData[$key] = $data[$key];
    //         }
    //     }
    //     if ($request->role === Config::get('variables.INDIVIDUAL')) {
    //         $validatedData['role'] = $this->individual;
    //     } else if ($request->role === Config::get('variables.CORPORATE')) {
    //         $validatedData['role'] = $this->corporate;
    //     } else {
    //         $validatedData['role'] = $this->partner;
    //     }
    

    //     if (!$userData['phone']) {
    //         return 'Your country is not availabel!';
    //     }
    //     return $userData;
    //     $user = $this->platformUserInterface->store('PlatformUser', $validatedData);
    //     //Mail::to($user->email)->send(new RegisterMailable($user));
    //     return new PlatformUserResource($user);
    // }

    public function show(PlatformUser $platform_user)
    {
        $platform_user->load('profileViewImage');
        return new PlatformUserResource($platform_user);
    }

    public function edit(PlatformUser $platform_user)
    {
        $platform_user->load('profileViewImage');
        return new PlatformUserResource($platform_user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PlatformUserRequest $request, PlatformUser $platformUser)
    {
        $validated_data = $request->validated();
        $platform_usersArr = [
            'first_name', 'middle_name', 'last_name',
            'gender', 'email', 'phone_no', 'commercial_name'
        ];

        $ward_fields = [];
        $ward_fields['township_id'] = $validated_data['township_id'];
        $ward_fields['ward_name'] = $validated_data['ward_name'];
        $ward = $this->wardInterface->store('Ward', $ward_fields);

        $street_fields = [];
        $street_fields['ward_id'] = $ward->id;
        $street_fields['street_name'] = $validated_data['street_name'];
        $street = $this->streetInterface->store('Street', $street_fields);

        $address_fields = [];
        $address_fields['street_id'] = $street->id;
        $address_fields['platform_user_id'] = auth()->user()->id;
        $address_fields['add_type'] = $validated_data['add_type'];
        $address_fields['block_no'] = $validated_data['block_no'];
        $address_fields['floor'] = $validated_data['floor'];
        $this->addressInterface->store('Address', $address_fields);

        $platform_user = $this->hasChanges($validated_data, $platformUser, $platform_usersArr) ?
            $this->updatePlatformUser($validated_data, $platformUser->id) : $platformUser;

        $request->hasFile('upload_url') ?
            $this->storeImage($request, auth()->user()->id, $this->genre, $this->platformUserInterface) : false;

        return new PlatformUserResource($platform_user);
    }

    public function destroy(PlatformUser $platform_user)
    {
        // $status = $platform_user->customCascadeUser($platform_user->id) ?
        //     $this->platformUserInterface->delete('PlatformUser', $platform_user->id) : false;
        return $this->platformUserInterface->delete('PlatformUser', $platform_user->id) ?
            response(status: 204) : response()->json([
                'message' => 'Currently, you cannot perform this action!'
            ]);
    }

    public function updatePlatformUser(array $validated_data, int $id): Model
    {
        $platform_user_fields = [];
        $platform_user_fields['role'] = auth()->user()->role;
        $platform_user_fields['first_name'] = $validated_data['first_name'];
        $platform_user_fields['middle_name'] = $validated_data['middle_name'] ?? null;
        $platform_user_fields['last_name'] = $validated_data['last_name'];
        $platform_user_fields['gender'] = $validated_data['gender'];
        $platform_user_fields['email'] = $validated_data['email'];
        $platform_user_fields['phone_no'] = $validated_data['phone_no'];
        $platform_user_fields['commercial_name'] = $validated_data['commercial_name'] ?? null;
        $platform_user_fields['password'] = Hash::make($validated_data['password']);

        return $this->platformUserInterface->update('PlatformUser', $platform_user_fields, $id);
    }
}
