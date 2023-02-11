# Docker Symfony Demo

### Get Started

1. Add the IMDB API key that is referenced on the email to the app/.env file (line 30).
2. To start the app, run this command from the root directory of the project:
````
docker compose up -d --build
````
For more info on the docker environment see bellow.

3. The app should now be running at: http://localhost:8080/

### Environment

To make the app portable and easy to setup, I am using docker to containerise each aspect of the environment
There are 3 containers, nginx (nginx-service), php (php81-service) & mysql (mysql8-service).
These containers use ports 8080 9000 & 4206, so if any of these ports are already in use you will have to stop the services running on them to start the app.

You can start a bash session inside of the php container (for running tests etc) by running the following command:
````
docker exec -it php81-container bash
````

### Code Structure

All code that is specific to the IMDB API is seperated out into its own service (app/src/Service/ImdbService) and implments from the MoviesApiinterface.
The MoviesApiinterface is injected into the MoviesController, meaning that if the API had to be changed I wouldnt have to edit the controller I would just have to change the service injected from app/config/services.yaml to another service that implments the same interface).

### Functionality

The home page of the app displays all of the movies that are saved in the database and has a search bar at the top for adding new movies.
When a user searches for a movie, the app first checks if a movie already exists in the database with the same title, if so it will return that instead of sending a new request to the API.
If the movie does not exists in the database then the API will be called.

There is also a GET endpoint for retrieving all the saved movies.
Once the app is running, you can access the endpoint here: http://localhost:8080/get

### Tests

To test that IMDB API is returning valid results & that the logic for sorting movies is working, I have added a unit test for the ImdbService getMovies method.
This test is found at app/tests/ImdbServiceTest. To run this please start a bash session in the php81 container and follow the PHPUnit documentation.
