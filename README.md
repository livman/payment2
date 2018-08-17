# payment2
# The project devlelop almost done but without unit test (Time)

1. Clone the project.

2. Be sure your port not conflit by default config port 7000 for app , 7070 for phpmyadmin (user: root without password).

3. Cd to project and run 'docker-compose up -d'

4. Change permission -> run 'chmod -R 777 app/storage/' , 'chmod -R 777 app/bootstrap/'

5. To migrate db -> run 'docker-compose exec app php artisan migrate'

6. launch app -> your_host:7000

7. To run unittest -> run 'docker-compose exec app ./vendor/bin/phpunit'
