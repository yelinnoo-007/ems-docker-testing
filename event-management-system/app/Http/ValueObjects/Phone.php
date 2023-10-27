<?php

namespace App\Http\ValueObjects;

use App\Contracts\ValueObjectInterface;

class Phone implements ValueObjectInterface
{

  public function __construct(
    protected string|null $phone
  ) {
    if (!str($phone)->startsWith('+95')) {
      //$phone = '+' . $phone;
      $phone = null;
    }
    $this->phone = $phone;
  }

  public static function from(string|null $phone = null): static
  {
    return new static($phone);
  }

  public function toNative(): ?string
  {
    return $this->phone;
  }

  public function isUsNumber(): bool
  {
    return str($this->phone)->startsWith('+1');
  }

  public function isEqual(ValueObjectInterface $phone): bool
  {
    return $this->toNative() == $phone->toNative();
  }

  public function isEmpty(): bool
  {
    return $this->toNative() == null;
  }
}
