SELECT distinct a.author_name
FROM isometr1_keyboard.authors as a
JOIN isometr1_keyboard.contribs_games as c
where a.author_id = c.author_id;
