# DiorgesBB

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Total Downloads][ico-downloads]][link-downloads]
[![Build Status][ico-travis]][link-travis]
[![StyleCI][ico-styleci]][link-styleci]

API para registro/verificação de boletos usando o projeto Piloto do BB, compátivel com Laravel 5 ao 8.

## Installation

Via Composer

``` bash
$ composer require diorgesl/diorgesbb
```

## Usage

Arquivo de Configuração
``` bash
php artisan vendor:publish --provider="Diorgesl\DiorgesBB\DiorgesBBServiceProvider"
```

Configurar o arquivo `config/diorgesbb.php` com os dados de acesso da API.

## Change log

Please see the [changelog](changelog.md) for more information on what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [contributing.md](contributing.md) for details and a todolist.

## Security

If you discover any security related issues, please email diorges@gis.net.br instead of using the issue tracker.

## Credits

- [Diorges Rocha][link-author]
- [All Contributors][link-contributors]

## License

MIT. Please see the [license file](license.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/diorgesl/diorgesbb.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/diorgesl/diorgesbb.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/diorgesl/diorgesbb/master.svg?style=flat-square
[ico-styleci]: https://styleci.io/repos/12345678/shield

[link-packagist]: https://packagist.org/packages/diorgesl/diorgesbb
[link-downloads]: https://packagist.org/packages/diorgesl/diorgesbb
[link-travis]: https://travis-ci.org/diorgesl/diorgesbb
[link-styleci]: https://styleci.io/repos/12345678
[link-author]: https://github.com/diorgesl
[link-contributors]: ../../contributors
