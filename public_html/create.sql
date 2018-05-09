DROP TABLE IF EXISTS routing_info, engineer_shift, conductor_shift, location, rail_car, train, conductor, engineer, administrator, employee, customer, authentication, `user`, weblog ;

Create table weblog(
    transaction_id int NOT NULL AUTO_INCREMENT,
    Primary key(transaction_id),
    `date` date,
    `time` time,
    ip_address varchar(255),
    `action` varchar(255),
    action_user varchar(255)
);

/*supertype*/
create table `user`(
    user_id varchar(255) NOT NULL,
    primary key(user_id),
    first_name varchar(255),
    last_name varchar(255)
);

create table authentication(
    user_id varchar(255) NOT NULL,
    primary key(user_id),
    foreign key(user_id) references `user`(user_id),
    /*remember to store as encrypted, use php command before sending to database from server*/
    `password` varchar(255) NOT NULL,
    role varchar(255)
);

/*first subtypes*/
create table customer(
    user_id varchar(255) NOT NULL,
    primary key(user_id),
    foreign key(user_id) references `user`(user_id),
    company_id varchar(255), /*int?*/
    unique(company_id),
    num_cars_requested int,
    cargo_type varchar(255),
    car_type_requested varchar(255)
);

create table employee(
    user_id varchar(255) NOT NULL,
    primary key(user_id),
    foreign key(user_id) references `user`(user_id),
    years_in_company decimal(4,2)
);

/*employee subtypes*/
create table administrator(
    user_id varchar(255) NOT NULL,
    primary key(user_id),
    foreign key(user_id) references employee(user_id)
);

create table engineer(
    user_id varchar(255) NOT NULL,
    primary key(user_id),
    foreign key(user_id) references employee(user_id),    
    total_travel_hours int,
    rank varchar(255),
    `status` varchar(255)
);

create table conductor(
    user_id varchar(255) NOT NULL,
    primary key(user_id),
    foreign key(user_id) references employee(user_id),
    rank varchar(255),
    `status` varchar(255)
);

create table train(
    train_no int auto_increment not null,
    primary key(train_no),
    /*engineer_one_id varchar(255),
    engineer_two_id varchar(255),
    foreign key(engineer_one_id) references engineer(user_id),
    foreign key(engineer_two_id) references engineer(user_id),*/
    /*conductor_id varchar(255),*/
    locomotive_id varchar(255),
    unique(locomotive_id)
    /*foreign keys for fields that reference others (?) i.e. conductor, rail car, etc*/
);

create table rail_car(
    rail_car_id int NOT NULL,
    primary key(rail_car_id),
    user_id varchar(255),
    foreign key(user_id) references customer(user_id),
    car_type varchar(255),
    location varchar(255),
    manufacturer varchar(255),
    freight_capacity int,
    reservation_status varchar(255),
    price decimal(8,2),
    train_no int NOT NULL,
    foreign key(train_no) references train(train_no)
);

create table location(
    street varchar(255) not null,
    zipcode int(5) not null,
    primary key(street, zipcode),
    /*split up into more specific info?*/
    city_name varchar(255),
    state varchar(255)
);

create table conductor_shift(
    train_no int not null,
    user_id varchar(255) not null,
    primary key(train_no, user_id),
    foreign key(train_no) references train(train_no),
    foreign key(user_id) references conductor(user_id)    
);

create table engineer_shift(
    train_no int not null,
    user_id varchar(255) not null,
    primary key(train_no, user_id),
    foreign key(train_no) references train(train_no),
    foreign key(user_id) references engineer(user_id),
    shift_duration time
);

create table routing_info(
    street varchar(255) not null,
    zipcode int not null,
    train_no int not null,
    primary key(street, zipcode, train_no),
    foreign key(street, zipcode) references location(street, zipcode),
    foreign key(train_no) references train(train_no),
    departure_city varchar(255),
    destination_city varchar(255)
);
