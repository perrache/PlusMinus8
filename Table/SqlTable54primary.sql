select o.name || ' / ' || a.name                 saccount,
       k.name                                    skind,
       k.name || ' / ' || t.name                 stype,
       to_char(m.dat, 'YYYY/MM')                 smies,
       to_char(sum(m.value), '999 999 990"."99') nvalue
from minus m
         join type t on t.id = m.type_id
         join kind k on k.id = t.kind_id
         join account a on a.id = m.account_id
         join organization o on o.id = a.organization_id
         join currency c on c.id = a.currency_id
where c.code = 'PLN'
group by rollup (saccount, skind, stype, smies)
order by saccount nulls last, skind nulls last, stype nulls last, smies desc nulls last
