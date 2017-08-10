<?php

namespace Brackets\AdminTranslations\Test\Feature\TestsFromSpatie;

use Brackets\AdminTranslations\Test\TestCase;
use Illuminate\Support\Facades\Artisan;
use Brackets\AdminTranslations\LanguageLine;
use Spatie\TranslationLoader\TranslationServiceProvider;

abstract class LanguageLineTestCase extends TestCase
{
    /** @var \Brackets\AdminTranslations\LanguageLine */
    protected $languageLine;

    public function setUp()
    {
        parent::setUp();

        Artisan::call('migrate');

        include_once __DIR__.'/../../../vendor/spatie/laravel-translation-loader/database/migrations/create_language_lines_table.php.stub';
        include_once __DIR__.'/../../../database/migrations/change_language_lines_table.php.stub';

        (new \CreateLanguageLinesTable())->up();
        (new \ChangeLanguageLinesTable())->up();

        $this->languageLine = $this->createLanguageLine('group', 'key', ['en' => 'english', 'nl' => 'nederlands']);
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            TranslationServiceProvider::class,
        ];
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['path.lang'] = $this->getFixturesDirectory('lang');

        $app['config']->set('database.default', 'sqlite');

        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        $app['config']->set('laravel-translation-loader.model', LanguageLine::class);
    }

    public function getFixturesDirectory(string $path): string
    {
        return __DIR__."/../../fixtures/{$path}";
    }

    protected function createLanguageLine(string $group, string $key, array $text): LanguageLine
    {
        return LanguageLine::create(compact('group', 'key', 'text'));
    }
}