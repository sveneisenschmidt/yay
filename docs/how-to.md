[Table of Contents](README.md) | [Getting Started](getting-started.md) | [Customisation](customisation.md) | [Examples](examples.md) | **How To** | [Under The Hood](under-the-hood.md) | [Contributing](contributing.md)

---

# How To

* [How to connect to Gitlab](how-to.md#how-to-connect-to-gitlab)
* [How to connect to GitHub](how-to.md#how-to-connect-to-github)
* [How to add your own levels](how-to.md#how-to-add-your-own-levels)

---

## How to connect to GitLab

Emerging git platform Gitlab uses the concept of webhooks [(official documentation)](https://docs.gitlab.com/ce/user/project/integrations/webhooks.html) to connect their own and third party systems in a simple way. With this in mind it is possible to connect Gitlab and Yay! very easily, the only needed part is a custom processor that is able to interpret the payload sent by Gitlab, process and transform it so Yay! is able to process it as well. A custom processor for Gitlab is shipped by Yay.

```yml
integration:
    webhooks:
        incoming_processors:
            gitlab:
                type: class
                class: Yay\ThirdParty\Gitlab\Webhook\Incoming\Processor\GitlabProcessor
```

The [GitlabProcessor](../../src/ThirdParty/Gitlab/Webhook/Incoming/Processor/GitlabProcessor.php) processes Gitlab webhook payloads to extract `username` and `actions`.

Support webhook events:
- commit & push (`push`)
- merge request (`merge_request.{opened,updated,reviewed,merged,closed}`)

---

## How to connect to GitHub

Famous git platform GitHub uses the concept of webhooks [(official documentation)](https://developer.github.com/webhooks/) to connect their own and third party systems in a simple way. With this in mind it is possible to connect GitHub and Yay! very easily, the only needed part is a custom processor that is able to interpret the payload sent by GitHub, process and transform it so Yay! is able to process it as well.  A custom processor for GitHub is shipped by Yay.

```yml
integration:
    webhooks:
        incoming_processors:
            github:
                type: class
                class: Yay\ThirdParty\GitHub\Webhook\Incoming\Processor\GitHubProcessor
```

The [GithubProcessor](../../src/ThirdParty/Github/Webhook/Incoming/Processor/GithubProcessor.php) processes GitHub webhook payloads to extract `username` and `actions`.

Support webhook events:
- commit & push (`push`)
- pull request (`pull_request.{opened,updated,reviewed,merged,closed}`)

-- 

## How to add your own levels

Yay! does ship with a purely random set of levels for testing purpose via the [default integration](../integration/default.yml). Find below the script from which the levels were generated.

```php
<?php
// generate-levels.php

require 'vendor/autoload.php';

use Faker\Factory as FakerFactory;
use Symfony\Component\Yaml\Yaml;

$faker = FakerFactory::create();
$faker->seed(time());

$levels = [];
foreach (range(0,100) as $index) {
    $levels["level-{$index}"] = [
        'level' => $index,
        'points' => $index * 100,
        'label' => $faker->unique()->jobTitle,
        'description' => $faker->unique()->catchPhrase,
    ];
}

print Yaml::dump(['levels' => $levels], 4, 4);
```

```console
php levels.php > levels.yml
```

It is encouraged to create your own integration that adds levels including meaningful score theresholds to the platform. Think of it as getting experience points in RPGs, with every level the amount of experience you have to earn is getting bigger. How to add levels to your integration can be found in the documentation for [levels](customization.md#levels).

For players reaching new levels it is of importance that the achievements they earn are configured to have points, for further information please see the documentation for configuring [achievements](customization.md#achievements).