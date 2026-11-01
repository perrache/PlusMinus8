select k.name || ' / ' || t.name                 stype,
       r.name                                    stransaction,
       to_char(sum(m.value), '999 999 990"."99') nvalue,
       count(*)                                  nile,
       m.comment                                 scomment
from minus m
         join type t on t.id = m.type_id
         join kind k on k.id = t.kind_id
         join transaction r on r.id = m.transaction_id
group by stype, stransaction, scomment
order by scomment, stype, stransaction
