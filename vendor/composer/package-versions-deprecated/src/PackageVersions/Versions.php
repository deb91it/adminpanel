<?php

declare(strict_types=1);

namespace PackageVersions;

use Composer\InstalledVersions;
use OutOfBoundsException;

class_exists(InstalledVersions::class);

/**
 * This class is generated by composer/package-versions-deprecated, specifically by
 * @see \PackageVersions\Installer
 *
 * This file is overwritten at every run of `composer install` or `composer update`.
 *
 * @deprecated in favor of the Composer\InstalledVersions class provided by Composer 2. Require composer-runtime-api:^2 to ensure it is present.
 */
final class Versions
{
    /**
     * @deprecated please use {@see self::rootPackageName()} instead.
     *             This constant will be removed in version 2.0.0.
     */
    const ROOT_PACKAGE_NAME = 'laravel/laravel';

    /**
     * Array of all available composer packages.
     * Dont read this array from your calling code, but use the \PackageVersions\Versions::getVersion() method instead.
     *
     * @var array<string, string>
     * @internal
     */
    const VERSIONS          = array (
  'barryvdh/laravel-dompdf' => 'v0.6.1@b606788108833f7765801dca35455fb23ce9f869',
  'brozot/laravel-fcm' => '1.3.1@1b5c64c5ea829f46c7e5f274971c587a8c3f0668',
  'classpreloader/classpreloader' => '3.2.1@297db07cabece3946f4a98d23f11f90aa10e1797',
  'composer/package-versions-deprecated' => '1.11.99.1@7413f0b55a051e89485c5cb9f765fe24bb02a7b6',
  'cornford/googlmapper' => 'v2.37.0@8465e8d2e61c57dcec1dceb00c22e0bc88405b41',
  'dnoegel/php-xdg-base-dir' => '0.1@265b8593498b997dc2d31e75b89f053b5cc9621a',
  'doctrine/annotations' => '1.12.1@b17c5014ef81d212ac539f07a1001832df1b6d3b',
  'doctrine/cache' => '1.10.2@13e3381b25847283a91948d04640543941309727',
  'doctrine/dbal' => '2.10.4@47433196b6390d14409a33885ee42b6208160643',
  'doctrine/event-manager' => '1.1.1@41370af6a30faa9dc0368c4a6814d596e81aba7f',
  'doctrine/inflector' => '1.4.3@4650c8b30c753a76bf44fb2ed00117d6f367490c',
  'doctrine/lexer' => '1.2.1@e864bbf5904cb8f5bb334f99209b48018522f042',
  'dompdf/dompdf' => 'v0.6.2@cc06008f75262510ee135b8cbb14e333a309f651',
  'guzzlehttp/guzzle' => '6.5.5@9d4290de1cfd701f38099ef7e183b64b4b7b0c5e',
  'guzzlehttp/promises' => '1.4.1@8e7d04f1f6450fef59366c399cfad4b9383aa30d',
  'guzzlehttp/psr7' => '1.7.0@53330f47520498c0ae1f61f7e2c90f55690c06a3',
  'intervention/image' => '2.5.1@abbf18d5ab8367f96b3205ca3c89fb2fa598c69e',
  'jakub-onderka/php-console-color' => 'v0.2@d5deaecff52a0d61ccb613bb3804088da0307191',
  'jakub-onderka/php-console-highlighter' => 'v0.3.2@7daa75df45242c8d5b75a22c00a201e7954e4fb5',
  'jean85/pretty-package-versions' => '1.6.0@1e0104b46f045868f11942aea058cd7186d6c303',
  'jenssegers/mongodb' => 'v3.2.3@c0cae3e187999ea80f92cc5ab169de4baa784f2e',
  'jeremeamia/superclosure' => '2.4.0@5707d5821b30b9a07acfb4d76949784aaa0e9ce9',
  'jlapp/swaggervel' => 'dev-master@e026d72cacec8b2db8b2510179d73042f5e87bb9',
  'kylekatarnls/update-helper' => '1.2.1@429be50660ed8a196e0798e5939760f168ec8ce9',
  'laravel/framework' => 'v5.2.45@2a79f920d5584ec6df7cf996d922a742d11095d1',
  'laravelcollective/html' => 'v5.2.6@4f6701c7c3f6ff2aee1f4ed205ed6820e1e3048e',
  'league/flysystem' => '1.1.3@9be3b16c877d477357c015cec057548cf9b2a14a',
  'league/fractal' => '0.19.2@06dc15f6ba38f2dde2f919d3095d13b571190a7c',
  'league/mime-type-detection' => '1.7.0@3b9dff8aaf7323590c1d2e443db701eb1f9aa0d3',
  'maatwebsite/excel' => '2.1.30@f5540c4ba3ac50cebd98b09ca42e61f926ef299f',
  'milon/barcode' => '5.3.6@ca2f3efbf46142ff7f7afe3b0f3660ea4a067576',
  'mongodb/mongodb' => '1.8.0@953dbc19443aa9314c44b7217a16873347e6840d',
  'monolog/monolog' => '1.26.0@2209ddd84e7ef1256b7af205d0717fb62cfc9c33',
  'mtdowling/cron-expression' => 'v1.2.3@9be552eebcc1ceec9776378f7dcc085246cacca6',
  'nesbot/carbon' => '1.39.1@4be0c005164249208ce1b5ca633cd57bdd42ff33',
  'nikic/php-parser' => 'v2.1.1@4dd659edadffdc2143e4753df655d866dbfeedf0',
  'paragonie/random_compat' => 'v1.4.3@9b3899e3c3ddde89016f576edb8c489708ad64cd',
  'phenx/php-font-lib' => '0.2.2@c30c7fc00a6b0d863e9bb4c5d5dd015298b2dc82',
  'phpoffice/phpexcel' => '1.8.2@1441011fb7ecdd8cc689878f54f8b58a6805f870',
  'psr/http-message' => '1.0.1@f6561bf28d520154e4b0ec72be95418abe6d9363',
  'psr/log' => '1.1.3@0f73288fd15629204f9d42b7055f72dacbe811fc',
  'psy/psysh' => 'v0.7.2@e64e10b20f8d229cac76399e1f3edddb57a0f280',
  'ralouphie/getallheaders' => '3.0.3@120b605dfeb996808c31b6477290a714d356e822',
  'swiftmailer/swiftmailer' => 'v5.4.12@181b89f18a90f8925ef805f950d47a7190e9b950',
  'symfony/console' => 'v3.0.9@926061e74229e935d3c5b4e9ba87237316c6693f',
  'symfony/css-selector' => 'v3.0.9@b8999c1f33c224b2b66b38253f5e3a838d0d0115',
  'symfony/debug' => 'v3.0.9@697c527acd9ea1b2d3efac34d9806bf255278b0a',
  'symfony/event-dispatcher' => 'v3.4.47@31fde73757b6bad247c54597beef974919ec6860',
  'symfony/finder' => 'v3.0.9@3eb4e64c6145ef8b92adefb618a74ebdde9e3fe9',
  'symfony/http-foundation' => 'v3.0.9@49ba00f8ede742169cb6b70abe33243f4d673f82',
  'symfony/http-kernel' => 'v3.0.9@d97ba4425e36e79c794e7d14ff36f00f081b37b3',
  'symfony/polyfill-ctype' => 'v1.22.1@c6c942b1ac76c82448322025e084cadc56048b4e',
  'symfony/polyfill-intl-idn' => 'v1.22.1@2d63434d922daf7da8dd863e7907e67ee3031483',
  'symfony/polyfill-intl-normalizer' => 'v1.22.1@43a0283138253ed1d48d352ab6d0bdb3f809f248',
  'symfony/polyfill-mbstring' => 'v1.22.1@5232de97ee3b75b0360528dae24e73db49566ab1',
  'symfony/polyfill-php56' => 'v1.20.0@54b8cd7e6c1643d78d011f3be89f3ef1f9f4c675',
  'symfony/polyfill-php72' => 'v1.22.1@cc6e6f9b39fe8075b3dabfbaf5b5f645ae1340c9',
  'symfony/polyfill-php80' => 'v1.22.1@dc3063ba22c2a1fd2f45ed856374d79114998f91',
  'symfony/process' => 'v3.0.9@768debc5996f599c4372b322d9061dba2a4bf505',
  'symfony/routing' => 'v3.0.9@9038984bd9c05ab07280121e9e10f61a7231457b',
  'symfony/translation' => 'v3.0.9@eee6c664853fd0576f21ae25725cfffeafe83f26',
  'symfony/var-dumper' => 'v3.0.9@1f7e071aafc6676fcb6e3f0497f87c2397247377',
  'symfony/yaml' => 'v3.3.18@af615970e265543a26ee712c958404eb9b7ac93d',
  'tijsverkoyen/css-to-inline-styles' => '2.2.3@b43b05cf43c1b6d849478965062b6ef73e223bb5',
  'vlucas/phpdotenv' => 'v2.6.7@b786088918a884258c9e3e27405c6a4cf2ee246e',
  'yajra/laravel-datatables-oracle' => 'v6.29.3@5ccbe38affa0a9930a2add19684e012bed09f62d',
  'zircote/swagger-php' => '3.1.0@9d172471e56433b5c7061006b9a766f262a3edfd',
  'doctrine/instantiator' => '1.4.0@d56bf6102915de5702778fe20f2de3b2fe570b5b',
  'fzaninotto/faker' => 'v1.9.2@848d8125239d7dbf8ab25cb7f054f1a630e68c2e',
  'hamcrest/hamcrest-php' => 'v1.2.2@b37020aa976fa52d3de9aa904aa2522dc518f79c',
  'mockery/mockery' => '0.9.11@be9bf28d8e57d67883cba9fcadfcff8caab667f8',
  'ozankurt/repoist' => '1.0.1@03812fcff931849d65765f9cecd451d698030460',
  'phpdocumentor/reflection-common' => '2.2.0@1d01c49d4ed62f25aa84a747ad35d5a16924662b',
  'phpdocumentor/reflection-docblock' => '5.2.2@069a785b2141f5bcf49f3e353548dc1cce6df556',
  'phpdocumentor/type-resolver' => '1.4.0@6a467b8989322d92aa1c8bf2bebcc6e5c2ba55c0',
  'phpspec/prophecy' => 'v1.10.3@451c3cd1418cf640de218914901e51b064abb093',
  'phpunit/php-code-coverage' => '2.2.4@eabf68b476ac7d0f73793aada060f1c1a9bf8979',
  'phpunit/php-file-iterator' => '1.4.5@730b01bc3e867237eaac355e06a36b85dd93a8b4',
  'phpunit/php-text-template' => '1.2.1@31f8b717e51d9a2afca6c9f046f5d69fc27c8686',
  'phpunit/php-timer' => '1.0.9@3dcf38ca72b158baf0bc245e9184d3fdffa9c46f',
  'phpunit/php-token-stream' => '1.4.12@1ce90ba27c42e4e44e6d8458241466380b51fa16',
  'phpunit/phpunit' => '4.8.36@46023de9a91eec7dfb06cc56cb4e260017298517',
  'phpunit/phpunit-mock-objects' => '2.3.8@ac8e7a3db35738d56ee9a76e78a4e03d97628983',
  'sebastian/comparator' => '1.2.4@2b7424b55f5047b47ac6e5ccb20b2aea4011d9be',
  'sebastian/diff' => '1.4.3@7f066a26a962dbe58ddea9f72a4e82874a3975a4',
  'sebastian/environment' => '1.3.8@be2c607e43ce4c89ecd60e75c6a85c126e754aea',
  'sebastian/exporter' => '1.2.2@42c4c2eec485ee3e159ec9884f95b431287edde4',
  'sebastian/global-state' => '1.1.1@bc37d50fea7d017d3d340f230811c9f1d7280af4',
  'sebastian/recursion-context' => '1.0.5@b19cc3298482a335a95f3016d2f8a6950f0fbcd7',
  'sebastian/version' => '1.0.6@58b3a85e7999757d6ad81c787a1fbf5ff6c628c6',
  'symfony/dom-crawler' => 'v3.0.9@dff8fecf1f56990d88058e3a1885c2a5f1b8e970',
  'webmozart/assert' => '1.10.0@6964c76c7804814a842473e0c8fd15bab0f18e25',
  'laravel/laravel' => 'dev-master@7d4c82f306180db50fd681bdeddad7893449a9b9',
);

    private function __construct()
    {
    }

    /**
     * @psalm-pure
     *
     * @psalm-suppress ImpureMethodCall we know that {@see InstalledVersions} interaction does not
     *                                  cause any side effects here.
     */
    public static function rootPackageName() : string
    {
        if (!class_exists(InstalledVersions::class, false) || !InstalledVersions::getRawData()) {
            return self::ROOT_PACKAGE_NAME;
        }

        return InstalledVersions::getRootPackage()['name'];
    }

    /**
     * @throws OutOfBoundsException If a version cannot be located.
     *
     * @psalm-param key-of<self::VERSIONS> $packageName
     * @psalm-pure
     *
     * @psalm-suppress ImpureMethodCall we know that {@see InstalledVersions} interaction does not
     *                                  cause any side effects here.
     */
    public static function getVersion(string $packageName): string
    {
        if (class_exists(InstalledVersions::class, false) && InstalledVersions::getRawData()) {
            return InstalledVersions::getPrettyVersion($packageName)
                . '@' . InstalledVersions::getReference($packageName);
        }

        if (isset(self::VERSIONS[$packageName])) {
            return self::VERSIONS[$packageName];
        }

        throw new OutOfBoundsException(
            'Required package "' . $packageName . '" is not installed: check your ./vendor/composer/installed.json and/or ./composer.lock files'
        );
    }
}