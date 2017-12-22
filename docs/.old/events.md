# Events #

Yay provides a set of events to easily hook into. How to work with events is illustrated through the [ActivityListener](../src/Component/Engine/EventListener/ActivityListener.php) and the [services.yml](../src/App/Engine/Resources/config/services.yml) configuration.

```yml
# services.yml
MyListener:
    tags:
        - { name: yay.event_listener, event: yay.engine.grant_personal_action, method: onGrantPersonalAction }
```
```php
# MyListener.php
class MyListener
{
    public function onGrantPersonalAction(ObjectEvent $event): void
    {
        /** @var PersonalAction $personalAction */
        $personalAction = $event->getObject();

        // ...
    }
}
```

| Name | Type | Object | Description |
|---|---|---|---|
| yay.engine.pre_save | [ObjectEvent](../src/Component/Engine/Event/ObjectEvent.php) | [Entity\*](../src/Component/Entity)  | Triggered before an entity is saved. |
| yay.engine.post_save | [ObjectEvent](../src/Component/Engine/Event/ObjectEvent.php) | [Entity\*](../src/Component/Entity)  | Triggered after an entity was saved. |
| yay.engine.grant_personal_achievement | [ObjectEvent](../src/Component/Engine/Event/ObjectEvent.php) | [PersonalAchievement](../src/Component/Entity/Achievement/PersonalAchievement.php)  | Triggered after a player has been awarded with a new achievement. |
| yay.engine.grant_personal_action | [ObjectEvent](../src/Component/Engine/Event/ObjectEvent.php) | [PersonalAction](../src/Component/Entity/Achievement/PersonalAction.php) | Triggered after a player has been accounted a new action.  |
| yay.engine.create_player | [ObjectEvent](../src/Component/Engine/Event/ObjectEvent.php) | [Player](../src/Component/Entity/Player.php) | Triggered after a new player has been created.  |