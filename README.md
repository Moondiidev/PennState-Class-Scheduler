# PennState-Class-Scheduler
Penn State Software Enginnering Bachelor's Degree Course Recomendation Tool

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
- After running the migration you can populate the database with the course content, users, semesters, etc. by running `php artisan db:seed`

### Add the `.env` file and Generate the local app key
- In order for the app to function properly it needs to have a `.env` (environment) file (more info on environment files can be [found here](https://dev.to/jakewitcher/using-env-files-for-environment-variables-in-python-applications-55a1)). You will also need to generate an application key to secure the app. This will ensure that any items (like cookies) that should be encypted are encrypted. For further reading see [this blog post](https://tighten.co/blog/app-key-and-you/). 
    - First, it's important to note that the `.env` file is not committed to the repo because that file generally contains private details for the app (such as pass keys for services) that should not be in the repo. Luckily though, there is a `.env.example` file that is included in the repo which has all the values that your `.env` file will need. It has been updated from the default version to include specific environment variables for this application.
    - Next, to generate the key run the following command: `php artisan key:generate`
