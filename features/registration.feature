Feature: Registration

        Scenario: Access to registration form
            Given an anonymous user
             When he browses "/register"
             Then he sould see a form

        Scenario: Registration with valid data in the registration form
            Given an anonymous user
             When he browses "/register"
              And he fills in the form with email "lior@mail.com", username "Liorozore" and password "j4ckas$"
             Then he should be redirected to "/"
              And we should find his data in the database
              And his password should have been hashed
