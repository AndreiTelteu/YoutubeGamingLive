
## Docker usage

```bash

# start
docker-compose up -d

# install dependencies
docker-compose exec php su app -c 'composer install'

# run migrations and seeders
docker-compose exec php su app -c 'php artisan migrate:fresh --seed'

# make storage link
docker-compose exec nginx su app -c 'cd /app/public/; ln -s ../storage/app/public storage'

# exec inside the php container
docker-compose exec php su app

```
