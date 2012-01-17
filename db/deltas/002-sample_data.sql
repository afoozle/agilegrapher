--//

--
-- Initial Dummy task data
-- Author: Matthew Wheeler <matt@yurisko.net>


insert into task(name,description) values('Get milk', 'Go to the shop for milk');
insert into task(name,description) values('Get bread', 'Go to the shop for bread');
insert into task(name,description) values('Get eggs', 'Search the chicken coop for eggs');
insert into task(name,description) values('Buy flowers', 'Go to the florist and get some flowers');

--//@UNDO

delete from task where name in('Get milk','Get bread','Get eggs','Buy flowers');

--//
