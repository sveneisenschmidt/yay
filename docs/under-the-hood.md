[Table of Contents](README.md) | [Getting Started](getting-started.md) | [Customisation](customisation.md) | [How To](how-to.md) | [Examples](examples.md) | **Under The Hood** | [Contributing](contributing.md)

---

# Under The Hood

* [Commands](under-the-hood.md#commands)
* [Events](under-the-hood.md#events)
* [Webhooks](under-the-hood.md#webhooks) ([GitHub](under-the-hood.md#github), [GitLab](under-the-hood.md#gitlab))

---

## Commands

Yay! is shipped with a collection of commands that are necessary to configure and control a running Yay! installation.

| Command | Description | Example |
|---|---|---|
| `yay:integration:enable <name> <path>` | Enables an integration. | `php bin/console --env=prod yay:integration:enable demo integration/demo` |
| `yay:integration:disable <name>` | Disables an integration. | `php bin/console --env=prod yay:integration:disable demo` |
| `yay:integration:validate <name> <path>` | Validates an integration. | `php bin/console --env=prod yay:integration:validate demo integration/demo` |
| `yay:recalculate <player>` | Recalculates a player's progress. | `php bin/console --env=prod yay:recalculate alex.doe` |
Hint: It is important to always pass the environment Yay! is running in as the env parameter.

---

## Events

Yay! provides a set of events to easily hook into. How to work with events is illustrated through the [ActivityListener](../src/Component/Engine/EventListener/ActivityListener.php) and the [services.yml](../src/App/Engine/Resources/config/services.yml) configuration.

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
| yay.engine.grant_personal_action | [ObjectEvent](../src/Component/Engine/Event/ObjectEvent.php) | [PersonalAction](../src/Component/Entity/Achievement/PersonalAction.php) | Triggered after a player has been accounted a new action. |
| yay.engine.create_player | [ObjectEvent](../src/Component/Engine/Event/ObjectEvent.php) | [Player](../src/Component/Entity/Player.php) | Triggered after a new player has been created. |
| yay.engine.change_level | [ObjectEvent](../src/Component/Engine/Event/ObjectEvent.php) | [Player](../src/Component/Entity/Player.php) | Triggered after a player's level has been changed. |
| yay.engine.change_score | [ObjectEvent](../src/Component/Engine/Event/ObjectEvent.php) | [Player](../src/Component/Entity/Player.php) | Triggered after a player's score has been changed. |
---

## Webhooks

Webhooks are the link that joins the outside world with your Yay! instance.

### Internals

Processors implement the `ProcessorInterface`([1](../src/Component/Webhook/Incoming/ProcessorInterface.php), [2](../src/Component/Webhook/Outgoing/ProcessorInterface.php)) for incoming and outgoing webhooks.

#### Incoming processors

During execution of the webhook the `process` method of the processor is called. A [Request](http://api.symfony.com/master/Symfony/Component/HttpFoundation/Request.html) instance is passed, it contains all request data passed to the application.

The webhook implementation requires that after the processor or all processors via the `chain` processor are run the request object holds both `username` and `action` attributes.

Processors can be combined through chaining to maximise flexibility, you can follow the [GitHub example](under-the-hood.md#example-github) to see all the benefits of processing webhook payloads. E.g you can process a payload from GitHub and then use a custom processor to map github usernames to internal usernames.

Processors are then available as part of a webhook route `/webhook/incoming/{processor}/` and reachable via `GET` and `POST`.


```php
// ProcessorInterface.php
namespace Component\Webhook\Incoming;
use Symfony\Component\HttpFoundation\Request;

interface ProcessorInterface
{
    public function getName(): string;

    public function process(Request $request): void;
}

// MyProcesor.php
use Component\Webhook\Incoming\ProcessorInterface
use Symfony\Component\HttpFoundation\Request;

class MyProcessor implements ProcessorInterface
{
    /* @var string */
    protected $name;

    public function __construct(string $name)
    {
     $this->name = $name;
    }

    public function getName(): string
    {
     return $this->name:
    }

    public function process(Request $request): void
    {
     // extract, transform data from $request object, assign $username and $action
     // $username = ...
     // $action = ...

     $request->request->set('username', $username);
     $request->request->set('action', $action);
    }
}
```

### Built-in incoming processors

#### `ChainProcessor`

The [ChainProcessor](../../src/Component/Webhook/Incoming/Processor/ChainProcessor.php) is able to chain multiple processors to maximise flexibility. It is configured in your integration configuration.

```yml
integration:
    webhooks:
     incoming_processors:
            # Chains multiple processors into one
            example-chain:
                type: chain
                arguments:
                    - [example-mycompany-jenkinsci, example-mycompany-users]
            # Your company provides a processor to transform Jenkins CI payloads
            example-mycompany-jenkinsci:
                type: class
                class: MyCompany\Component\Webhook\Incoming\Processor\JenkinsProcessor
            # Your company provides a second processor to map jenkins users to Yay! players
            # based on a static configuration file deployed with the application
            example-mycompany-users:
                type: class
                class: MyCompany\Component\Webhook\Incoming\Processor\StaticUserProcessor
                arguments: [ '%kernel.root_dir/../integration/mycompany/users.yml%' ]
```
URL:  `/webhook/incoming/example-chain/`.

#### `DummyProcessor`

The [DummyProcessor](../../src/Component/Webhook/Incoming/Processor/DummyProcessor.php) is able to push key, value pairs to the request object, useful for fallback or default configuration.

```yml
integration:
    webhooks:
     incoming_processors:
        example-dummy:
            type: dummy
            arguments:
                -
                    username: alex.doe
                    action: example.action
```
URL:  `/webhook/incoming/example-dummy/`.

#### `NullProcessor`

The [NullProcessor](../../src/Component/Webhook/Incoming/Processor/NullProcessor.php) does nothing. Its `process` method is empty. It is used for testing.

```yml
integration:
    webhooks:
        incoming_processors:
            example-null:
                type: 'null'
```
URL:  `/webhook/incoming/example-null/`.

#### `StaticMapProcessor`

The [StaticMapProcessor](../../src/Component/Webhook/Incoming/Processor/StaticMapProcessor.php) remaps the specified request attribute values.

```yml
integration:
    webhooks:
     incoming_processors:
            example-static-map:
                type: static-map
                arguments:
                    - username
                    -
                    # username=octocate => username=alex.doe
                    octocat: alex.doe
```
URL:  `/webhook/incoming/example-static-map/`.

#### `SimpleProcessor`

The [SimpleProcessor](../../src/Component/Webhook/Incoming/Processor/SimpleProcessor.php) accepts a json payload and extracts `username` and `action` fields.

```json
{
	"username": "Alex Doe",
	"action": "example.action"
}
```

```yml
integration:
    webhooks:
     incoming_processors:
            example-simple:
                type: simple
```
URL:  `/webhook/incoming/example-simple/`.


### Third party incoming processors

Yay ships with a colleciton of incoming processors to connect to third parties like GitHub, GitLab, BitBucket, Travsic CI and more. Supported actions and events can be found at [How to connect Third Parties](how-to.md#how-to-connect-third-parties).