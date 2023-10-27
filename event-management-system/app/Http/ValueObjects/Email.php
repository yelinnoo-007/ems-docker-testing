<?php

namespace App\Http\ValueObjects;

use App\Contracts\ValueObjectInterface;

class Email implements ValueObjectInterface
{

  public function __construct(
    protected string|null $email
  ) {
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $email = null;
    }
    $this->email = $email;
  }

  public static function from(string|null $email = null): static
  {
    return new static($email);
  }

  public function toNative(): ?string
  {
    return $this->email;
  }

  public function domain(): string
  {
    return str($this->email)->afterLast('@')->toString();
  }

  public function isEqual(ValueObjectInterface $email): bool
  {
    return $this->toNative() == $email->toNative();
  }

  public function isEmpty(): bool
  {
    return $this->toNative() == null;
  }
}
