select to_char(m.dat, 'YYYY/MM')                 smies,
       k.name                                    skind,
       to_char(sum(m.value), '999 999 990"."99') nvalue
from minus m
         join type t on t.id = m.type_id
         join kind k on k.id = t.kind_id
         join account a on a.id = m.account_id
         join currency c on c.id = a.currency_id
where c.code = 'PLN'
group by rollup (smies, skind)
order by smies desc nulls last, skind nulls last
