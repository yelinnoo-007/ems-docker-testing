<?php

namespace App\Contracts;

interface ValueObjectInterface
{
  public static function from(string|null $native): static;
  public function toNative(): mixed;
  public function isEqual(ValueObjectInterface $object): bool;
  public function isEmpty(): bool;
}
