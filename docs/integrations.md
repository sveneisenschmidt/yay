# Integrations #

Integrations are used to extend the application with new actions and achievements. For now actions and achievements are provided as entities and configurable services. This is subject to change in the future and will be by high chance replaced by a single configuration file and format.

Before creating your own integration it is advised to have a look at the the [demo integration](integration/demo) provided in the application's source.

In our example we will create a `mycompany` integration. To start please execute the following commands to create our integration folder and configuration file.

```bash
mkdir -p integraiton/mycompany
touch integration/mycompany/entities.yml
touch integration/mycompany/services.yml
```

# Actions & Achievements

## Actions

Actions are of type [ActionDefinition](../src/Yay/Component/Entity/Achievement/ActionDefinition.php) and need to be imported to the database during installation. Let us assume you want to add a new action `mycompany-action-01`, for this we need to extend our `entities.yml` file.

```yaml
Yay/Component/Entity/Achievement/AchievementDefinition:
    mycompany-action-01:
        __construct: ['mycompany-action-01']
        label: Action One
        description: My companies action one.
```
Hint: Actions are defined via the `Nelmio/Alice` fixture definition for objects.

## Achievements

Achievements are of type [AchievementDefinition](..src/Yay/Component/Entity/Achievement/AchievementDefinition.php) and need to be imported to the database during installation. Let us assume you want to add a new achievement `mycompany-achievement-01`, for this we need to extend our `entities.yml` file.

```yaml
Yay/Component/Entity/Achievement/AchievementDefinition:
    mycompany-achievement-01:
        __construct: ['mycompany-achievement-01']
        __calls:
          - addActionDefinition: [ '@mycompany-action-01' ]
        label: Achievement One
        description: My companies achievement one.
        points: 50
```
Hint: Achievements are defined via the `Nelmio/Alice` fixture definition for objects.

# Validation & granting an achievement

During runtime the application needs to know when to grant an Achievement after certain actions have been performed through a player. To provide this functioanilty the application uses validators that are able to tell if achievement criterias are met. Validators are implementing the [AchievementValidatorInterface](../src/Yay/Component/Engine/AchievementValidatorInterface.php), a default validator that uses the [Expression Language](https://symfony.com/doc/current/components/expression_language.html) component is provided and simplifies the evaluation of Achievements. Let us assume you want to grant achievement `mycompany-achievement-01` when a player performs the `mycompany-action-01` action five times, for this we need to extend our `services.yml` file.

```yaml
parameters: ~

services:
    _defaults:
        autoconfigure: true

    mycompany-achievement-validator-01:
        class:  Yay\Component\Engine\AchievementValidator\Validator\ExpressionLanguageValidator
        arguments:
            - achievement.getName() in ['mycompany-achievement-01'] and filteredPersonalActions.count() >= 5
```
Hint 1: Achievement validators are defined via the symfony container specification for services.

Hint 2: Achievement validators are automatically registered if when the option `autoconfigure: true` as a default option or service property is set.

Hint 3: Due to the connection between achievement and action through `addActionDefinition` of our `AchievementDefinition` we can use `filteredPersonalActions` to access on a player's personal actions and actions that are supported by our achievement.

# Using your integration

## 1) Enable your integration
```bash
$ make shell
$ php bin/console yay:integration:enable mycompany integration/mycompany
```

## 2) Disable your integration
```bash
$ make shell
$ php bin/console yay:integration:disable mycompany
```
Hint: The disable routine will only remove the service configuration. Entities created during the installation will not be removed due to the fact that they often share relations to other entities (e.g. players and their personal achievements.)

