select r.name                                    stransaction,
       o.name || ' / ' || a.name                 saccount,
       to_char(sum(m.value), '999 999 990"."99') nvalue,
       count(*)                                  nile,
       m.comment                                 scomment
from minus m
         join transaction r on r.id = m.transaction_id
         join account a on a.id = m.account_id
         join organization o on o.id = a.organization_id
         join currency c on c.id = a.currency_id
group by stransaction, saccount, scomment
order by scomment, stransaction, saccount
