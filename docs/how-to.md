[Table of Contents](README.md) | [Getting Started](getting-started.md) | [Customisation](customisation.md) | [Examples](examples.md) | **How To** | [Under The Hood](under-the-hood.md) | [Contributing](contributing.md)

---

# How To

* [How to connect to BitBucket](how-to.md#how-to-connect-to-bitbucket)
* [How to connect to GitHub](how-to.md#how-to-connect-to-github)
* [How to connect to GitLab](how-to.md#how-to-connect-to-gitlab)
* [How to connect to Travis CI](how-to.md#how-to-connect-to-travis-ci)
* [How to add your own levels](how-to.md#how-to-add-your-own-levels)

---

## How to connect to BitBucket

Git platform BitBucket uses the concept of webhooks [(official documentation)](https://confluence.atlassian.com/bitbucket/manage-webhooks-735643732.html) to connect their own and third party systems in a simple way. With this in mind it is possible to connect BitBucket and Yay! very easily, the only needed part is a custom processor that is able to interpret the payload sent by GitHub, process and transform it so Yay! is able to process it as well.  A custom processor for BitBucket is shipped by Yay.

```yml
integration:
    webhooks:
        incoming_processors:
            bitbucket:
                type: class
                class: Yay\ThirdParty\BitBucket\Webhook\Incoming\Processor\BitBucketProcessor
```

The [BitBucketProcessor](../../src/ThirdParty/BitBucket/Webhook/Incoming/Processor/BitBucketProcessor.php) processes BitBucket webhook payloads to extract `username` and `actions`.

Supported webhook events:
- commit & push (`push`)
- pull request (`pull_request.{created,updated,approved,unapproved,fulfilled,rejected}`)

---

## How to connect to GitHub

Git platform GitHub uses the concept of webhooks [(official documentation)](https://developer.github.com/webhooks/) to connect their own and third party systems in a simple way. With this in mind it is possible to connect GitHub and Yay! very easily, the only needed part is a custom processor that is able to interpret the payload sent by GitHub, process and transform it so Yay! is able to process it as well.  A custom processor for GitHub is shipped by Yay.

```yml
integration:
    webhooks:
        incoming_processors:
            github:
                type: class
                class: Yay\ThirdParty\GitHub\Webhook\Incoming\Processor\GitHubProcessor
```

The [GithubProcessor](../../src/ThirdParty/Github/Webhook/Incoming/Processor/GitHubProcessor.php) processes GitHub webhook payloads to extract `username` and `actions`.

Supported webhook events:
- commit & push (`push`)
- pull request (`pull_request.{opened,merged,closed}`)

---

## How to connect to GitLab

Git and CI platform GitLab uses the concept of webhooks [(official documentation)](https://docs.gitlab.com/ce/user/project/integrations/webhooks.html) to connect their own and third party systems in a simple way. With this in mind it is possible to connect GitLab and Yay! very easily, the only needed part is a custom processor that is able to interpret the payload sent by GitLab, process and transform it so Yay! is able to process it as well. A custom processor for GitLab is shipped by Yay.

```yml
integration:
    webhooks:
        incoming_processors:
            gitlab:
                type: class
                class: Yay\ThirdParty\GitLab\Webhook\Incoming\Processor\GitLabProcessor
```

The [GitLabProcessor](../../src/ThirdParty/GitLab/Webhook/Incoming/Processor/GitLabProcessor.php) processes GitLab webhook payloads to extract `username` and `actions`.

Supported webhook events:
- commit & push (`push`)
- merge request (`merge_request.{opened,updated,merged,closed}`)

--

## How to connect to Travis CI

CI platform Travis CI uses the concept of webhooks [(official documentation)](https://docs.travis-ci.com/user/notifications/#Configuring-webhook-notifications) to connect their own and third party systems in a simple way. With this in mind it is possible to connect Travis CI and Yay! very easily, the only needed part is a custom processor that is able to interpret the payload sent by Travis CI, process and transform it so Yay! is able to process it as well. A custom processor for Travis CI is shipped by Yay.

```yml
integration:
    webhooks:
        incoming_processors:
            travisci:
                type: class
                class: Yay\ThirdParty\TravisCI\Webhook\Incoming\Processor\TravisCIProcessor
```

Supported webhook events:
- build events (`build.{pending,passed,fixed,failed,broken,still failing,canceled,errored}`)

---

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