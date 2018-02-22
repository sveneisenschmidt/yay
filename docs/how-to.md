[Table of Contents](README.md) | [Getting Started](getting-started.md) | [Customisation](customisation.md) | [Examples](examples.md) | **How To** | [Under The Hood](under-the-hood.md) | [Contributing](contributing.md)

---

# How To

* [How to connect Third Parties](how-to.md#how-to-connect-third-parties)
* [How to add your own levels](how-to.md#how-to-add-your-own-levels)

---

## How to connect Third Parties

| Service | Category | Type |Events | Documentation | Processor | Example |
|---|---|---|---|---|---|---|
| BitBucket | Source Code Management | Official Webhook Support | commit & push (`push`), pull request (`pull_request.{created,updated,approved,unapproved,fulfilled,rejected}`) | [Webhook documentation](https://confluence.atlassian.com/bitbucket/manage-webhooks-735643732.html) | [ThirdParty\BitBucket\Webhook\Incoming\Processor\BitBucketProcessor](../src/ThirdParty/BitBucket/Webhook/Incoming/Processor/BitBucketProcessor.php) | [integration/example/bitbucket.yml](../integration/example/bitbucket.yml) |
| GitHub | Source Code Management | Official Webhook Support | commit & push (`push`), pull request (`pull_request.{opened,merged,closed}`) | [Webhook documentation](https://developer.github.com/webhooks/) | [ThirdParty\GitHub\Webhook\Incoming\Processor\GitHubProcessor](../src/ThirdParty/GitHub/Webhook/Incoming/Processor/GitHubProcessor.php) | [integration/example/github.yml](../integration/example/github.yml) |
| GitLab | Source Code Management | Official Webhook Support | commit & push (`push`), merge request (`merge_request.{opened,updated,merged,closed}`) | [Webhook documentation](https://docs.gitlab.com/ce/user/project/integrations/webhooks.html) | [ThirdParty\GitLab\Webhook\Incoming\Processor\GitLabProcessor](../src/ThirdParty/GitLab/Webhook/Incoming/Processor/GitLabProcessor.php) | [integration/example/gitlab.yml](../integration/example/gitlab.yml) |
| TravisCI | Continuous Integration | Official Webhook Support | build events (`build.{pending,passed,fixed,failed,broken,still failing,canceled,errored}`) | [Webhook documentation](https://docs.gitlab.com/ce/user/project/integrations/webhooks.html) | [ThirdParty\TravisCI\Webhook\Incoming\Processor\TravisCIProcessor](../src/ThirdParty/TravisCI/Webhook/Incoming/Processor/TravisCIProcessor.php) | [integration/example/travisci.yml](../integration/example/travisci.yml) |
| Jira | Task management  | Official Webhook Support | issue (`jira:issue_created, jira:issue_updated, jira:issue_deleted, jira:worklog_updated`), worklog (`jira.worklog_created, jira.worklog_updated, worklog_deleted`), comment (`jira.comment_created, jira.comment_updated`), project (`jira.project_created, jira.project_updated, jira.project_deleted`), version (`jira:version_released, jira:version_unreleased, jira:version_created, jira:version_moved, jira:version_updated, jira:version_deleted`), user (`jira.user_created, jira.user_updated, jira.user_deleted`), feature (`jira.option_voting_changed, jira.option_watching_changed, jira.option_unassigned_issues_changed, jira.option_subtasks_changed, jira.option_attachments_changed, jira.option_issuelinks_changed, jira.option_timetracking_changed`), sprint (`jira.sprint_created, jira.sprint_deleted, jira.sprint_updated, jira.sprint_started, jira.sprint_closed`), board (`jira.board_created, jira.board_updated, jira.board_deleted, jira.board_configuration_changed`) | [Webhook documentation](https://developer.atlassian.com/server/jira/platform/webhooks/) | [ThirdParty\Jira\Webhook\Incoming\Processor\JiraProcessor](../src/ThirdParty/Jira/Webhook/Incoming/Processor/JiraProcessor.php) | [integration/example/jira.yml](../integration/example/jira.yml) |
| Jenkins | Continuous Integration | Custom Support | build events (`build.{STARTED,ABORTED,FAILED,SUCCESS}`) | [Webhook documentation](how-to.md#jenkins) | [Yay\Component\Webhook\Incoming\Processor\SimpleProcessor](../src/Component/Webhook/Incoming/Processor/SimpleProcessor.php) | ... |


Example (BitBucket):

```yml
integration:
    webhooks:
        incoming_processors:
            bitbucket:
                type: class
                class: ThirdParty\BitBucket\Webhook\Incoming\Processor\BitBucketProcessor
```

`POST /webhook/incoming/bitbucket/`

### Jenkins

**Required Plugins:**

| Plugin| Description |
|---|---| 
| [Build User Vars](https://plugins.jenkins.io/build-user-vars-plugin) | This plugin provides a set of environment variables that describe the user who started the build.  |
| [PostBuild Script](https://plugins.jenkins.io/postbuildscript) | This plugin makes it possible to execute a set of scripts at the end of the build. |


**Configuration inside Jenkins**

1. Under `Build Environment` in `General` enable `Set jenkins user build variables` to prove the `BUILD_USER` environment variable inside tasks and actions.


2. After our build is finished we need to send our data to Yay!. To do so add a final `Post-build Actions` in `Build`. Configure it to have a new build step via `Add post build step`, select all build status or the ones you want the webhook triggered. Next add within the post build step a new build step by clicking `Add build step`, select `Execute Shell`. Paste the following code into the text area, replace the `WEBHOOK_URL` with your incoming webhook endpoint. 

```shell
WEBHOOK_URL="http://localhost:50080/webhook/incoming/jenkins/"
PAYLOAD="{\"username\":\"${BUILD_USER}\",\"action\":\"build.${BUILD_RESULT}\"}"

curl -sS -X POST -d "${PAYLOAD}" ${WEBHOOK_URL} 
```

The data structure defined in `PAYLOAD` consists of a JSON payload including `username` and `action`. The `SimpleProcessor` processor shipped by Yay! is then able to process the payload with ease. 

3. If you also need to get notified when a regular build job starts, you can modify above example and set `BUILD_RESULT` to `STARTED`.

```shell
BUILD_RESULT="STARTED"
WEBHOOK_URL="http://localhost:50080/webhook/incoming/jenkins/"
PAYLOAD="{\"username\":\"${BUILD_USER}\",\"action\":\"jenkins.build_${BUILD_RESULT}\"}"

curl -sS -X POST -d "${PAYLOAD}" ${WEBHOOK_URL} 
```

**Configuration inside Yay!**

Accepting and processing the Jenkins payload is as easy as setting `type: simple` in your incoming processor configuration.

```yml
integration:
    webhooks:
        incoming_processors:
            jenkins:
                type: simple
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