[Table of Contents](README.md) | [Getting Started](getting-started.md) | [Customisation](customisation.md) | [Examples](examples.md) | **How To** | [Under The Hood](under-the-hood.md) | [Contributing](contributing.md)

---

# How To

* [How to connect Third Parties](how-to.md#how-to-connect-third-parties)
* [How to add your own levels](how-to.md#how-to-add-your-own-levels)

---

## How to connect Third Parties

| Service | Type | Events | Documentation | Processor |
|---|---|---|---|---|
| BitBucket | Source Code Management | commit & push (`push`), pull request (`pull_request.{created,updated,approved,unapproved,fulfilled,rejected}`) | [Webhook documentation](https://confluence.atlassian.com/bitbucket/manage-webhooks-735643732.html) | [Yay\ThirdParty\BitBucket\Webhook\Incoming\Processor\BitBucketProcessor](../../Yay/ThirdParty/BitBucket/Webhook/Incoming/Processor/BitBucketProcessor.php) |
| GitHub | Source Code Management | commit & push (`push`), pull request (`pull_request.{opened,merged,closed}`) | [Webhook documentation](https://developer.github.com/webhooks/) | [Yay\ThirdParty\GitHub\Webhook\Incoming\Processor\GitHubProcessor](../../Yay/ThirdParty/GitHub/Webhook/Incoming/Processor/GitHubProcessor.php) |
| GitLab | Source Code Management | commit & push (`push`), merge request (`merge_request.{opened,updated,merged,closed}`) | [Webhook documentation](https://docs.gitlab.com/ce/user/project/integrations/webhooks.html) | [Yay\ThirdParty\GitLab\Webhook\Incoming\Processor\GitLabProcessor](../../Yay/ThirdParty/GitLab/Webhook/Incoming/Processor/GitLabProcessor.php) |
| TravisCI | Continuous Integration | build events (`build.{pending,passed,fixed,failed,broken,still failing,canceled,errored}`) | [Webhook documentation](https://docs.gitlab.com/ce/user/project/integrations/webhooks.html) | [Yay\ThirdParty\TravisCI\Webhook\Incoming\Processor\TravisCIProcessor](../../Yay/ThirdParty/TravisCI/Webhook/Incoming/Processor/TravisCIProcessor.php) |
| Jira | Task management  | issue (`jira:issue_created, jira:issue_updated, jira:issue_deleted, jira:worklog_updated`), worklog (`jira.worklog_created, jira.worklog_updated, worklog_deleted`), comment (`jira.comment_created, jira.comment_updated`), project (`jira.project_created, jira.project_updated, jira.project_deleted`), version (`jira:version_released, jira:version_unreleased, jira:version_created, jira:version_moved, jira:version_updated, jira:version_deleted`), user (`jira.user_created, jira.user_updated, jira.user_deleted`), feature (`jira.option_voting_changed, jira.option_watching_changed, jira.option_unassigned_issues_changed, jira.option_subtasks_changed, jira.option_attachments_changed, jira.option_issuelinks_changed, jira.option_timetracking_changed`), sprint (`jira.sprint_created, jira.sprint_deleted, jira.sprint_updated, jira.sprint_started, jira.sprint_closed`), board (`jira.board_created, jira.board_updated, jira.board_deleted, jira.board_configuration_changed`) | [Webhook documentation](https://developer.atlassian.com/server/jira/platform/webhooks/) | [Yay\ThirdParty\Jira\Webhook\Incoming\Processor\JiraProcessor](../../Yay/ThirdParty/Jira/Webhook/Incoming/Processor/JiraProcessor.php) |


Example (BitBucket):

`POST /webhook/incoming/bitbucket/`

```yml
integration:
    webhooks:
        incoming_processors:
            bitbucket:
                type: class
                class: Yay\ThirdParty\BitBucket\Webhook\Incoming\Processor\BitBucketProcessor
```



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