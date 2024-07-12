create database mydb;

use mydb;

create table tasks (
    id int not null auto_increment,
    nome varchar(255) not null,
    prioridade int not null default 0,
    done datetime,
    created_at datetime not null default now(),
    primary key (id)
);