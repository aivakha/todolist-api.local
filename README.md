# Start

1. Run command to create user: `php artisan user:create` and copy API token.
2. Run command to seed DB: `php artisan db:seed`.

# Endpoints (require auth token)

****TASK INDEX****


    GET api/v1/tasks

*QueryParams:*

- **search** `string` `optional`
- **status** `string|integer(todo; done)` `optional`
- **priorityFrom** `integer(1-5)` `optional`
- **priorityTo** `integer(1-5)` `optional`
- **sortBy** `string(priority; completedAt; createdAt)` `optional`
- **orderBy** `string(asc; desc)` `optional`
- **perPage** `integer` `optional`
- **withSubtasks** `boolean(true; false)` `optional` (to load relation)


****TASK CREATE****


    POST api/v1/tasks

*Body:*

    {
    "title": "Task Title",
    "description": "Task Description",
    "priority": "1",
    "parentId": "2",
    "status": "0"
    }

- **title** `string` `required`
- **description** `string` `required`
- **status** `string|integer(0; 1)` `optional`
- **priority** `integer(1-5)` `required`
- **parentId** `null` `integer` `optional`
- **completedAt** `date` `optional`

****TASK UPDATE****


    PUT api/v1/tasks/{id}

*Body:*

    {
    "title": "Task Title Update",
    "description": "Task Description Update",
    "priority": "0",
    "parentId": null,
    "status": "0"
    }

- **title** `string` `required`
- **description** `string` `required`
- **status** `string|integer(0; 1)` `optional`
- **priority** `integer(1-5)` `required`
- **parentId** `null` `integer` `optional`
- **completedAt** `date` `optional`


****TASK DELETE****

    DELETE api/v1/tasks/{id}
   
