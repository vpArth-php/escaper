<?php

namespace Arth\Util\String;

interface Splitter
{
  /** @return string[] */
  public function split(string $delimiter, string $input, int $limit = null): array;
  public function join(string $delimiter, array $input): string;
}
