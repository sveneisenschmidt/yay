trivago/yay
===

Gamification done simple.

## Integration

### Configuration

#### 1) Define your Actions (ActionDefinition)

See `Yay\Component\Entity\Achievement\ActionDefinition` in `integration/demo/entities.yml`. 
Structure: Actions are defined via the `Nelmio\Alice` fixture definition for objects.

#### 2) Define your Achievements (AchievementDefinition)

See `Yay\Component\Entity\Achievement\AchievementDefinition` in `integration/demo/entities.yml`
Structure: Achievements are defined via the `Nelmio\Alice` fixture definition for objects.

#### 3) Define how to validate your Achievements (AchievementValidation)

See `services: ...` in `integration/demo/services.yml`.
Structure: Achievement validators are defined as tagged services. They need to implement 
the `Yay\Component\Engine\AchievementValidatorInterface` and can be any object of your choice.

### Installation

#### 1) Install your integration
```bash
$ make shell
$ php bin/console yay:integration:install path/to/your/integration
```

#### 2) Remove your integration
```bash
$ make shell
$ php bin/console yay:integration:uninstall path/to/your/integration
```

Hint: _The uninstall routine will only remove the service configuration all entities 
will not be removed due to the fact that they can bring relations to other entities like
players or granted achievements._

