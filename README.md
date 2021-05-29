# PennState-Class-Scheduler
SWENG 411 group project repository

## Getting the project installed and running locally:

### Installing the project's dependencies 
- [Download Composer](https://getcomposer.org/) (PHP Package Manager) if you don't already have it on your machine 
- Run `composer install` from the command line in the project directory 
- In order to configure the front end assets, React, Tailwind, Etc. you will need to have NPM on your machine, it can be be [found here](https://www.npmjs.com/)
- Once you have NPM installed, run the following commands in the project terminal: `npm install` followed by `npm run dev`

### Running the project locally
- There are several options shown in the [Laravel Documentation](https://laravel.com/docs/8.x/installation#your-first-laravel-project) on how to do this. I personally am on a Mac and use a first party Laravel application called [Laravel Valet](https://laravel.com/docs/8.x/valet), but feel free to use what you're most comfortable with. 

### Connecting to the Database and Migrating 
- One of the key advantages of using the Laravel is the tremendous how-to videos on the Framework that can be found at Laracasts.com. Many of these tutorials, videos, etc. are completely free, including [this one](https://laracasts.com/series/laravel-8-from-scratch/episodes/17), which walks you through setting up your database connection in the app. The DB portion starts at about the 2:00 mark in the video but it may be worth watching the first two minutes if you haven't use `.env` (environment files) before as they are used in many frameworks nowadays.  
- I recommend using the default mysql connection setup shown in the video as opposed to sqlite or postgres 
- Now that the app is connected to a database, you can run the default [migration files](https://laravel.com/docs/8.x/migrations) with the `php artisan migrate` command
