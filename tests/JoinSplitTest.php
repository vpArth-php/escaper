<?php

namespace Test\Util\String;

use Arth\Util\String\Escaper;
use Generator;
use PHPUnit\Framework\TestCase;

class JoinSplitTest extends TestCase
{
  public function testSplitLimit(): void
  {
    $svc = new Escaper();

    $input = 'a\.1.b\.2.c\.3.d\.4';
    $this->assertEquals([$input], $svc->split('.', $input, 1));
    $this->assertEquals(['a.1', 'b.2', 'c\.3.d\.4'], $svc->split('.', $input, 3));
  }

  /**
   * @dataProvider splitData
   * @param string[] $expected
   */
  public function testSplit(string $input, array $expected, int $limit = null): void
  {
    $svc = new Escaper('#');

    $this->assertEquals($expected, $svc->split('.', $input, $limit));
  }

  public function splitData(): ?Generator
  {
    yield ['', ['']];
    yield ['a', ['a']];
    yield ['#a', ['#a']];
    yield ['#a#.', ['#a.']];
    yield ['#a#', ['#a#']];
    yield ['a.', ['a', '']];
    yield ['a.', ['a.'], 1];
    yield ['a#.', ['a#.'], 1];
    yield ['a#.', ['a.'], 2];
    yield ['a.', ['a', ''], 2];
  }

  /**
   * @dataProvider joinSplitData
   * @param string[] $words
   */
  public function testJoinSplit(array $words, string $delimiter, string $escape, string $joinExpected): void
  {
    $svc = new Escaper($escape);

    $encoded = $svc->join($delimiter, $words);
    $this->assertEquals($joinExpected, $encoded);

    $decoded = $svc->split($delimiter, $encoded);
    $this->assertEquals($words, $decoded);
  }

  public function joinSplitData(): ?Generator
  {
    yield [
        [''],
        '.', '\\',
        'encoded' => '',
    ];
    yield [
        ['.', '\\', '\.', '\\a.\b'],
        '.', '\\',
        'encoded' => '\..\\\\.\\\\\..\\\a\.\\\\b', // '\\\\a' === '\\\a' in PHP
    ];
    yield [
        ['При::вет', 'Мир&#'],
        '::', '&#',
        'encoded' => 'При&#::вет::Мир&#&#',
    ];
  }
}
