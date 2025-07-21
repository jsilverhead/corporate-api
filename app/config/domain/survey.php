<?php

declare(strict_types=1);

use App\Domain\Survey\Service\DeleteSurveyTemplateService;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Config\DoctrineConfig;

use function Symfony\Component\DependencyInjection\Loader\Configurator\param;

return static function (ContainerConfigurator $container, DoctrineConfig $doctrine): void {
    $entityManger = $doctrine->orm()->entityManager('default');
    $entityManger
        ->mapping('App\\Domain\\Survey\\')
        ->type('attribute')
        ->prefix('App\Domain\Survey')
        ->dir(param('kernel.project_dir')->__toString() . '/src/Domain/Survey');

    $services = $container->services();
    $services->defaults()->autowire();

    $services->load(
        'App\\Domain\\Survey\\Service\\',
        param('kernel.project_dir')->__toString() . '/src/Domain/Survey/Service',
    );

    $services->load(
        'App\\Domain\\Survey\\Repository\\',
        param('kernel.project_dir')->__toString() . '/src/Domain/Survey/Repository',
    );

    $services->set(App\Domain\Survey\Service\CreateSurveyAnswerService::class)->public();
    $services->set(App\Domain\Survey\Service\CreateSurveyService::class)->public();
    $services->set(App\Domain\Survey\Service\CreateQuestionService::class)->public();
    $services->set(App\Domain\Survey\Service\CreateSurveyTemplateService::class)->public();
    $services->set(App\Domain\Survey\Service\ApplySurveyService::class)->public();
    $services->set(DeleteSurveyTemplateService::class)->public();
};
