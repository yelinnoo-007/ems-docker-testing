<?php

namespace App\Http\Requests;

use App\Http\ValueObjects\Email;
use App\Http\ValueObjects\Name;
use App\Http\ValueObjects\Phone;

class EmsDto
{
  // public function __construct(
  //   public readonly string $fname,
  //   public readonly string $mname,
  // ) {
  //   dd($fname);
  // }
  public static function formRequest($request): array
  {
    return [
      'role' => $request->validated('role'),
      'first_name' => Name::from($request->validated('first_name'))->getFirstName(),
      'middle_name' => Name::from($request->validated('middle_name'))->getMiddleName(),
      'last_name' => Name::from($request->validated('last_name'))->getLastName(),
      'email' => Email::from($request->validated('email'))->toNative(),
      'password' => $request->validated('password'),
      'dob' => $request->validated('dob'),
      'gender' => $request->validated('gender'),
      'phone' => Phone::from($request->validated('phone'))->toNative(),
      'commercial_name' => $request->validated('commercial_name'),
      'upload_url' => $request->validated('upload_url'),
      'township_id' => $request->validated('township_id'),
      'ward_name' => $request->validated('ward_name'),
      'street_name' => $request->validated('street_name'),
      'block_no' => $request->validated('block_no'),
      'floor' => $request->validated('floor'),
      'add_type' => $request->validated('add_type'),
    ];
    // return new self(
    //   $request->input('first_name'),
    //   $request->input('middle_name')
    // );
  }
}
