# Symfony API Template

Репозиторий-шаблон для быстрого развертывания API-приложения с использованием Symfony.

## Как использовать этот репозиторий

### Сделать форк репозитория

Используйте кнопку "Use this template", чтобы создать новый репозиторий на основе этого.

## Что включает в себя репозиторий

- Docker
- Symfony
- Инструменты для написания документации к API
- PhpUnitTest
- CSFixer
- Psalm
- Команды PhpStorm для запуска
  - Линтеров (CSFixer, Prettier, EsLint)
  - Psalm
  - Сборки документации
  - Команды запуска тестов
  - Команды для очистки кеша Symfony
  - Команды для работы с доктриной
    - setup clean DB
    - create database
    - drop database
    - migrate migrations
    - migrations diff
    - rollback to previous migration
    - schema validate
    - update schema
    - update schema (dump SQL)

### Тесты

Тесты запускаются с помощью [PHPUnit](https://phpunit.de). Они находятся в директории [`tests`](./tests).

### Стиль кода

Стиль кода проверяется и исправляется с помощью [PHP-CS-Fixer](https://github.com/FriendsOfPHP/PHP-CS-Fixer) и [Prettier](https://prettier.io).

### Статический анализ кода

Мы проверяем код на потенциальные ошибки с помощью [Psalm](https://psalm.dev) и [ESLint](https://eslint.org). Игнорировать ошибки статических анализаторов категорически не рекомендуется.

Psalm работает в самом строгом режиме.

### Автоматизации

Все автоматизации вроде проверки стиля кода, статического анализа, тестов и т.п. запускаются через [GitHub Actions](https://github.com/features/actions). Все экшены описаны в директории [`.github/workflows`](./.github/workflows).

#### Test Framework

Для работы тестов нужно настроить [Test Framework](https://www.jetbrains.com/help/phpstorm/php-test-frameworks.html).

`path to script`: symfony-api-template/app/vendor/autoload.php

`default configuration file`: symfony-api-template/app/phpunit.xml

При запуске теста в дебаг режиме может возникнуть проблема:

```
Connection was not established.
Probably 'xdebug.remote_host=host.docker.internal' is incorrect.
Change 'xdebug.remotehost'
```

Для решения проблемы в настройках IDE `PHP -> Debug` Debug port оставить только 9003 порт.

## Docker

Для локальной разработки используйте образ, основанный на [`php-cli-local.dockerfile`](./docker/php/php-cli-local.dockerfile).

Для сборки образа, который будет куда-то задеплоен, используйте [`php-fpm-prod.dockerfile`](./docker/php/php-fpm-prod.dockerfile).

Для локальной разработки удобно использовать Docker Compose и конфигурацию, описанную в файле [`docker-compose.yaml`](docker/docker-compose.yaml) (подробнее об этом расписано ниже).

Docker-образы автоматически собираются в GitHub Actions, процесс описан в файле [`.github/workflows/docker.yml`](./.github/workflows/push_container_to_docker_template).

## Рекомендации по настройке [PhpStorm](https://www.jetbrains.com/phpstorm/)

В директории [`.run`](./.run) находятся [Run/debug configurations](https://www.jetbrains.com/help/phpstorm/run-debug-configuration.html), которые IDE автоматически подхватит при первом открытии проекта. Это заранее написанные скрипты и последовательности из скриптов, которые можно удобно использовать при локальной разработке. Вместо того чтобы выполнять скрипты через терминал, рекомендуется использовать для этого находящиеся в репозитории run/debug configurations, которые покрывают большинство типовых задач.

Проект не предусмотрен для работы или разработки в окружении Windows. Проект запустится только в окружениях Linux и MacOS. Если у вас Windows, используйте [Windows Subsystem for Linux (WSL)](https://docs.microsoft.com/en-us/windows/wsl/) или удаленный интерпретатор.

Независимо от операционной системы, для разработки рекомендуется использовать удаленный интерпретатор (см. "[Configure remote PHP interpreter](https://www.jetbrains.com/help/phpstorm/configuring-remote-interpreters.html)"), запущенный через Docker Compose. Не рекомендуется использовать интерпретатор PHP, который установлен в вашу операционную систему, т.к. в конфигурации Docker (см. [`php-cli-local.dockerfile`](./docker/php/php-cli-local.dockerfile)) уже описана полностью пригодная для работы среда - со всеми зависимостями, правильной версией PHP, Xdebug и всеми необходимыми расширениями для PHP.

Включите в настройках IDE инспекции [Psalm](https://www.jetbrains.com/help/phpstorm/using-psalm.html), чтобы вы узнавали о проблемах в статическом анализе сразу же, а не после создания пулл-реквеста. Для этих двух инструментов рекомендуем использовать интерпретатор PHP, установленный в вашей операционной системе, а вот использование удаленного интерпретатора может сильно замедлить процесс анализа вашего кода, из-за чего статус инспекций будет обновляться с сильной задержкой.

В IDE рекомендуется включить [ESLint](https://www.jetbrains.com/help/phpstorm/eslint.html), а также [настройку, которая будет автоматически исправлять код при сохранении файлов](https://www.jetbrains.com/help/phpstorm/eslint.html#ws_eslint_configure_run_eslint_on_save).

Следующие плагины могут сильно облегчить работу с кодом:

- [Php Inspections (EA Extended)](https://plugins.jetbrains.com/plugin/7622-php-inspections-ea-extended-) - дополнительные инспекции для статического анализа.
- [Symfony Support](https://plugins.jetbrains.com/plugin/7219-symfony-support) - для подсказок и анализа кода, связанного с Symfony.
