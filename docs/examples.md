[Table of Contents](README.md) | [Getting Started](getting-started.md) | [Customization](customization.md) | **Examples** | [Under The Hood](under-the-hood.md) | [Contributing](contributing.md)


---

# Examples

* [Usage / API](examples.md#usage--api)
* [Demo](examples.md#setup)
---

## Usage / API

Yay's API supports filtering, sorting and paginating for `GET requests.

An on-the-fly generated API documentation can be found under [`http://localhost:50080/api/doc`](http://localhost:50080/api/doc), run it with `make start`. The latest stable version [`https://yay-demo.sloppy.zone/api/doc`](https://yay-demo.sloppy.zone/api/doc) is available via `sloppy.io`.

### Filtering results

Filtering for specific results is achieved by the `filter` query paramter.

#### Filter by `name`, it must equal `Jane Doe`:
```bash
curl -gX "GET" http://localhost:50080/api/players/?filter[name]=Jane+Doe
// Same as: curl -gX "GET" http://localhost:50080/api/players/?filter[name:eq]=Jane+Doe

[
    {
        "name": "John Doe",
        "username": "john.doe",
        "created_at": "2018-01-02T13:24:40+00:00",
    },
    {
        "name": "Jane Doe",
        "username": "jane.doe",
        "created_at": "2018-01-02T13:24:19+00:00",
    }
]
```

#### Supported filter methods:

The filtering uses a specific syntax to enable different types of matching strategies: `filter[$field:$expression]=$value`.

| Expression | Example | Description |
|---|---|---|
| * | `filter[name]=Jane+Doe` | Value of field `name` equals `Jane Doe`. |
| eq | `filter[name:eq]=Jane+Doe` | Value of field `name` equals `Jane Doe`. |
| gt | `filter[score:gt]=10` | Value of field `name` is greater than `10`. |
| lt | `filter[score:lt]=10` | Value of field `name` is less than `10`. |
| lte | `filter[score:lte]=10` | Value of field `name` is greater than or equals `10`. |
| gte | `filter[score:gte]=10` | Value of field `name` is less than or equals `10`. |
| neq | `filter[name:neq]=Jane+Doe` | Value of field `name` does not equals `Jane Doe`. |
| contains | `filter[name:contains]=Doe` | Value of field `name` contains `Doe`. |
| startsWith | `filter[name:startsWith]=Jane` | Value of field `name` ends with `Jane`. |
| endsWith | `filter[name:endsWith]=Doe` | Value of field `name` ends with `Doe`. |




### Sorting results

Setting a sorting is possible through setting a `order` query parameter. Sorting both ascdending and descending is possible.

#### Sorts by field `created_at`, use default sort direction (`desc`):
```bash
curl -gX "GET" http://localhost:50080/api/players/?order[created_at]

[
    {
        "name": "John Doe",
        "username": "john.doe",
        "created_at": "2018-01-02T13:24:40+00:00",
    },
    {
        "name": "Jane Doe",
        "username": "jane.doe",
        "created_at": "2018-01-02T13:24:19+00:00",
    }
]
```

#### Sorts by field `created_at`, use ascending as sort direction (`asc`):
```bash
curl -gX "GET" http://localhost:50080/api/players/?order[created_at]=asc

[
    {
        "name": "Jane Doe",
        "username": "jane.doe",
        "created_at": "2018-01-02T13:24:19+00:00",
    },
    {
        "name": "John Doe",
        "username": "john.doe",
        "created_at": "2018-01-02T13:24:40+00:00",
    }
]
```

### Paginating results

Paginating through results is possible by using the `limit` and `offset` query parameters.

#### Shows the first result:
```bash
curl -gX "GET" http://localhost:50080/api/players/?limit=1

[
    {
        "name": "Jane Doe",
        "username": "jane.doe",
        "created_at": "2018-01-02T13:24:19+00:00",
    }
]
```

#### Shows the second result:
```bash
curl -gX "GET" http://localhost:50080/api/players/?limit=1&offset=1

[
    {
        "name": "John Doe",
        "username": "john.doe",
        "created_at": "2018-01-02T13:24:40+00:00",
    }
]
```

---

## Demo

To run the demo we need to enable the demo integration, you can do so by using the built-in make command `make enable-demo` for your local dev environment or executing the appropriate console command (`php bin/console yay:integration:enable demo integration/demo`), we choose the former.

```console
$ make enable-demo

[OK] Integration "default" enabled
[OK] Integration "demo" enabled
```

Now we can run the application.

```console
$ make start
# ...
# http://localhost:50080
```

1\) Make a request to [localhost:50080/api/players](http://localhost:50080/api/players).
```bash
curl -X "GET" http://localhost:50080/api/players/

[]
```

There are no users created yet, let's create a user by executing the following request.
```bash
curl -X "POST" http://localhost:50080/api/players/ \
    -d "{\"name\": \"Jane Doe\",\"username\":\"jane.doe\",\"email\": \"jane.doe@example.org\",\"image_url\":\"https://api.adorable.io/avatars/128/354\"}"

{
    "name": "Jane Doe",
    "username": "jane.doe",
    "image_url": "https://api.adorable.io/avatars/128/354",
    "score": 0,
    "links": {
        "self": "http://localhost:50080/api/players/jane.doe/",
        "personal_achievements": "http://localhost:50080/api/players/jane.doe/personal-achievements/",
        "personal_actions": "http://localhost:50080/api/players/jane.doe/personal-actions/"
    }
}
```

2\) Make a request to [localhost:50080/api/actions](http://localhost:50080/api/actions). Here you can see a list of available actions a user can perform. Let us pick`demo-action-01`.
```bash
curl -X "GET" http://localhost:50080/api/actions/

[
    {
        "name": "demo-action-01",
        "label": "Accusamus molestias eum libero ullam libero.",
        "links": {
            "self": "http://localhost:50080/api/actions/demo-action-01/"
        }
    }
]
```

3\) Make a request to [localhost:50080/api/achievements](http://localhost:50080/api/achievements).
Here you can see a list of available achievements, in this demo you can earn both achievements by
performing 5x the action `demo-action-01` for the first achievement and then 10x the
action `demo-action-01` for the second achievement.

```bash
curl -X "GET" http://localhost:50080/api/achievements/

[
    {
        "name": "demo-achievement-01",
        "label": "Facere quibusdam iure voluptas velit sapiente.",
        "points": 50,
        "links": {
            "self": "http://localhost:50080/api/achievements/demo-achievement-01/",
            "actions": [
                "http://localhost:50080/api/actions/demo-action-01/"
            ]
        }
    },
    {
        "name": "demo-achievement-02",
        "label": "Nulla soluta iusto recusandae est veritatis nesciunt sequi.",
        "points": 50,
        "links": {
            "self": "http://localhost:50080/api/achievements/demo-achievement-02/",
            "actions": [
                "http://localhost:50080/api/actions/demo-action-01/"
            ]
        }
    }
]
```

4\) Let our player `jane.doe` perform the action `demo-action-01` one time.
```bash
curl -X "POST" http://localhost:50080/api/progress/ \
    -d "{\"username\":\"jane.doe\",\"action\":\"demo-action-01\"}"

[]
```

No achievment were granted yet, although if you check now our players personal actions you will find our recent action.
```bash
curl -X "GET" http://localhost:50080/api/players/jane.doe/personal-actions

[
    {
        "name": "demo-action-01",
        "label": "demo-action-01",
        "description": "demo-action-01",
        "achieved_at": "2017-10-08T12:48:13+0000",
        "links": {
            "self": "http://localhost:50080/api/players/jane.doe/personal-actions/",
            "player": "http://localhost:50080/api/players/jane.doe/",
            "action": "http://localhost:50080/api/actions/demo-action-01/"
        }
    }
]
```

Now let us get our first achievement. For this, perform four more times our `demo.action` action.
```bash
curl -X "POST" http://localhost:50080/api/progress/ \
    -d "{\"username\":\"jane.doe\",\"actions\":[\"demo-action-01\",\"demo-action-01\",\"demo-action-01\",\"demo-action-01\"]}"

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
Et voilà, we earned our first achievement!

5\) Now let us go further, Let the player perform five times more the `demo-action-01` action.
```bash
curl -X "POST" http://localhost:50080/api/progress/ \
    -d "{\"username\":\"jane.doe\",\"actions\":[\"demo-action-01\",\"demo-action-01\",\"demo-action-01\",\"demo-action-01\",\"demo-action-01\",\"demo-action-01\"]}"

[
    {
        "name": "demo-achievement-02",
        "label": "demo-achievement-02",
        "description": "demo-achievement-02",
        "points": 50,
        "achieved_at": "2017-10-08T13:23:53+0000",
        "links": {
            "self": "http://localhost:50080/api/players/jane.doe/personal-achievements/",
            "player": "http://localhost:50080/api/players/jane.doe/",
            "achievement": "http://localhost:50080/api/achievements/demo-achievement-02/"
        }
    }
]⏎
```

Clap, clap! Our player earned now his official second achievemennt.


6\) Let's check now our player achievements by performing the following request.
```bash
curl -X "GET" http://localhost:50080/api/players/jane.doe/personal-achievements/

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
    },
    {
        "name": "demo-achievement-02",
        "label": "demo-achievement-02",
        "description": "demo-achievement-02",
        "points": 50,
        "achieved_at": "2017-10-08T13:23:53+0000",
        "links": {
            "self": "http://localhost:50080/api/players/jane.doe/personal-achievements/",
            "player": "http://localhost:50080/api/players/jane.doe/",
            "achievement": "http://localhost:50080/api/achievements/demo-achievement-02/"
        }
    }
]
```

And that's all for the demo. Yay!

