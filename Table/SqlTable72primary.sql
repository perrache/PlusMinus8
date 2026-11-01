select s.nspname as snspname,
       s.oid     as noid,
       u.usename as susename
from pg_catalog.pg_namespace s
         join pg_catalog.pg_user u on u.usesysid = s.nspowner
order by snspname
