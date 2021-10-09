# itksites

```sh
docker-compose up -d
docker-compose exec phpfpm composer install
bin/console doctrine:migrations:migrate --no-interaction
```

Update server data:

```sh
bin/console app:server:data
```

Get websites on servers:

```sh
bin/console app:website:get
```

Detect (guess) the type and version of each website:

```sh
bin/console app:website:detect
```

Get data for each website:

```sh
bin/console app:website:data
```

Get updates data for each website:

```sh
bin/console app:website:updates
```

## API

Regexp

`https://127.0.0.1:8000/api/websites.jsonld?regexp_type=dru|pal`
