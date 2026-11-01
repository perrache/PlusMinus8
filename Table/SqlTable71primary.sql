select oid          as noid,
       datname      as sdatname,
       datallowconn as sdatallowconn,
       datconnlimit as ndatconnlimit
from pg_database
order by sdatname
