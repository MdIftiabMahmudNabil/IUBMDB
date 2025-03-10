USE iubmdb;
SOURCE /docker-entrypoint-initdb.d/iubmdb.sql;

CREATE DATABASE IF NOT EXISTS imdb_webapp;
USE imdb_webapp;

CREATE TABLE users (
    user_id INT(11) NOT NULL AUTO_INCREMENT,
    username VARCHAR(100) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    is_admin BOOLEAN NOT NULL DEFAULT FALSE,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE movies (
    movie_id INT(11) NOT NULL AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    release_year YEAR NOT NULL,
    genre VARCHAR(100) NOT NULL,
    description TEXT NOT NULL,
    director_id INT(11) NOT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (movie_id),
    FOREIGN KEY (director_id) REFERENCES directors(director_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE directors (
    director_id INT(11) NOT NULL AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    birthdate DATE NOT NULL,
    nationality VARCHAR(100) NOT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (director_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE actors (
    actor_id INT(11) NOT NULL AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    birthdate DATE NOT NULL,
    nationality VARCHAR(100) NOT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (actor_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE movie_cast (
    movie_id INT(11) NOT NULL,
    actor_id INT(11) NOT NULL,
    role_name VARCHAR(255) NOT NULL,
    PRIMARY KEY (movie_id, actor_id),
    FOREIGN KEY (movie_id) REFERENCES movies(movie_id) ON DELETE CASCADE,
    FOREIGN KEY (actor_id) REFERENCES actors(actor_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE reviews (
    review_id INT(11) NOT NULL AUTO_INCREMENT,
    movie_id INT(11) NOT NULL,
    user_id INT(11) NOT NULL,
    rating DECIMAL(3,1) NOT NULL CHECK (rating BETWEEN 0 AND 10),
    review_text TEXT NOT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (review_id),
    FOREIGN KEY (movie_id) REFERENCES movies(movie_id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE genres (
    genre_id INT(11) NOT NULL AUTO_INCREMENT,
    genre_name VARCHAR(100) NOT NULL UNIQUE,
    PRIMARY KEY (genre_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE movie_genres (
    movie_id INT(11) NOT NULL,
    genre_id INT(11) NOT NULL,
    PRIMARY KEY (movie_id, genre_id),
    FOREIGN KEY (movie_id) REFERENCES movies(movie_id) ON DELETE CASCADE,
    FOREIGN KEY (genre_id) REFERENCES genres(genre_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE watchlist (
    watchlist_id INT(11) NOT NULL AUTO_INCREMENT,
    user_id INT(11) NOT NULL,
    movie_id INT(11) NOT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (watchlist_id),
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (movie_id) REFERENCES movies(movie_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;




INSERT INTO users (username, email, password, is_admin)
VALUES
('john_doe', 'john@example.com', 'password123', FALSE),
('admin_user', 'admin@example.com', 'admin123', TRUE),
('jane_doe', 'jane@example.com', 'jane123', FALSE);

INSERT INTO directors (name, birthdate, nationality)
VALUES
('Steven Spielberg', '1946-12-18', 'American'),
('Christopher Nolan', '1970-07-30', 'British-American'),
('Quentin Tarantino', '1963-03-27', 'American');

INSERT INTO actors (name, birthdate, nationality)
VALUES
('Leonardo DiCaprio', '1974-11-11', 'American'),
('Morgan Freeman', '1937-06-01', 'American'),
('Scarlett Johansson', '1984-11-22', 'American');

INSERT INTO movies (title, release_year, genre, description, director_id)
VALUES
('Inception', 2010, 'Sci-Fi', 'A mind-bending thriller where thieves invade the subconscious.', 2),
('The Dark Knight', 2008, 'Action', 'Batman battles the Joker in a chaotic Gotham City.', 2),
('Pulp Fiction', 1994, 'Crime', 'A series of interconnected stories involving criminals and their mishaps.', 3);

INSERT INTO movie_cast (movie_id, actor_id, role_name)
VALUES
(1, 1, 'Dom Cobb'),
(2, 1, 'Jack Dawson'),
(2, 2, 'Detective Joe'),
(3, 1, 'Mia Wallace'),
(3, 2, 'Jules Winnfield');

INSERT INTO genres (genre_name)
VALUES
('Sci-Fi'),
('Action'),
('Crime'),
('Thriller'),
('Drama');

INSERT INTO movie_genres (movie_id, genre_id)
VALUES
(1, 1),
(2, 2),
(3, 3);

INSERT INTO watchlist (user_id, movie_id)
VALUES
(1, 1),
(2, 2),
(3, 3);

INSERT INTO reviews (movie_id, user_id, rating, review_text)
VALUES
(1, 1, 9.0, 'Amazing movie with a complex and mind-bending plot.'),
(2, 2, 10.0, 'Masterpiece! Heath Ledger as the Joker was incredible.'),
(3, 3, 8.5, 'Great storytelling, with some unforgettable characters.');
