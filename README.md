#What you need

##A sqlite 3 database

```
sqlite3 database.sqlite
```

##A table called expenses
```
CREATE TABLE expenses(id INT PRIMARY KEY, amount REAL, date TEXT, tags TEXT, imported INT)
```

##And a .csv with your expenses
The .csv has to have the first line like

```
date,tag,amount
```

###Where
- `date` is in the form of `YYYY-mm-dd`
- `tag` is a comma separated list `onetag,othertag,someothertag`
- `amount` is a float value
