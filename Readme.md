### String\Escaper

Handle string splitting with ability to escape delimiter

#### Installation

```sh
composer req arth/escaper
```

#### Usage
```php
  use Arth\Util\String\Escaper;
  
  $svc = new Escaper();
  $list = $svc->split(' ', 'Home Sweet\ Home'); // ['Home', 'Sweet Home']
```
