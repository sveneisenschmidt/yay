[Table of Contents](README.md) | [Getting Started](getting-started.md) | [Customisation](customisation.md) | [Examples](examples.md) | **How To**| [Under The Hood](under-the-hood.md) | [Contributing](contributing.md)

---

# How To

* [How to connect to GitLab](how-to.md#how-to-connect-to-gitlab)
* [How to connect to GitHub](how-to.md#how-to-connect-to-github)
* [How to add own player levels](how-to.md#how-to-add-own-player-levels)

---

## How to connect to GitLab

Famous git platform GitHub uses the concept of webhooks [(official documentation)](https://developer.github.com/webhooks/) to connect their own and third party systems in a simple way. With this in mind it is possible to connect GitHub and Yay very easily, the only needed part is a custom processor that is able to interpret the payload sent by GitHub, process and transform it so Yay is able to process it as well.  A custom processor for GitHub is shipped by Yay.

```yml
integration:
    webhooks:
     incoming_processors:
         example-processor:
          type: chain
          arguments:
              - [ example-github ]
         example-github:
          type: class
          class: Yay\ThirdParty\Github\Webhook\Incoming\Processor\GithubProcessor
         example-users:
          type: static-map
          arguments:
              - username
              -
               octocat: jane.doe
```

The [GitlabProcessor](../../src/ThirdParty/Gitlab/Webhook/Incoming/Processor/GitlabProcessor.php) processes Gitlab webhook payloads to extract `username` and `actions`.

Support webhook events:
- commit & push (`push`)
- merge request (`merge_request.{opened,updated,reviewed,merged,closed}`)

---

## How to connect to GitHub

Emerging git platform Gitlab uses the concept of webhooks [(official documentation)](https://docs.gitlab.com/ce/user/project/integrations/webhooks.html) to connect their own and third party systems in a simple way. With this in mind it is possible to connect Gitlab and Yay very easily, the only needed part is a custom processor that is able to interpret the payload sent by Gitlab, process and transform it so Yay is able to process it as well. A custom processor for Gitlab is shipped by Yay.

```yml
integration:
    webhooks:
     incoming_processors:
         example-processor:
          type: chain
          arguments:
              - [ example-gitlab ]
         example-github:
          type: class
          class: Yay\ThirdParty\Gitlab\Webhook\Incoming\Processor\GitlabProcessor
         example-users:
          type: static-map
          arguments:
              - username
              -
               octocat: jane.doe
```

The [GithubProcessor](../../src/ThirdParty/Github/Webhook/Incoming/Processor/GithubProcessor.php) processes GitHub webhook payloads to extract `username` and `actions`.

Support webhook events:
- commit & push (`push`)
- pull request (`pull_request.{opened,updated,reviewed,merged,closed}`)

---

## How to add own player levels

Coming soon ...