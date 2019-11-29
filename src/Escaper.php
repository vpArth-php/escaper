<?php

namespace Arth\Util\String;

class Escaper implements Splitter
{
  /** * @var string */
  protected $escape;

  public function __construct(string $escape = '\\')
  {
    $this->escape = $escape;
  }

  public function split(string $delimiter, string $input, int $limit = null): array
  {
    $tokens = $this->tokenize($input, [$delimiter]);

    $res  = [];
    $flag = false;
    $word = '';
    foreach ($tokens as $token) {
      if (null !== $limit && count($res) === $limit - 1) {
        $word .= $token;
        continue;
      }
      $this->processToken($token, $delimiter, $flag, $word, $res);
    }
    $res[] = $word;

    return $res;
  }
  public function join(string $delimiter, array $input): string
  {
    return implode($delimiter, array_map(function ($str) use ($delimiter) {
      return $this->encode($str, [$delimiter]);
    }, $input));
  }

  public function encode($word, array $list): string
  {
    $word = str_replace($this->escape, "{$this->escape}{$this->escape}", $word);
    foreach ($list as $sep) {
      $word = str_replace($sep, "{$this->escape}{$sep}", $word);
    }
    return $word;
  }

  protected function tokenize(string $input, array $list)
  {
    $parts[] = preg_quote($this->escape, '#');
    foreach ($list as $sep) {
      $parts[] = preg_quote($sep, '#');
    }
    $parts = implode('|', $parts);

    preg_match_all("#($parts|.+?)#u", $input, $m);

    return $m[0];
  }
  private function processToken(string $token, string $delimiter, bool &$flag, string &$word, array &$res): void
  {
    switch ($token) {
      case $this->escape:
        if ($flag) {
          $flag = false;
          $word .= $token;
        } else {
          $flag = true;
        }
        break;
      case $delimiter:
        if ($flag) {
          $flag = false;
          $word .= $token;
        } else {
          $res[] = $word;
          $word  = '';
        }
        break;
      default:
        if ($flag) {
          $flag = false;
          $word .= $this->escape;
        }
        $word .= $token;
    }
  }
}
