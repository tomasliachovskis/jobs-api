# Instruction

Instructions on building and running the app, as well as basic commands for testing functionality.

## Setup the project

```bash
$ cp .env.example .env
$ docker-compose up -d
$ docker-compose exec app composer install
$ docker-compose exec app php artisan cache:clear
$ docker-compose exec app php artisan queue:listen
```

After completing the steps, you should be able to access the application via http://127.0.0.1:8080

## API EndPoints

##### Create Job
* POST `http://localhost:8000/api/jobs`

Request example:

```
curl --location 'http://127.0.0.1:8080/api/jobs' \
--header 'Content-Type: application/json' \
--data '{
"urls": [
"https://www.php.net",
"https://www.php.net/releases/8.3/en.php"
],
"selectors": [
"h1",
"h2",
"h3"
]
}'
```

Response example: 

```
{"job_id":"job_6723a21f7b2f6"}
```

##### Get job details
* GET `http://localhost:8000/api/jobs/{id}`

Response example:

```
{
    "id": "job_6723a21f7b2f6",
    "status": "Completed",
    "urls": [
        "https://www.php.net",
        "https://www.php.net/releases/8.3/en.php"
    ],
    "selectors": [
        "p",
        "h1",
        "h2",
        "h3"
    ],
    "scraped_data": [
        {
            "url": "https://www.php.net",
            "success": true,
            "scraped_data": [
                {
                    "selector": "p",
                    "values": [
                        "A popular general-purpose scripting language that is especially suited to web development.Fast, flexible and pragmatic, PHP powers everything from your blog to the most popular websites in the world.",
                        "The PHP team is pleased to announce the release of PHP 8.4.0, RC3. This is the third release candidate, continuing the PHP 8.4 release cycle, the rough outline of which is specified in the PHP Wiki.",
                        "For source downloads of PHP 8.4.0, RC3 please visit the download page.",
                        "Please carefully test this version and report any issues found in the bug reporting system.",
                        "Please DO NOT use this version in production, it is an early test version.",
                        "For more information on the new features and other changes, you can read the NEWS file or the UPGRADING file for a complete list of upgrading notes. These files can also be found in the release archive.",
                        "Special Thanks"
                    ]
                },
                {
                    "selector": "h1",
                    "values": []
                },
                {
                    "selector": "h2",
                    "values": [
                        "PHP 8.4.0 RC3 available for testing",
                        "PHP 8.2.25 Released!",
                        "PHP 8.3.13 Released!",
                        "PHP 8.4.0 RC2 available for testing",
                        "PHP 8.1.30 Released!",
                    ]
                },
                {
                    "selector": "h3",
                    "values": []
                }
            ]
        },
        {
            "url": "https://www.php.net/releases/8.3/en.php",
            "success": true,
            "scraped_data": [
                {
                    "selector": "p",
                    "values": [
                        "Due to the limited precision and implicit rounding of floating point numbers, generating an unbiased float lying within a specific interval is non-trivial and the commonly used userland solutions may generate biased results or numbers outside the requested range.",
                        "The Randomizer was also extended with two methods to generate random floats in an unbiased fashion. The Randomizer::getFloat() method uses the γ-section algorithm that was published in Drawing Random Floating-Point Numbers from an Interval. Frédéric Goualard, ACM Trans. Model. Comput. Simul., 32:3, 2022.",
                    ]
                },
                {
                    "selector": "h1",
                    "values": []
                },
                {
                    "selector": "h2",
                    "values": [
                        "Typed class constants RFC",
                        "Dynamic class constant fetch RFC",
                        "New #[\\Override] attribute RFC",
                        "Deep-cloning of readonly properties RFC",
                        "New json_validate() function RFC Doc",
                    ]
                },
                {
                    "selector": "h3",
                    "values": []
                }
            ]
        }
    ],
    "created_at": "2024-10-31T15:28:31.504592Z",
    "scraped_at": "2024-10-31T15:30:48.168875Z"
}
```

##### Delete job
* DELETE `http://localhost:8000/api/{id}`

Response example:

```
{
    "message": "Job deleted successfully"
}
```

