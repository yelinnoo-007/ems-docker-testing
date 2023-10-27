<?php

namespace App\Http\ValueObjects;

class Name
{
  public function __construct(
    private string|null $name,
  ) {
  }
  public static function from(string|null $name): static
  {
    return new static($name);
  }

  public function getFirstName(): string
  {
    return $this->name;
  }

  public function getMiddleName(): ?string
  {
    return $this->name;
  }

  public function getLastName(): string
  {
    return $this->name;
  }
}
