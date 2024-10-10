# Vaccination Registration System

This project is a web-based application where users can register for a vaccination, select a vaccine center, and get scheduled for a vaccination date. The system ensures that vaccine appointments are managed using a first-come first-served strategy, and notifications are sent to users before their scheduled appointment. It also includes a public search page to check the registration status of users.

## Features

- **User Registration**: 
  - Users can register by providing necessary details.
  - They must select a vaccine center during registration.
  - Registration is allowed only once per user.
  
- **Vaccine Center Management**: 
  - The system supports multiple vaccine centers with varying daily user limits.
  - Centers are pre-populated using seeder.
  
- **Vaccination Scheduling**: 
  - Vaccination appointments are scheduled for weekdays (Sunday to Thursday).
  - Users are assigned to a date based on daily limit of the specified vaccine center and first-come first-served policy.
  
- **Notification System**: 
  - Users receive an email notification at 9 PM on the day before their scheduled vaccination date.
  
- **Public Search**: 
  - Users can enter their NID to check their vaccination status.
  - Statuses include:
    - `Not registered`: Not registered yet, with a link to the registration page.
    - `Not scheduled`: Registered but not yet scheduled.
    - `Scheduled`: Vaccination date is set, and the scheduled date is shown.
    - `Vaccinated`: If the scheduled date has passed.

## Possible Optimizations

- **Database Indexing**:
  - NID field can be set as index column for faster search results.
  
- **Queue Worker**:
  - We can have multiple queue workers to process more jobs.

- **Concurrency**:
  - Laravel introduced concurrency as a new feature that we can use to run multiple independent task at the same time.
  - We can also use defer() to execute task later and send faster response without waiting for it when its applicable.
  
- **Eager Loading**:
  - Used where appropriate to reduce query counts, especially when retrieving user and vaccine center data.

- **Caching**:
  - We can cache frequently used data like the vaccine centers.

- **Sub-Query**:
  - We can use sub-query to improve the performance

## Future Considerations

- **SMS Notifications**:
  - To add SMS notifications alongside email reminders, the following changes would be required:
    - Integrate an SMS API (e.g., Twilio) into the notification system by installing required packages.
    - Configure the SMS provider with required credentials.
    - Update the notification (`VaccinationScheduledNotification`) class's via() method to include SMS alongside email.
    - Add another message representation for the sms just like `toMail()`.
  
## Installation and Setup

1. Clone the repository.
2. Run `composer install` to install dependencies.
3. Set up your `.env` file with your database credentials, mail and queue configuration.
4. Run `php artisan key:generate`.
5. Run `php artisan migrate --seed` to set up the database and populate vaccine centers.
6. Run `npm i` to install the dependencies.
7. Run `npm run build` to bundle the assets.
8. Run `php artisan queue:listen` to start queue worker and listen.
9. Add cron configuration to run the scheduler at regular interval. Example: `* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1`
10. Start the server using `php artisan serve`.

## Testing

You can test the registration and search functionality by:
- Run `php artisan test` to run the tests.
- You can manually test it by registering and checking the status of a user via the search page using the provided NID.