# Integrations #

Integrations are used to extend the application with new actions and achievements. For now actions and achievements are provided as entities and configurable services. This is subject to change in the future and will be by high chance replaced by a single configuration file and format.

Before creating your own integration it is advised to have a look at the the [demo integration](integration/demo) provided in the application's source.

In our example we will create a `mycompany` integration. To start please execute the following commands to create our integration folder and configuration file.

```bash
mkdir -p integraiton/mycompany
touch integration/mycompany/entities.yml
touch integration/mycompany/services.yml
```

# Actions & Achivements

## Actions

Actions are of type [ActionDefinition](../src/Yay/Component/Entity/Achievement/ActionDefinition.php) and need to be imported to the database during installation. Let us assume you want to add a new achievement `mycompany-action-01`, for this we need to extend our `entities.yml` file.

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
        label: Achivement One
        description: My companies achievement one.
        points: 50
```
Hint: Achievements are defined via the `Nelmio/Alice` fixture definition for objects.

# Validation & granting an achievement

During runtime the application needs to know when to grant an achivement after certain actions have been performed by an player. To provide this functioanilty the application uses validators that check if achivement criterias are met. Validators are implementing [AchievementValidatorInterface](../src/Yay/Component/Engine/AchievementValidatorInterface.php), a default validator that uses the [Expression Language](https://symfony.com/doc/current/components/expression_language.html) component is provided and simplifies the evaluation of achivements. Let us assume you want to grant achievement `mycompany-achievement-01` when a player performs the `mycompany-action-01` action five times, for this we need to extend our `services.yml` file.

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
Hint 1: Achivement validators are defined via the symfony container specification for services.
Hint 2: Due to the connection between achievement and action through `addActionDefinition` of our `AchivementDefinition` we can use `filteredPersonalActions` to access on a player's personal actions and actions that are supported by our achievement.

# Using your integration

## 1) Install your integration
```bash
$ make shell
$ php bin/console yay:integration:install integration/mycompany
```

## 2) Remove your integration
```bash
$ make shell
$ php bin/console yay:integration:uninstall integration/mycompany
```
Hint: The uninstall routine will only remove the service configuration. Entities created during the installation will not be removed due to the fact that they often share relations to other entities (e.g. players and their personal achievements.)

