DROP TABLE IF EXISTS test;

CREATE TABLE test
LIKE keygroups_dynamic; 

INSERT INTO test
SELECT *
FROM keygroups_dynamic;
