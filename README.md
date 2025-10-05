# Order Service (Symfony 6.4)

A Symfony microservice that manages orders and consumes events from the Product microservice.

## Prerequisites
- PHP 8.x
- Composer
- Symfony CLI (optional)
- A message broker (e.g. RabbitMQ, Redis) configured for Symfony Messenger
- Database supported by Doctrine (e.g. MySQL, PostgreSQL)
- (Optional) Docker & Docker Compose if you prefer containerized setup

## Quick overview
- Responsibilities:
  - Create and manage orders
  - Consume product response events (ProductReservedEvent, ProductOutOfStockEvent)
  - Update order status based on product events
- Event handling:
  - ProductReservedEvent → sets order status to `Reserved`
  - ProductOutOfStockEvent → sets order status to `OutOfStock`

## Important notes before starting
- If running in containers, services must use service names (not localhost) to connect.
- Ensure PHP container/image includes required PDO and ext packages for your DB.
- Ensure Messenger transport DSN (MESSENGER_TRANSPORT_DSN) is set and reachable.

## Installation & setup

1. Clone project
   ```sh
   git clone https://github.com/arman424/order-service
   cd order_service
   ```

2. Copy/inspect environment
   ```sh
   cp .env.example .env
   ```

3. Install PHP dependencies:
   ```sh
   composer install
   ```

4. Prepare the database:
   ```sh
   php bin/console doctrine:database:create --if-not-exists
   php bin/console doctrine:migrations:migrate --no-interaction
   ```

## Consume Product Events
To start a consumer that listens to product events run (interactive):
```sh
php bin/console messenger:consume product_events
```
