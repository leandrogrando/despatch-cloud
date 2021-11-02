# Despatch cloud code challenge

I decided to create a project in Laravel to solve the proposed challenge.

In the project I created the database using Laravel migrations, as well as the models and relationships.
The synchronization of the orders happens through the use of cron jobs and the Laravel queue. It all starts with the SyncOrdersCommand which is executed by the cron job every minute. This command queues up a job called SyncOrders.
The SyncOrders job fetches the orders from the ID of the last order stored in the database. For each order fetched, a SyncOrder job is sent to the queue. It is the SyncOrder job that enters the order data into the database and updates the order type to approved.

For the jobs I used a RateLimited middleware, which prevents more than 30 jobs from running per minute, to respect the API limit.

I also used a package to generate logs of each request/response made by the laravel HTTP client. The logs can be checked by accessing the storage/logs/ folder. The package used also accepts other drivers to generate other log formats besides files if desired.


## Running the project

1. Clone the project to a folder
2. Enter the folder and run ``composer install``
3. Copy the .env.example file and rename it to .env, then set the credentials of a mysql database in this file.
4. Run ```php artisan migrate``` to generete the database structure.
5. In a terminate, left running the command ```php artisan schedule:work``` to run the cron job. (Or set up the cron job the traditional way).
6. In another terminate I left running the command ```php artisan queue:work``` so that the jobs in the queue are run.
7. Monitor the synchronization of the requests as the tables in the database are filled.
