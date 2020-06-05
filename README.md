## LEPP-RabbitMQ-ELK
This is a complete stack for running Symfony 5 (Last version) into Docker containers using docker-compose tool.

### Cluster Architecture
In this cluster architecture all tiers of the Web application are deployed to a single Docker container.

![Cluster architecture](https://user-images.githubusercontent.com/11296140/83842636-50c03f80-a6fb-11ea-9e42-75ab3ec57be4.png)

### How it works?
Here are the docker-compose built images:
- db: This is the PostgreSQL database container (can be changed to MySQL or whatever in docker-compose.yml file),
- php: This is the PHP-FPM container including the application volume mounted on,
- nginx: This is the Nginx Webserver container in which php volumes are mounted too.
- rabbitmq: This is the RabbitMQ container that transmits the application message to the work (Task: indexation of the entity Book::class in Elasticsearch).
- elasticsearch: This is the Elasticsearch server used to store our Web server and application logs,
- logstash: This is the Logstash tool from Elastic Stack that allows to read logs and send them into our Elasticsearch server,
- kibana: This is the Kibana UI that is used to render logs and create beautiful dashboards.

### Installation
First, clone this repository:

$ git clone https://github.com/ali-touil/LEPP-RabbitMQ-ELK.git

Then, run:

```bash
$ docker-compose up -d
```

You are done, you can visit your Symfony application on the following URL: http://symfony.localhost (access RabbitMQ on http://localhost:15672 and access Kibana on http://symfony.localhost:81)

Note : you can rebuild all Docker images by running:

```bash
$ docker-compose up -d --build
```

Next, run the migration to add the table to your database:

```bash
$ docker exec php bin/console doctrine:migrations:migrate --no-interaction --allow-no-migration
```

### Read logs
You can access Nginx and Symfony application logs in the following directories on your host machine:
- logs/nginx
- logs/symfony

### RabbitMQ
You can use RabbitMQ to manage exchanges and queues the by visiting http://localhost:15672.

### Kibana
You can also use Kibana to visualize Nginx & Symfony logs by visiting http://symfony.localhost:81.

### Xdebug
Configure your IDE to use port 5902 for XDebug. Docker versions below 19.03.8 don't support the Docker variable host.docker.internal.

In that case you'd have to swap out host.docker.internal with your machine IP address in php-fpm/xdebug.ini.

### Status
- Developing

### Contributing
Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

### License
[MIT](https://choosealicense.com/licenses/mit/) license. Copyright (c) 2020.