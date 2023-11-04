# usermanagement

1. installing Postgre
2. Setup XAMPP
3. setup index.php
4. setup function
    1. connect to db
    2. setup validation
    3. setup CRUD
5. setup usertests
    1. setup composer + phpunit
    2. write tests
6. setup end-to-end tests
    1. Plugin Playwright VSCode
    2. write tests

#### Known Bugs? 
- Password still set on new after create/update?
- PHPUnit needs SQLite Connection resulting in "could not find driver" on phpunit tests

#### thoughts

- Nutzer erst anlegen wenn E-Mail bestätigt wurde bzw. Passwort gesetzt
- charset sicherheitshalber mit übergeben
- löschen Safetycheck? / FK-Constraint -> stattdessen deaktivieren?
- Passwort identisch
- versch. Tests, vorallem max 3 pro Sort ggf? Unit-Test, End-To-End
- Man sollte vermutlich das "errorhandling" von "Fehlermeldung + Alle Ergebnisse" umstellen


#### Tests
- Unittests
    - Create
        - Success
        - Missing Data (Frontend checks this)
        - Wrong Data
        - Existing Account
        - 
    - Read
    - Update
        - Success
        - no user found
        - no id supplied
        - Existing User with new Email
        - Email faulty
        - Email missing
        - Success with missing values
    - Delete
        - Success
        - no id
        - no user found

- End-To-End-Tests
    - CRUD
    - CreateExistingMail
    - UpdateExistingMail