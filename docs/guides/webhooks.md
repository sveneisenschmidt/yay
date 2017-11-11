# Guides #

## Webhooks ##

Webhooks are the super glue that conntect the outside world with your Yay instance. 

### Internals

Processors implement the [ProcessorInterface](../../src/Yay/Component/Webhook/Incoming/ProcessorInterface.php) interface for incoming webhooks and implement the [ProcessorInterface](../../src/Yay/Component/Webhook/Outgoing/ProcessorInterface.php) interface for outgoing webhooks.

#### Incoming processors

During execution of the webhook the `process` method of the processor is called. A [Request](http://api.symfony.com/master/Symfony/Component/HttpFoundation/Request.html) instance is passed, it contains all request data passed to the applcation.

The webhook implementation requires that after the processor or all processor via the `chain` processor are run the request object holds both `username` and `action` attributes.

Processors can be combined through chaining to maximize flexibility, for an example you can follow the [How to write your own integration](integrations.md) guide. A very good example is to process palyload from GitHub and then use a custom processor to map github usernames to internal usernames.

Processors are then available as part of a webhook route `/webhook/incoming/{processor}/` and reachable via `GET` and `POST`.


```php
// ProcessorInterface.php
namespace Yay\Component\Webhook\Incoming;
use Symfony\Component\HttpFoundation\Request;

interface ProcessorInterface
{
    public function getName(): string;

    public function process(Request $request): void;
}

// MyProcesor.php
use Yay\Component\Webhook\Incoming\ProcessorInterface
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

        $request->attrbiutes->set('username', $username);
        $request->attrbiutes->set('action', $action);
    }
}
```

### Buit-in incoming processors

#### `ChainProcessor`

The [ChainProcessor](../../src/Yay/Component/Webhook/Incoming/Processor/ChainProcessor.php) is able to chain multiple processors to maximize flexibility. It is configured in your integration configuration.

```yml
integration:
    webhooks:
        incoming_processors:
            example-chain:
                type: chain
                arguments:
                    - [example-mycompany-jenkinsci, example-mycompany-users]
            # Your company provides a processor to transform Jenkins CI payloads
            example-mycompany-jenkinsci:
                class: MyCompany\Yay\Component\Webhook\Incoming\Processor\JenkinsProcessor
            # Your company provides a second processor to ap jenkins users to Yay players
            # based on a static configuration file deployed witht he application 
            example-mycompany-jenkinsci:
                class: MyCompany\Yay\Component\Webhook\Incoming\Processor\StaticUserProcessor
                arguments: [ '%kernel.root_dir/../integration/mycompany/users.yml%' ]
```
URL:  `/webhook/incoming/example-chain/`.

#### `DummyProcessor`

The [DummyProcessor](../../src/Yay/Component/Webhook/Incoming/Processor/DummyProcessor.php) is able to push key, value pairs to the request object, useful for fallback or default configuration.

```yml
integration:
    webhooks:
        incoming_processors:
            example-dummy:
                type: dummy
                arguments:
                    - 
                        - username: jane.doe
                        - aciton: example.action
```
URL:  `/webhook/incoming/example-dummy/`.

#### `NullProcessor`

The [NullProcessor](../../src/Yay/Component/Webhook/Incoming/Processor/NullProcessor.php) does nothing. Its `process` method is empty. It is used for testing.

```yml
integration:
    webhooks:
        incoming_processors:
            example-dummy:
                type: 'null'
```
URL:  `/webhook/incoming/example-null/`.

### Example GitHub

Infamous git platform GitHub use the concept of webhooks [(official documentation)](https://developer.github.com/webhooks/) to conntect their own and third party systems in a simple way. With this in mind it is possible to connect GitHub and Yay very easily, the only needed part is a custom processor that is able to interpret the payload sent by GitHub, process and transform it so Yay is able to process it as well.

#### Configuration in Yay

```yml
integration:
    webhooks:
        incoming_processors:
            example-github:
                type: class
                class: Yay\ThirdParty\Github\Webhook\Incoming\Processor\GithubProcessor
```
URL:  `/webhook/incoming/example-github/`.

#### Configuration in Github 

![Github Webhook Configuration](docs/src/github-webhook.png)

