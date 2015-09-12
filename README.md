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
The .csv must be named `expenses.csv` and the first line must be

```
date,tag,amount
```

All other lines must follow that schema having:
- `date` in the form of `YYYY-mm-dd`
- `tag` as a comma separated list `onetag,othertag,someothertag`
- `amount` as a float value
