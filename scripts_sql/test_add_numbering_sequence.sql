SET @row := 0;
SELECT @row := @row + 1 as row, t.*
FROM games as t, (SELECT @row := 0) as r
ORDER BY t.genre_id;