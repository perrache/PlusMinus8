select qkind                                                                   skind,
       qmies                                                                   smies,
       qvalue                                                                  nvalue,
       round(qpart * 100)                                                      "npart%",
       to_char(round(qperday / public.ndayinmonth(qmies)), '999 999 990"."99') "nper/day"
from (select k.name                                                                      qkind,
             to_char(m.dat, 'YYYY/MM')                                                   qmies,
             to_char(sum(m.value), '999 999 990"."99')                                   qvalue,
             sum(m.value)::numeric / last_value(sum(m.value)) over (partition by k.name) qpart,
             sum(m.value)::numeric                                                       qperday
      from minus m
               join type t on t.id = m.type_id
               join kind k on k.id = t.kind_id
               join account a on a.id = m.account_id
               join currency c on c.id = a.currency_id
      where c.code = 'PLN'
      group by rollup (qkind, qmies)) q
order by skind nulls last, smies desc nulls last
