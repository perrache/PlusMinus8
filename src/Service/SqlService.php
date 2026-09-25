<?php

namespace App\Service;

class SqlService
{
    public array $sqlArray = [
        10 => [
            'title' => 'Dictionary',
        ],
        11 => [
            'title' => 'Dictionary-Kind-Type',
            'sql1' => '
select k.name skind,
       k.id nid
from kind k
order by skind',
            'sql2' => '
select t.name stype,
       t.id nid
from type t
where t.kind_id = $1
order by stype',
            'sql3' => '',
            'sql5' => [
                1 => [
                    'name' => 'Main',
                    'sql' => '',
                ],
            ],
        ],
        12 => [
            'title' => 'Dictionary-Organization-Account',
            'sql1' => '
select o.name sorganization,
       o.id nid
from organization o
order by sorganization',
            'sql2' => '
select a.name saccount,
       c.code scode,
       a.id nid
from account a
    join currency c on c.id = a.currency_id
where a.organization_id = $1
order by saccount',
            'sql3' => '',
            'sql5' => [
                1 => [
                    'name' => 'Main',
                    'sql' => '',
                ],
            ],
        ],
        20 => [
            'title' => 'Turnover',
        ],
        21 => [
            'title' => 'Turnover-Kinds(PLN)',
            'sql1' => '
select k.name skind,
       k.id nid,
       count(*) nile,
       to_char(sum(m.value), \'999 999 990"."99\') nsuma
from minus m
    join type t on t.id = m.type_id
    join kind k on k.id = t.kind_id
    join account a on a.id = m.account_id
    join currency c on c.id = a.currency_id
where c.code = \'PLN\'
group by k.name, k.id
order by skind',
            'sql2' => '
select k.name||\' / \'||t.name stype,
       to_char(m.value, \'999 999 990"."99\') nvalue,
       to_char(m.dat, \'DD-MM-YYYY\') sdata,
       m.comment scomm
from minus m
    join type t on t.id = m.type_id
    join kind k on k.id = t.kind_id
    join account a on a.id = m.account_id
    join currency c on c.id = a.currency_id
where c.code = \'PLN\' and t.kind_id = $1
order by m.dat desc, stype, m.id desc',
            'sql3' => '',
            'sql5' => [
                1 => [
                    'name' => 'Main',
                    'sql' => '',
                ],
                2 => [
                    'name' => 'Suma/Type',
                    'sql' => '
select k.name||\' / \'||t.name stype,
       count(*) nile,
       to_char(sum(m.value), \'999 999 990"."99\') nvalue
from minus m
    join type t on t.id = m.type_id
    join kind k on k.id = t.kind_id
    join account a on a.id = m.account_id
    join currency c on c.id = a.currency_id
where c.code = \'PLN\' and t.kind_id = $1
group by k.name, t.name
order by stype',
                ],
                3 => [
                    'name' => 'Suma/Type/Comm',
                    'sql' => '
select k.name||\' / \'||t.name stype,
       m.comment scomm,
       count(*) nile,
       to_char(sum(m.value), \'999 999 990"."99\') nvalue
from minus m
    join type t on t.id = m.type_id
    join kind k on k.id = t.kind_id
    join account a on a.id = m.account_id
    join currency c on c.id = a.currency_id
where c.code = \'PLN\' and t.kind_id = $1
group by k.name, t.name, m.comment
order by stype, scomm',
                ],
            ],
        ],
        22 => [
            'title' => 'Turnover-Types(PLN)',
            'sql1' => '
select k.name||\' / \'||t.name stype,
       t.id nid,
       to_char(sum(m.value), \'999 999 990"."99\') nsuma,
       count(*) nile
from minus m
    join type t on t.id = m.type_id
    join kind k on k.id = t.kind_id
    join account a on a.id = m.account_id
    join currency c on c.id = a.currency_id
where c.code = \'PLN\'
group by k.name||\' / \'||t.name, t.id
order by stype',
            'sql2' => '
select k.name||\' / \'||t.name stype,
       to_char(m.value, \'999 999 990"."99\') nvalue,
       to_char(m.dat, \'DD-MM-YYYY\') sdata,
       m.comment scomm
from minus m
    join type t on t.id = m.type_id
    join kind k on k.id = t.kind_id
    join account a on a.id = m.account_id
    join currency c on c.id = a.currency_id
where c.code = \'PLN\' and m.type_id = $1
order by m.dat desc, stype, m.id desc',
            'sql3' => '',
            'sql5' => [
                1 => [
                    'name' => 'Main',
                    'sql' => '',
                ],
            ],
        ],
        23 => [
            'title' => 'Turnover-Accounts',
            'sql1' => '
select tab.oname||\' / \'||tab.aname saccount,
       tab.id nid,
       case c.code when \'PLN\' then \'\' else c.code end swal,
       to_char(sum(tab.bo), \'999 999 990"."99\') nbo,
       to_char(sum(tab.val), \'999 999 990"."99\') nsaldo,
       to_char(sum(tab.val+tab.lt), \'999 999 990"."99\') ndost
from
    (select o.name oname, a.name aname, a.id, a.bo, a.bo val, a.lt, a.currency_id
     from account a
         join organization o on o.id = a.organization_id
     union all
     select o.name oname, a.name aname, a.id, 0 bo, -m.value val, 0 lt, a.currency_id
     from minus m
         join account a on a.id = m.account_id
         join organization o on o.id = a.organization_id
     union all
     select o.name oname, a.name aname, a.id, 0 bo, p.value val, 0 lt, a.currency_id
     from plus p
         join account a on a.id = p.account_id
         join organization o on o.id = a.organization_id
     union all
     select o.name oname, a.name aname, a.id, 0 bo, -pm.value val, 0 lt, a.currency_id
     from move pm
         join account a on a.id = pm.accminus_id
         join organization o on o.id = a.organization_id
     union all
     select o.name oname, a.name aname, a.id, 0 bo, pm.value val, 0 lt, a.currency_id
     from move pm
         join account a on a.id = pm.accplus_id
         join organization o on o.id = a.organization_id
     ) tab
        join currency c on c.id = tab.currency_id
group by tab.oname||\' / \'||tab.aname, tab.id, c.code
order by saccount',
            'sql2' => '
select k.name||\' / \'||t.name stype,
       to_char(-m.value, \'999 999 990"."99\') nvalue,
       m.dat xdat,
       to_char(m.dat, \'DD-MM-YYYY\') sdata,
       m.comment scomm
from minus m
    join type t on t.id = m.type_id
    join kind k on k.id = t.kind_id
where m.account_id = $1
union all
select \'+\' stype,
       to_char(p.value, \'999 999 990"."99\') nvalue,
       p.dat xdat,
       to_char(p.dat, \'DD-MM-YYYY\') sdata,
       p.comment scomm
from plus p
where p.account_id = $1
union all
select \'+-\' stype,
       to_char(pm.value, \'999 999 990"."99\') nvalue,
       pm.dat xdat,
       to_char(pm.dat, \'DD-MM-YYYY\') sdata,
       pm.comment scomm
from move pm
where pm.accplus_id = $1
union all
select \'+-\' stype,
       to_char(-pm.value, \'999 999 990"."99\') nvalue,
       pm.dat xdat,
       to_char(pm.dat, \'DD-MM-YYYY\') sdata,
       pm.comment scomm
from move pm
where pm.accminus_id = $1
order by xdat desc, stype',
            'sql3' => '',
            'sql5' => [
                1 => [
                    'name' => 'Main',
                    'sql' => '',
                ],
            ],
        ],
        24 => [
            'title' => 'Turnover-Transactions(PLN)',
            'sql1' => '
select t.name stransaction,
       t.id nid,
       to_char(sum(m.value), \'999 999 990"."99\') nsuma,
       count(*) nile
from minus m
    join transaction t on t.id = m.transaction_id
    join account a on a.id = m.account_id
    join currency c on c.id = a.currency_id
where c.code = \'PLN\'
group by t.name, t.id
order by stransaction',
            'sql2' => '
select t.name stransaction,
       to_char(m.value, \'999 999 990"."99\') nvalue,
       to_char(m.dat, \'DD-MM-YYYY\') sdata,
       m.comment scomm
from minus m
    join transaction t on t.id = m.transaction_id
    join account a on a.id = m.account_id
    join currency c on c.id = a.currency_id
where c.code = \'PLN\' and t.id = $1
order by m.dat desc, stransaction, m.id desc',
            'sql3' => '',
            'sql5' => [
                1 => [
                    'name' => 'Main',
                    'sql' => '',
                ],
            ],
        ],
        30 => [
            'title' => 'Tables',
        ],
        31 => [
            'title' => 'Move-All',
            'sql1' => '
select oplus.name||\' / \'||aplus.name saccountplus,
       case cplus.code when \'PLN\' then \'\' else cplus.code end swalplus,
       ominus.name||\' / \'||aminus.name saccountminus,
       case cminus.code when \'PLN\' then \'\' else cminus.code end swalminus,
       to_char(m.value, \'999 999 990"."99\') nvalue,
       to_char(m.dat, \'DD-MM-YYYY\') sdata,
       m.comment scomment
from move m
    join account aplus on aplus.id = m.accplus_id
    join organization oplus on oplus.id = aplus.organization_id
    join currency cplus on cplus.id = aplus.currency_id
    join account aminus on aminus.id = m.accminus_id
    join organization ominus on ominus.id = aminus.organization_id
    join currency cminus on cminus.id = aminus.currency_id
order by m.dat desc, m.id desc',
            'sql2' => '',
            'sql3' => '',
            'sql5' => [
                1 => [
                    'name' => 'Main',
                    'sql' => '',
                ],
            ],
        ],
        32 => [
            'title' => 'Minus-All',
            'sql1' => '
select to_char(m.dat, \'YYYY/MM\') smies,
       k.name skind,
       k.name||\' / \'||t.name stype,
       r.name stransaction,
       o.name||\' / \'||a.name saccount,
       case c.code when \'PLN\' then \'\' else c.code end swal,
       to_char(m.value, \'999 999 990"."99\') nvalue,
       to_char(m.dat, \'DD-MM-YYYY\') sdata,
       m.comment scomment,
       m.refer srefer
from minus m
    join type t on t.id = m.type_id
    join kind k on k.id = t.kind_id
    join transaction r on r.id = m.transaction_id
    join account a on a.id = m.account_id
    join organization o on o.id = a.organization_id
    join currency c on c.id = a.currency_id
order by m.dat desc, m.id desc',
            'sql2' => '',
            'sql3' => 'mies',
            'sql5' => [
                1 => [
                    'name' => 'Main',
                    'sql' => '',
                ],
            ],
        ],
        33 => [
            'title' => 'Minus-Comments-Sum(PLN)',
            'sql1' => '
select m.comment scomment,
       to_char(sum(m.value), \'999 999 990"."99\') nsuma,
       count(*) nile
from minus m
    join account a on a.id = m.account_id
    join currency c on c.id = a.currency_id
where c.code = \'PLN\'
group by m.comment
order by scomment',
            'sql2' => '',
            'sql3' => '',
            'sql5' => [
                1 => [
                    'name' => 'Main',
                    'sql' => '',
                ],
            ],
        ],
        34 => [
            'title' => 'Minus-Comments-Order',
            'sql1' => '
select k.name||\' / \'||t.name stype,
       r.name stransaction,
       o.name||\' / \'||a.name saccount,
       case c.code when \'PLN\' then \'\' else c.code end swal,
       to_char(m.value, \'999 999 990"."99\') nvalue,
       to_char(m.dat, \'DD-MM-YYYY\') sdata,
       m.comment scomment
from minus m
    join type t on t.id = m.type_id
    join kind k on k.id = t.kind_id
    join transaction r on r.id = m.transaction_id
    join account a on a.id = m.account_id
    join organization o on o.id = a.organization_id
    join currency c on c.id = a.currency_id
order by scomment, m.dat desc, m.id desc',
            'sql2' => '',
            'sql3' => '',
            'sql5' => [
                1 => [
                    'name' => 'Main',
                    'sql' => '',
                ],
            ],
        ],
        35 => [
            'title' => 'Minus-Mies-Value-Order',
            'sql1' => '
select to_char(m.dat, \'YYYY/MM\') smies,
       k.name skind,
       k.name||\' / \'||t.name stype,
       r.name stransaction,
       o.name||\' / \'||a.name saccount,
       case c.code when \'PLN\' then \'\' else c.code end swal,
       to_char(m.value, \'999 999 990"."99\') nvalue,
       to_char(m.dat, \'DD-MM-YYYY\') sdata,
       m.comment scomment
from minus m
    join type t on t.id = m.type_id
    join kind k on k.id = t.kind_id
    join transaction r on r.id = m.transaction_id
    join account a on a.id = m.account_id
    join organization o on o.id = a.organization_id
    join currency c on c.id = a.currency_id
order by smies desc, m.value desc',
            'sql2' => '',
            'sql3' => '',
            'sql5' => [
                1 => [
                    'name' => 'Main',
                    'sql' => '',
                ],
            ],
        ],
        36 => [
            'title' => 'Minus-Refer-Multi',
            'sql1' => '
select to_char(m.dat, \'YYYY/MM\') smies,
       k.name skind,
       k.name||\' / \'||t.name stype,
       r.name stransaction,
       o.name||\' / \'||a.name saccount,
       case c.code when \'PLN\' then \'\' else c.code end swal,
       to_char(m.value, \'999 999 990"."99\') nvalue,
       to_char(m.dat, \'DD-MM-YYYY\') sdata,
       m.comment scomment,
       m.refer srefer
from minus m
    join type t on t.id = m.type_id
    join kind k on k.id = t.kind_id
    join transaction r on r.id = m.transaction_id
    join account a on a.id = m.account_id
    join organization o on o.id = a.organization_id
    join currency c on c.id = a.currency_id
where m.refer in
      (select refer
       from
           (select refer,
                   count(*)
            from minus
            group by refer
            having count(*) > 1) t1)
order by m.dat desc, m.refer, k.name, t.name, m.id desc',
            'sql2' => '',
            'sql3' => '',
            'sql5' => [
                1 => [
                    'name' => 'Main',
                    'sql' => '',
                ],
            ],
        ],
        37 => [
            'title' => 'Minus-Type-Transaction-Sum',
            'sql1' => '
select k.name||\' / \'||t.name stype,
       r.name stransaction,
       count(*) nile,
       to_char(sum(m.value), \'999 999 990"."99\') nvalue
from minus m
    join type t on t.id = m.type_id
    join kind k on k.id = t.kind_id
    join transaction r on r.id = m.transaction_id
group by stype, stransaction
order by stype, stransaction',
            'sql2' => '',
            'sql3' => '',
            'sql5' => [
                1 => [
                    'name' => 'Main',
                    'sql' => '',
                ],
            ],
        ],
        40 => [
            'title' => 'Report',
        ],
        41 => [
            'title' => 'Bilans(PLN)',
            'sql1' => '
select t2.mies smies,
       to_char(sum(t2.valplus), \'999 999 990"."99\') nplus,
       to_char(sum(t2.valminus), \'999 999 990"."99\') nminus,
       to_char(sum(t2.valplus - t2.valminus), \'999 999 990"."99\') nsaldo
from (
select case t1.sign when 1 then t1.val else 0 end valplus,
       case t1.sign when -1 then t1.val else 0 end valminus,
       t1.data mies
from (
select -1 sign, m.value val, to_char(m.dat, \'YYYY/MM\') data
from minus m
    join account a on a.id = m.account_id
    join currency c on c.id = a.currency_id
where c.code = \'PLN\'
union all
select 1 sign, value val, to_char(dat, \'YYYY/MM\') data
from plus p
    join account a on a.id = p.account_id
    join currency c on c.id = a.currency_id
where c.code = \'PLN\'
union all
select 1 sign, sum(bo) val, \'0000/00\' data
from account a
    join currency c on c.id = a.currency_id
where c.code = \'PLN\'
) t1
) t2
group by grouping sets ((t2.mies), ())
order by t2.mies nulls last',
            'sql2' => '',
            'sql3' => '',
            'sql5' => [
                1 => [
                    'name' => 'Main',
                    'sql' => '',
                ],
            ],
        ],
        42 => [
            'title' => 'Bottles-All',
            'sql1' => '
select smies,
       sdata,
       stype,
       to_char(sum(value), \'999 999 990"."99\') nvalue,
       scomment,
       srefer
from
    (select to_char(m.dat, \'YYYY/MM\') smies,
            k.name||\' / \'||t.name stype,
            -m.value value,
            to_char(m.dat, \'YYYY-MM-DD\') sdata,
            m.comment scomment,
            m.refer srefer
     from minus m
         join type t on t.id = m.type_id
         join kind k on k.id = t.kind_id
     where t.name = \'Butelki\'
     union all
     select to_char(p.dat, \'YYYY/MM\') smies,
            s.name stype,
            p.value,
            to_char(p.dat, \'YYYY-MM-DD\') sdata,
            p.comment scomment,
            p.refer srefer
     from plus p
         join source s on s.id = p.source_id
     where s.name = \'Butelki\') tab
group by rollup (smies, sdata, stype), scomment, srefer
order by smies desc nulls last, sdata desc nulls last, stype nulls last, scomment nulls last, srefer nulls last',
            'sql2' => '',
            'sql3' => '',
            'sql5' => [
                1 => [
                    'name' => 'Main',
                    'sql' => '',
                ],
            ],
        ],
        43 => [
            'title' => 'Bottles-Sum',
            'sql1' => '
select smies,
       sdata,
       stype,
       to_char(sum(value), \'999 999 990"."99\') nvalue
from
    (select to_char(m.dat, \'YYYY/MM\') smies,
            k.name||\' / \'||t.name stype,
            -m.value value,
            to_char(m.dat, \'YYYY-MM-DD\') sdata
     from minus m
         join type t on t.id = m.type_id
         join kind k on k.id = t.kind_id
     where t.name = \'Butelki\'
     union all
     select to_char(p.dat, \'YYYY/MM\') smies,
            s.name stype,
            p.value,
            to_char(p.dat, \'YYYY-MM-DD\') sdata
     from plus p
         join source s on s.id = p.source_id
     where s.name = \'Butelki\') tab
group by rollup (smies, sdata, stype)
order by smies desc nulls last, sdata desc nulls last, stype nulls last',
            'sql2' => '',
            'sql3' => '',
            'sql5' => [
                1 => [
                    'name' => 'Main',
                    'sql' => '',
                ],
            ],
        ],
        50 => [
            'title' => 'Rollup',
        ],
        51 => [
            'title' => 'Minus-mies,kind,type,account(PLN)',
            'sql1' => '
select to_char(m.dat, \'YYYY/MM\') smies,
       k.name skind,
       k.name||\' / \'||t.name stype,
       o.name||\' / \'||a.name saccount,
       to_char(sum(m.value), \'999 999 990"."99\') nvalue
from minus m
    join type t on t.id = m.type_id
    join kind k on k.id = t.kind_id
    join account a on a.id = m.account_id
    join organization o on o.id = a.organization_id
    join currency c on c.id = a.currency_id
where c.code = \'PLN\'
group by rollup (smies, skind, stype, saccount)
order by smies desc nulls last, skind nulls last, stype nulls last, saccount nulls last',
            'sql2' => '',
            'sql3' => '',
            'sql5' => [
                1 => [
                    'name' => 'Main',
                    'sql' => '',
                ],
            ],
        ],
        52 => [
            'title' => 'Minus-mies,account,kind,type(PLN)',
            'sql1' => '
select to_char(m.dat, \'YYYY/MM\') smies,
       o.name||\' / \'||a.name saccount,
       k.name skind,
       k.name||\' / \'||t.name stype,
       to_char(sum(m.value), \'999 999 990"."99\') nvalue
from minus m
    join type t on t.id = m.type_id
    join kind k on k.id = t.kind_id
    join account a on a.id = m.account_id
    join organization o on o.id = a.organization_id
    join currency c on c.id = a.currency_id
where c.code = \'PLN\'
group by rollup (smies, saccount, skind, stype)
order by smies desc nulls last, saccount nulls last, skind nulls last, stype nulls last',
            'sql2' => '',
            'sql3' => '',
            'sql5' => [
                1 => [
                    'name' => 'Main',
                    'sql' => '',
                ],
            ],
        ],
        53 => [
            'title' => 'Minus-kind,type,account,mies(PLN)',
            'sql1' => '
select k.name skind,
       k.name||\' / \'||t.name stype,
       o.name||\' / \'||a.name saccount,
       to_char(m.dat, \'YYYY/MM\') smies,
       to_char(sum(m.value), \'999 999 990"."99\') nvalue
from minus m
    join type t on t.id = m.type_id
    join kind k on k.id = t.kind_id
    join account a on a.id = m.account_id
    join organization o on o.id = a.organization_id
    join currency c on c.id = a.currency_id
where c.code = \'PLN\'
group by rollup (skind, stype, saccount, smies)
order by skind nulls last, stype nulls last, saccount nulls last, smies desc nulls last',
            'sql2' => '',
            'sql3' => '',
            'sql5' => [
                1 => [
                    'name' => 'Main',
                    'sql' => '',
                ],
            ],
        ],
        54 => [
            'title' => 'Minus-account,kind,type,mies(PLN)',
            'sql1' => '
select o.name||\' / \'||a.name saccount,
       k.name skind,
       k.name||\' / \'||t.name stype,
       to_char(m.dat, \'YYYY/MM\') smies,
       to_char(sum(m.value), \'999 999 990"."99\') nvalue
from minus m
    join type t on t.id = m.type_id
    join kind k on k.id = t.kind_id
    join account a on a.id = m.account_id
    join organization o on o.id = a.organization_id
    join currency c on c.id = a.currency_id
where c.code = \'PLN\'
group by rollup (saccount, skind, stype, smies)
order by saccount nulls last, skind nulls last, stype nulls last, smies desc nulls last',
            'sql2' => '',
            'sql3' => '',
            'sql5' => [
                1 => [
                    'name' => 'Main',
                    'sql' => '',
                ],
            ],
        ],
        55 => [
            'title' => 'Minus-kind,mies(PLN)',
            'sql1' => '
select qkind skind,
       qmies smies,
       qvalue nvalue,
       round(qpart * 100) "npart%",
       to_char(round(qperday / public.ndayinmonth(qmies)), \'999 999 990"."99\') "nper/day"
from (
select k.name qkind,
       to_char(m.dat, \'YYYY/MM\') qmies,
       to_char(sum(m.value), \'999 999 990"."99\') qvalue,
       sum(m.value)::numeric / last_value(sum(m.value)) over (partition by k.name) qpart,
       sum(m.value)::numeric qperday
from minus m
    join type t on t.id = m.type_id
    join kind k on k.id = t.kind_id
    join account a on a.id = m.account_id
    join currency c on c.id = a.currency_id
where c.code = \'PLN\'
group by rollup (qkind, qmies)
) q
order by skind nulls last, smies desc nulls last',
            'sql2' => '',
            'sql3' => '',
            'sql5' => [
                1 => [
                    'name' => 'Main',
                    'sql' => '',
                ],
            ],
        ],
        56 => [
            'title' => 'Minus-mies,kind(PLN)',
            'sql1' => '
select to_char(m.dat, \'YYYY/MM\') smies,
       k.name skind,
       to_char(sum(m.value), \'999 999 990"."99\') nvalue
from minus m
    join type t on t.id = m.type_id
    join kind k on k.id = t.kind_id
    join account a on a.id = m.account_id
    join currency c on c.id = a.currency_id
where c.code = \'PLN\'
group by rollup (smies, skind)
order by smies desc nulls last, skind nulls last',
            'sql2' => '',
            'sql3' => '',
            'sql5' => [
                1 => [
                    'name' => 'Main',
                    'sql' => '',
                ],
            ],
        ],
        57 => [
            'title' => 'xxx',
            'sql1' => '
select to_char(m.dat, \'YYYY/MM\') smies,
       k.name skind,
       to_char(sum(m.value), \'999 999 990"."99\') nvalue
from minus m
    join type t on t.id = m.type_id
    join kind k on k.id = t.kind_id
    join account a on a.id = m.account_id
    join currency c on c.id = a.currency_id
where c.code = \'PLN\'
group by rollup (smies, skind)
order by smies desc nulls last, skind nulls last',
            'sql2' => '',
            'sql3' => '',
            'sql5' => [
                1 => [
                    'name' => 'Main',
                    'sql' => '',
                ],
            ],
        ],
        58 => [
            'title' => 'Minus-day,kind(PLN)',
            'sql1' => '
select to_char(m.dat, \'YYYY/MM/DD\') sday,
       k.name skind,
       to_char(sum(m.value), \'999 999 990"."99\') nvalue
from minus m
    join type t on t.id = m.type_id
    join kind k on k.id = t.kind_id
    join account a on a.id = m.account_id
    join currency c on c.id = a.currency_id
where c.code = \'PLN\'
group by rollup (sday, skind)
order by sday desc nulls last, skind nulls last',
            'sql2' => '',
            'sql3' => '',
            'sql5' => [
                1 => [
                    'name' => 'Main',
                    'sql' => '',
                ],
            ],
        ],
        60 => [
            'title' => 'CorrectData',
        ],
        61 => [
            'title' => 'Type, Transaction, Account',
            'sql1' => '
select k.name||\' / \'||t.name stype,
       r.name stransaction,
       o.name||\' / \'||a.name saccount,
       to_char(sum(m.value), \'999 999 990"."99\') nvalue,
       count(*) nile,
       m.comment scomment
from minus m
    join type t on t.id = m.type_id
    join kind k on k.id = t.kind_id
    join transaction r on r.id = m.transaction_id
    join account a on a.id = m.account_id
    join organization o on o.id = a.organization_id
    join currency c on c.id = a.currency_id
group by stype, stransaction, saccount, scomment
order by scomment, stype, stransaction, saccount',
            'sql2' => '',
            'sql3' => '',
            'sql5' => [
                1 => [
                    'name' => 'Main',
                    'sql' => '',
                ],
            ],
        ],
        62 => [
            'title' => 'Transaction, Account',
            'sql1' => '
select r.name stransaction,
       o.name||\' / \'||a.name saccount,
       to_char(sum(m.value), \'999 999 990"."99\') nvalue,
       count(*) nile,
       m.comment scomment
from minus m
    join transaction r on r.id = m.transaction_id
    join account a on a.id = m.account_id
    join organization o on o.id = a.organization_id
    join currency c on c.id = a.currency_id
group by stransaction, saccount, scomment
order by scomment, stransaction, saccount',
            'sql2' => '',
            'sql3' => '',
            'sql5' => [
                1 => [
                    'name' => 'Main',
                    'sql' => '',
                ],
            ],
        ],
        63 => [
            'title' => 'Type, Account',
            'sql1' => '
select k.name||\' / \'||t.name stype,
       o.name||\' / \'||a.name saccount,
       to_char(sum(m.value), \'999 999 990"."99\') nvalue,
       count(*) nile,
       m.comment scomment
from minus m
    join type t on t.id = m.type_id
    join kind k on k.id = t.kind_id
    join account a on a.id = m.account_id
    join organization o on o.id = a.organization_id
    join currency c on c.id = a.currency_id
group by stype, saccount, scomment
order by scomment, stype, saccount',
            'sql2' => '',
            'sql3' => '',
            'sql5' => [
                1 => [
                    'name' => 'Main',
                    'sql' => '',
                ],
            ],
        ],
        64 => [
            'title' => 'Type, Transaction',
            'sql1' => '
select k.name||\' / \'||t.name stype,
       r.name stransaction,
       to_char(sum(m.value), \'999 999 990"."99\') nvalue,
       count(*) nile,
       m.comment scomment
from minus m
    join type t on t.id = m.type_id
    join kind k on k.id = t.kind_id
    join transaction r on r.id = m.transaction_id
group by stype, stransaction, scomment
order by scomment, stype, stransaction',
            'sql2' => '',
            'sql3' => '',
            'sql5' => [
                1 => [
                    'name' => 'Main',
                    'sql' => '',
                ],
            ],
        ],
    ];
}
