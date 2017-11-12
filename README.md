[![Build Status](https://travis-ci.org/sveneisenschmidt/yay.svg?branch=master)](https://travis-ci.org/sveneisenschmidt/yay) [![codecov](https://codecov.io/gh/sveneisenschmidt/yay/branch/master/graph/badge.svg)](https://codecov.io/gh/sveneisenschmidt/yay) [![StyleCI](https://styleci.io/repos/85753371/shield?branch=master)](https://styleci.io/repos/85753371) [![License](https://img.shields.io/badge/License-Apache%202.0-blue.svg)](https://opensource.org/licenses/Apache-2.0)

# ![yay](docs/src/logo.png) What is Yay?

## The Idea
> Gamification can be applied to all technical and non-technical tasks during our daily working life. Everything can be gamified.

<p style="text-align: center;">
    <img title="Yay!" src="docs/src/cycle.svg" style="max-width: 250px;">
</p>

## The Implementation
Yay is a **gamification engine**, exposing a web service API, for integrating any kinds of gamification features into your organisation. Yay originally started as a 36 hours hackathon project at [trivago](https://github.com/trivago) to bring gamification features to our organisation. To integrate yay into many application landscapes it offers flexible extension points to write your own integrations.

## Yay Documentation
How-to-use demo, installation instructions, configuration examples and extension documentation:

* [Installation](docs/installation.md)
* [Configuration](docs/configuration.md)
* [API](docs/api.md)
* [Demo](docs/demo.md)
* [Development](docs/development.md)
* Guides
    * [How to write and configure your integration](docs/guides/integrations.md)
    * [How to write and configure your webhooks](docs/guides/webhooks.md)

## Installation
Yay is tested and packable to run through Docker on any operating system that supports Docker.

* [Installation](docs/installation.md)
* [Releases on github.com](https://github.com/sveneisenschmidt/yay/releases)

## Usage
```bash
curl -X "POST" http://localhost:50080/api/progress/ \
    -d "{\"username\":\"jane.doe\",\"action\":\"demo-action\"}"

[
    {
        "name": "demo-achievement-01",
        "label": "demo-achievement-01",
        "description": "demo-achievement-01",
        "points": 50,
        "achieved_at": "2017-10-08T13:22:08+0000",
        "links": {
            "self": "http://localhost:50080/api/players/jane.doe/personal-achievements/",
            "player": "http://localhost:50080/api/players/jane.doe/",
            "achievement": "http://localhost:50080/api/achievements/demo-achievement-01/"
        }
    }
]
```

Additionally Yay supports integrating third party applications via webhooks. For a start Yay is shipped with support for GitHub, more platforms will follow soon. Until then you can follow the webhook guide "[How to write and configure your webhooks](docs/guides/webhooks.md)" or write and maybe even support your own webhooks/processors, yay!

```yml
integration:
    webhooks:
        incoming_processors:
            demo-processor:
                type: chain
                arguments: 
                    - [ example-github, demo-users ]
            demo-github:
                type: class
                class: Yay\ThirdParty\Github\Webhook\Incoming\Processor\GithubProcessor
            demo-users:
                type: static-map
                arguments:
                    - username
                    - 
                        - octocat: jane.doe
```
URL:  `/webhook/incoming/example-processor/`.

## Get Yay Support and Help

**Reporting Issues**: To report an issue with Yay, please create an Issue here on github: https://github.com/sveneisenschmidt/yay/issues


## License

This project is released under the terms of the [Apache 2.0 license](http://www.apache.org/licenses/LICENSE-2.0).
