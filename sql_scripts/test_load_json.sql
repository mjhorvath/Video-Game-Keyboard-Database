CREATE TABLE employees(id INT PRIMARY KEY, name VARCHAR(45), age INT);
INSERT INTO employees(id, name, age) VALUES (1,'John', 34);
INSERT INTO employees(id, name, age) VALUES (2,'Mary', 40);
INSERT INTO employees(id, name, age) VALUES (3,'Mike', 44);

SET @jsonempl=(SELECT JSON_ARRAYAGG(JSON_OBJECT("id", id, "name", name, "age", age)) FROM employees);

SELECT JSON_PRETTY(@jsonempl);
