SET @abbr := 'bge', @name := 'Beige', @new_id := 7;

UPDATE test
SET test.sortorder_id = test.sortorder_id + 1
WHERE test.sortorder_id >= @new_id
ORDER BY test.sortorder_id DESC;

INSERT INTO test(keygroup_class, keygroup_color, sortorder_id)
VALUES(@abbr, @name, @new_id);
