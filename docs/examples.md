[Table of Contents](README.md) | [Getting Started](getting-started.md) | [Customisation](customisation.md) | [How To](how-to.md) | **Examples** | [Under The Hood](under-the-hood.md) | [Contributing](contributing.md)


---

# Examples

* [Usage / API](examples.md#usage--api)
* [Demo](examples.md#demo)
---

## Usage / API

Yay's API supports filtering, sorting and paginating for `GET` requests.

Automatically-generated API documentation can be found at [`http://localhost:50080/api/doc`](http://localhost:50080/api/doc), run it with `make start`. The latest stable version [`https://yay-demo.sloppy.zone/api/doc`](https://yay-demo.sloppy.zone/api/doc) is available via `sloppy.io`.

### Filtering results

Filtering for specific results is achieved by the `filter` query parameter.

#### Filter by `name`, it must equal `Alex Doe`:
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
        "name": "Alex Doe",
        "username": "alex.doe",
        "created_at": "2018-01-02T13:24:19+00:00",
    }
]
```

#### Supported filter methods:

The filtering uses a specific syntax to enable different types of matching strategies: `filter[$field:$expression]=$value`.

| Expression | Example | Description |
|---|---|---|
| * | `filter[name]=Jane+Doe` | Value of field `name` equals `Alex Doe`. |
| eq | `filter[name:eq]=Jane+Doe` | Value of field `name` equals `Alex Doe`. |
| gt | `filter[score:gt]=10` | Value of field `name` is greater than `10`. |
| lt | `filter[score:lt]=10` | Value of field `name` is less than `10`. |
| lte | `filter[score:lte]=10` | Value of field `name` is greater than or equals `10`. |
| gte | `filter[score:gte]=10` | Value of field `name` is less than or equals `10`. |
| neq | `filter[name:neq]=Jane+Doe` | Value of field `name` does not equal `Alex Doe`. |
| contains | `filter[name:contains]=Doe` | Value of field `name` contains `Doe`. |
| startsWith | `filter[name:startsWith]=Jane` | Value of field `name` ends with `Jane`. |
| endsWith | `filter[name:endsWith]=Doe` | Value of field `name` ends with `Doe`. |




### Sorting results

Setting a sorting is possible through setting a `order` query parameter. Sorting both ascending and descending is possible.

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
        "name": "Alex Doe",
        "username": "alex.doe",
        "created_at": "2018-01-02T13:24:19+00:00",
    }
]
```

#### Sorts by field `created_at`, use ascending as sort direction (`asc`):
```bash
curl -gX "GET" http://localhost:50080/api/players/?order[created_at]=asc

[
    {
        "name": "Alex Doe",
        "username": "alex.doe",
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
        "name": "Alex Doe",
        "username": "alex.doe",
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

To run the demo we need to enable the demo integration. You can do so by using the built-in make command `make enable-demo` for your local dev environment or executing the appropriate console command (`php bin/console yay:integration:enable demo integration/demo`), we choose the former.

```bash
$ make enable-demo

[OK] Integration "default" enabled
[OK] Integration "demo" enabled
```

Now we can run the application.

```bash
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
curl -X POST http://localhost:50080/api/players/ \
    -d "{\"name\": \"Alex Doe\",\"username\":\"alex.doe\",\"email\": \"alex.doe@example.org\",\"image_url\":\"https://avatars.dicebear.com/v2/female/354.svg\"}"

{
    "name": "Alex Doe",
    "username": "alex.doe",
    "image_url": "https://avatars.dicebear.com/v2/female/354.svg",
    "score": 0,
    "links": {
        "self": "http://localhost:50080/api/players/alex.doe/",
        "personal_achievements": "http://localhost:50080/api/players/alex.doe/personal-achievements/",
        "personal_actions": "http://localhost:50080/api/players/alex.doe/personal-actions/"
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

4\) Let our player `alex.doe` perform the action `demo-action-01` one time.
```bash
curl -X POST http://localhost:50080/api/progress/ \
    -d "{\"username\":\"alex.doe\",\"action\":\"demo-action-01\"}"

[]
```

No achievements were granted yet, although if you check now our players personal actions you will find our recent action.
```bash
curl -X "GET" http://localhost:50080/api/players/alex.doe/personal-actions/

[
    {
        "name": "demo-action-01",
        "label": "demo-action-01",
        "description": "demo-action-01",
        "achieved_at": "2017-10-08T12:48:13+0000",
        "links": {
            "self": "http://localhost:50080/api/players/alex.doe/personal-actions/",
            "player": "http://localhost:50080/api/players/alex.doe/",
            "action": "http://localhost:50080/api/actions/demo-action-01/"
        }
    }
]
```

If you're curious to see how far our player progress towards an achievement, you can query transient achievements.
```bash
curl -X "GET" http://localhost:50080/api/players/alex.doe/transient-achievements/

[
    {
        "achievement": "demo-achievement-01",
        "progress": 20,
        "points": 50,
        "links": {
            "self": "http://localhost:50080/api/players/alex.doe/transient-actions/",
            "player": "http://localhost:50080/api/players/alex.doe/",
            "achievement": "http://localhost:50080/api/achievements/demo-achievement-01/"
        }
    }
]
```


Now let us get our first achievement. For this, perform the `demo.action` action four more times.

```bash
curl -X POST http://localhost:50080/api/progress/ \
    -d "{\"username\":\"alex.doe\",\"actions\":[\"demo-action-01\",\"demo-action-01\",\"demo-action-01\",\"demo-action-01\"]}"

[
    {
        "name": "demo-achievement-01",
        "label": "demo-achievement-01",
        "description": "demo-achievement-01",
        "points": 50,
        "achieved_at": "2017-10-08T13:22:08+0000",
        "links": {
            "self": "http://localhost:50080/api/players/alex.doe/personal-achievements/",
            "player": "http://localhost:50080/api/players/alex.doe/",
            "achievement": "http://localhost:50080/api/achievements/demo-achievement-01/"
        }
    }
]
```
Et voilà: we earned our first achievement!

5\) Now let's go further. Let the player perform the `demo-action-01` action five more times.
```bash
curl -X POST http://localhost:50080/api/progress/ \
    -d "{\"username\":\"alex.doe\",\"actions\":[\"demo-action-01\",\"demo-action-01\",\"demo-action-01\",\"demo-action-01\",\"demo-action-01\",\"demo-action-01\"]}"

[
    {
        "name": "demo-achievement-02",
        "label": "demo-achievement-02",
        "description": "demo-achievement-02",
        "points": 50,
        "achieved_at": "2017-10-08T13:23:53+0000",
        "links": {
            "self": "http://localhost:50080/api/players/alex.doe/personal-achievements/",
            "player": "http://localhost:50080/api/players/alex.doe/",
            "achievement": "http://localhost:50080/api/achievements/demo-achievement-02/"
        }
    }
]⏎
```

Clap, clap! Our player earned their second official achievement.

6\) Let's check our player achievements by performing the following request.
```bash
curl -X "GET" http://localhost:50080/api/players/alex.doe/personal-achievements/

[
    {
        "name": "demo-achievement-01",
        "label": "demo-achievement-01",
        "description": "demo-achievement-01",
        "points": 50,
        "achieved_at": "2017-10-08T13:22:08+0000",
        "links": {
            "self": "http://localhost:50080/api/players/alex.doe/personal-achievements/",
            "player": "http://localhost:50080/api/players/alex.doe/",
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
            "self": "http://localhost:50080/api/players/alex.doe/personal-achievements/",
            "player": "http://localhost:50080/api/players/alex.doe/",
            "achievement": "http://localhost:50080/api/achievements/demo-achievement-02/"
        }
    }
]
```

And that's all for the demo. Yay!

