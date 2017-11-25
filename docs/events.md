# Events #

Yay provides a set of events to easily hook into. How to work with events is illustrated through the [ActivityListener](../src/App/Engine/EventListener/ActivityListener.php) and the [services.yml](../src/App/Engine/Resources/config/services.yml) configuration.

```yml
# services.yml
MyListener:
    tags:
        - { name: kernel.event_listener, event: yay.engine.grant_personal_action, method: onGrantPersonalAction }
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
| yay.engine.pre_save | `Component\Engine\Event\ObjectEvent` | `Component\Entity\*`  | Triggered before an entity is saved. |
| yay.engine.post_save | `Component\Engine\Event\ObjectEvent` | `Component\Entity\*`  | Triggered after an entity was saved. |
| yay.engine.grant_personal_achievement | `Component\Engine\Event\ObjectEvent` | `Component\Entity\Achievement\PersonalAchievement`  | Triggered after a player has been awarded with a new achievement. |
| yay.engine.grant_personal_action | `Component\Engine\Event\ObjectEvent` |  |`Component\Entity\Achievement\PersonalAction`  | Triggered after a player has been accounted a new action.  |
