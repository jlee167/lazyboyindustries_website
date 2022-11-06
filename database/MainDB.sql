/****************************************************************************
 *      WARNING   WARNING  WARNING  WARNING  WARNING  WARNING  WARNING      *
 *      WARNING   WARNING  WARNING  WARNING  WARNING  WARNING  WARNING      *
 *      WARNING   WARNING  WARNING  WARNING  WARNING  WARNING  WARNING      *
 *      WARNING   WARNING  WARNING  WARNING  WARNING  WARNING  WARNING      *
 *      WARNING   WARNING  WARNING  WARNING  WARNING  WARNING  WARNING      *
 *      WARNING   WARNING  WARNING  WARNING  WARNING  WARNING  WARNING      *
 *      WARNING   WARNING  WARNING  WARNING  WARNING  WARNING  WARNING      *
 *      WARNING   WARNING  WARNING  WARNING  WARNING  WARNING  WARNING      *
 *      WARNING   WARNING  WARNING  WARNING  WARNING  WARNING  WARNING      *
 *      WARNING   WARNING  WARNING  WARNING  WARNING  WARNING  WARNING      *
 *      WARNING   WARNING  WARNING  WARNING  WARNING  WARNING  WARNING      *
 * ----------------------------------------------------------------------   *
 *                THIS DATA SCHEME IS HIGHLY EXPERIMENTAL.                  *
 *  IN NORMAL CIRCUMSTANCES, I WILL NOT BE ASKING ANYTHING MORE THAN YOUR   *
 *                           - USERNAME, PASSWORD                           *
 *                                 - EMAIL                                  *
 *                            - FACESHOT(OPTIONAL)                          *
 * IN MY WEBSITE. REST OF THE PERSONAL DATA WILL BE NULL FOR NORMAL USERS.  *
 * ONLY MY OWN TEST ACCOUNTS WILL BE ABLE TO HAVE DATA ON REST OF FIELDS IN *
 *                               USERS TABLE.                               *
 ****************************************************************************/






/* -------------------------------------------------------------------------- */
/*                         MySql / MariaDb Hard Reset                         */
/* -------------------------------------------------------------------------- */




DROP DATABASE IF EXISTS LazyboyServer;
CREATE DATABASE LazyboyServer;
SHOW DATABASES;
USE LazyboyServer;




CREATE TABLE users (
    id                  INT AUTO_INCREMENT PRIMARY KEY,
    firstname           VARCHAR(30) DEFAULT NULL,
    lastname            VARCHAR(30) DEFAULT NULL,
    username            VARCHAR(60) UNIQUE,
    password            VARCHAR(60),
    email               VARCHAR(60) UNIQUE,
    auth_provider       ENUM('Google', 'Kakao') DEFAULT NULL,
    uid_oauth           VARCHAR(50),
    google2fa_secret    VARCHAR(50) DEFAULT NULL,
    google2fa_active    INT(1) DEFAULT 0,
    image_url           VARCHAR(200) DEFAULT NULL,
    cell                VARCHAR(20) UNIQUE DEFAULT NULL,
    stream_key          VARCHAR(32),
    status              ENUM(
                            'DANGER_URGENT',
                            'FINE'
                        ) DEFAULT 'FINE',

    password_hint       MEDIUMTEXT DEFAULT NULL,
    hint_answer         VARCHAR(50) DEFAULT NULL,
    email_verified_at   DATETIME DEFAULT NULL,
    created_at          TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at          TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT no_duplicate_oauth UNIQUE (uid_oauth, auth_provider),
    CONSTRAINT UNIQUE (id, status),

    INDEX(status)

) ENGINE=INNODB;




CREATE TABLE user_roles(
    id      INT AUTO_INCREMENT PRIMARY KEY,
    name    VARCHAR(30) NOT NULL UNIQUE
) ENGINE=INNODB;




CREATE TABLE permissions(
    id      INT AUTO_INCREMENT PRIMARY KEY,
    name    VARCHAR(30) NOT NULL UNIQUE
) ENGINE=INNODB;




CREATE TABLE role_has_permission(
    role_id         INT NOT NULL,
    permission_id   INT NOT NULL,

    PRIMARY KEY (role_id, permission_id),
    FOREIGN KEY (role_id) REFERENCES user_roles(id),
    FOREIGN KEY (permission_id) REFERENCES permissions(id)
) ENGINE=INNODB;




CREATE TABLE user_has_role(
    user_id     INT NOT NULL,
    role_id     INT NOT NULL,

    PRIMARY KEY (user_id, role_id),
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (role_id) REFERENCES user_roles(id)
) ENGINE=INNODB;




CREATE TABLE deleted_users(
    id              INT PRIMARY KEY,
    firstname       VARCHAR(30) DEFAULT NULL,
    lastname        VARCHAR(30) DEFAULT NULL,
    username        VARCHAR(60) UNIQUE,
    password        VARCHAR(60),
    auth_provider   ENUM('Google', 'Kakao') DEFAULT NULL,
    uid_oauth       VARCHAR(50)
) ENGINE=INNODB;




CREATE TABLE password_resets (
    email       VARCHAR(60) PRIMARY KEY,
    token       VARCHAR(60),
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (email) REFERENCES users(email) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=INNODB;




CREATE TABLE cameras (
    model_no            VARCHAR(20) UNIQUE NOT NULL,
    revision            INT NOT NULL,
    product_name        VARCHAR(60) UNIQUE NOT NULL,
    description         MEDIUMTEXT NOT NULL,
    img_url             VARCHAR(200) DEFAULT NULL,

    CONSTRAINT PRIMARY KEY (model_no, revision)
) ENGINE=INNODB;




CREATE TABLE camera_registered (
    cam_id              INT AUTO_INCREMENT PRIMARY KEY,
    owner_id           INT NOT NULL,
    model_no            VARCHAR(20) NOT NULL,
    revision            INT NOT NULL,
    date_registered     TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (owner_id) REFERENCES users(id) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=INNODB;



CREATE TABLE repair_history (
    reference_no    INT AUTO_INCREMENT  PRIMARY KEY,
    cam_id          INT NOT NULL,
    description     MEDIUMTEXT,
    date            TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (cam_id) REFERENCES camera_registered(cam_id) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=INNODB;



CREATE TABLE streams (
    id                  INT AUTO_INCREMENT PRIMARY KEY,
    uid                 INT NOT NULL,
    date_report         TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    responders          MEDIUMTEXT,
    stream_key          VARCHAR(32) NOT NULL,
    video_url           VARCHAR(200) DEFAULT NULL,
    audio_url           VARCHAR(200) DEFAULT NULL,
    geo_url             VARCHAR(200) DEFAULT NULL,
    description         MEDIUMTEXT DEFAULT NULL,

    CONSTRAINT one_stream_per_user UNIQUE (uid),
    FOREIGN KEY (uid) REFERENCES users(id) ON UPDATE CASCADE
) ENGINE=INNODB;




CREATE TABLE stream_webtokens (
    id                  INT PRIMARY KEY AUTO_INCREMENT,
    uid_protected       INT NOT NULL,
    uid_guardian        INT NOT NULL,
    token               VARCHAR(200) NOT NULL,
    last_update         TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    UNIQUE KEY (uid_protected, uid_guardian)
) ENGINE=INNODB;




CREATE TABLE posts (
    id              INT AUTO_INCREMENT PRIMARY KEY,
    forum           ENUM (
                        'general',
                        'tech'
                    ),
    title           VARCHAR(100) NOT NULL,
    author          VARCHAR(20),
    date            TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    view_count      INT NOT NULL DEFAULT 0,
    contents        MEDIUMTEXT,

    FOREIGN KEY (author) REFERENCES users(username) ON UPDATE CASCADE ON DELETE CASCADE,
    INDEX (view_count),
    INDEX (forum)
) ENGINE=INNODB;




CREATE TABLE comments (
    id              INT AUTO_INCREMENT PRIMARY KEY,
    author          VARCHAR(20),
    date            TIMESTAMP  DEFAULT CURRENT_TIMESTAMP,
    contents        MEDIUMTEXT,
    post_id         INT NOT NULL,
    parent_id       INT DEFAULT NULL,
    depth           INT NOT NULL DEFAULT 0,

    FOREIGN KEY (author) REFERENCES users(username) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (post_id) REFERENCES posts(id) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=INNODB;



CREATE TABLE tags (
    id      INT AUTO_INCREMENT PRIMARY KEY,
    name    VARCHAR(20)
) ENGINE=INNODB;



CREATE TABLE post_tags(
    id          INT AUTO_INCREMENT PRIMARY KEY,
    post_id     INT NOT NULL,
    tag_id      INT NOT NULL,

    FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE,
    FOREIGN KEY (tag_id) REFERENCES tags(id) ON DELETE CASCADE
) ENGINE=INNODB;



CREATE TABLE post_likes (
    post_id         INT NOT NULL,
    uid             INT NOT NULL,

    PRIMARY KEY (post_id, uid),
    FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE
) ENGINE=INNODB;




CREATE TABLE bookmarks (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    uid         INT NOT NULL,
    post_id     INT NOT NULL,

    FOREIGN KEY (uid) REFERENCES users(id) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (post_id) REFERENCES posts(id) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=INNODB;




CREATE TABLE support_request (
    id       INT auto_increment PRIMARY KEY,
    uid      INT DEFAULT NULL,
    contact  VARCHAR(60) NOT NULL,
    type     ENUM ('REPAIR', 'TECH_SUPPORT', 'REFUND', 'LEGAL'),
    status   ENUM('PENDING', 'RESPONDED', 'RESOLVED') DEFAULT 'PENDING',
    contents MEDIUMTEXT
) ENGINE=INNODB;




CREATE TABLE faq_contents (
    id          INT auto_increment PRIMARY KEY,
    question    VARCHAR(200) NOT NULL,
    answer      MEDIUMTEXT NOT NULL,

    FULLTEXT(question)
);




CREATE TABLE guardianship (
    id                  INT AUTO_INCREMENT PRIMARY KEY,
    uid_guardian        INT NOT NULL,
    uid_protected       INT NOT NULL,
    signed_protected    ENUM('WAITING', 'ACCEPTED', 'DENIED') NOT NULL DEFAULT 'WAITING',
    signed_guardian     ENUM('WAITING', 'ACCEPTED', 'DENIED') NOT NULL DEFAULT 'WAITING',

    CONSTRAINT no_duplicate UNIQUE (uid_guardian, uid_protected),
    INDEX (uid_guardian),
    INDEX (uid_protected)
) ENGINE=INNODB;


CREATE TABLE products(
    id                  INT PRIMARY KEY AUTO_INCREMENT,
    title               VARCHAR(30) UNIQUE NOT NULL,
    description         MEDIUMTEXT,
    price_credits       INT UNSIGNED NOT NULL,
    active              BOOLEAN NOT NULL,
    img_url             VARCHAR(100)
) ENGINE=INNODB;


CREATE TABLE warehouses(
    id              INT PRIMARY KEY AUTO_INCREMENT,
    distributor     VARCHAR(100),
    location        VARCHAR(100),

    CONSTRAINT unique_location UNIQUE (distributor, location)
) ENGINE=INNODB;


CREATE TABLE product_stocks(
    id                  INT PRIMARY KEY AUTO_INCREMENT,
    warehouse_id        INT NOT NULL,
    product_id          INT NOT NULL,
    quantity_available  INT UNSIGNED,
    last_purchase       TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    disable_purchase    INT(0) DEFAULT 0,

    CONSTRAINT UNIQUE (warehouse_id, product_id),
    CONSTRAINT FOREIGN KEY (product_id) REFERENCES products(id) ON UPDATE CASCADE,
    CONSTRAINT FOREIGN KEY (warehouse_id) REFERENCES warehouses(id) ON UPDATE CASCADE
) ENGINE=INNODB;




/*
    Accounting Tables
*/
CREATE TABLE credits(
    uid                 INT PRIMARY KEY,
    credits             INT UNSIGNED NOT NULL,
    created_at          TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at          TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (uid) REFERENCES users(id) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=INNODB;



CREATE TABLE transactions (
    id      INT PRIMARY KEY AUTO_INCREMENT,
    uid     INT NOT NULL,
    credits_expended    INT UNSIGNED NOT NULL,
    date    TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=INNODB;



CREATE TABLE product_purchases (
    id                  INT PRIMARY KEY AUTO_INCREMENT,
    uid                 INT NOT NULL,
    transaction_id      INT NOT NULL,
    product_id          INT NOT NULL,
    quantity            INT UNSIGNED NOT NULL,
    unit_price          INT UNSIGNED NOT NULL,
    date                TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    authorized          BOOLEAN NOT NULL DEFAULT false,
    stock_after_transaction INT NOT NULL,

    FOREIGN KEY (product_id) REFERENCES products(id),
    FOREIGN KEY (uid) REFERENCES users(id) ON UPDATE CASCADE ON DELETE CASCADE,
    INDEX (uid, product_id),
    FOREIGN KEY (transaction_id) REFERENCES transactions(id)
) ENGINE=INNODB;





CREATE TABLE product_refunds (
    id                  INT PRIMARY KEY AUTO_INCREMENT ,
    uid                 INT NOT NULL,
    product_id          INT NOT NULL,
    quantity            INT UNSIGNED NOT NULL,
    credits_refunded    INT UNSIGNED NOT NULL,
    date                TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    authorized          BOOLEAN NOT NULL DEFAULT false,

    stock_after_transaction INT NOT NULL,

    FOREIGN KEY (product_id) REFERENCES products(id),
    FOREIGN KEY (uid) REFERENCES users(id) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=INNODB;




CREATE TABLE product_restock_history (
    id                  INT PRIMARY KEY AUTO_INCREMENT,
    product_id          INT NOT NULL,
    quantity            INT UNSIGNED NOT NULL,
    date                TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    authorized          BOOLEAN NOT NULL DEFAULT false,

    stock_after_transaction INT NOT NULL,

    FOREIGN KEY (product_id) REFERENCES products(id) ON UPDATE CASCADE
) ENGINE=INNODB;



CREATE TABLE product_ratings(
    /* @Todo: Check need for normalization on uid */
    id                  INT PRIMARY KEY AUTO_INCREMENT,
    uid                 INT NOT NULL,
    purchase_id         INT NOT NULL,
    value               TINYINT NOT NULL,
    comment             VARCHAR(200),
    date                TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    UNIQUE (purchase_id),
    FOREIGN KEY (uid) REFERENCES users(id) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (purchase_id) REFERENCES product_purchases(id)
) ENGINE=INNODB;



CREATE TABLE cart_items(
    id                  INT PRIMARY KEY AUTO_INCREMENT,
    uid                 INT NOT NULL,
    product_id          INT NOT NULL,
    quantity            INT UNSIGNED NOT NULL,
    last_updated        TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (uid) REFERENCES users(id) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id)
) ENGINE   =INNODB;





CREATE TABLE credit_purchases (
    id              INT PRIMARY KEY AUTO_INCREMENT ,
    uid             INT NOT NULL,
    payment_usd     FLOAT UNSIGNED NOT NULL,
    credit          INT UNSIGNED NOT NULL,
    date            TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    authorized      BOOLEAN NOT NULL DEFAULT FALSE
);



CREATE TABLE donations_kakaopay (
    id                  INT PRIMARY KEY AUTO_INCREMENT ,
    uid                 INT NOT NULL,
    payments_krw        INT UNSIGNED NOT NULL,
    credit              INT UNSIGNED NOT NULL,
    date                TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    authorized          BOOLEAN NOT NULL DEFAULT FALSE
);


COMMIT;


/* -------------------------------------------------------------------------- */
/*                              Stored Procedures                             */
/* -------------------------------------------------------------------------- */


DELIMITER $$


/* ----------------------------- User Management ---------------------------- */

CREATE PROCEDURE GetIdByUsername (
    IN username VARCHAR(60),
    OUT uid_out INT
)
BEGIN
    SELECT id
    INTO   uid_out
    FROM   users
    WHERE  users.username = username;
END $$



/* ------------------------- E-Commerce Transactions ------------------------ */

CREATE PROCEDURE PurchaseCredits(
    IN qty INT,
    IN uid INT,
    IN usd_paid FLOAT,
    OUT result INT
)
BEGIN
    DECLARE current_credits INT DEFAULT 0;

    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        SET result = -1;
    END;


    START TRANSACTION;

        SELECT credits
        INTO current_credits
        FROM credits
        WHERE credits.uid = uid
        FOR UPDATE;

        UPDATE credits
        SET credits.credits = current_credits + qty
        WHERE credits.uid = uid;

        INSERT INTO credit_purchases(uid, payment_usd, credit, authorized)
        VALUES(uid, usd_paid, qty, 1);

        COMMIT;
        SET result = 0;
END$$




CREATE PROCEDURE PurchaseProduct(
    IN qty INT,
    IN uid INT,
    IN product_id INT
)
BEGIN

    DECLARE unit_price INT DEFAULT 0;
    DECLARE current_credits INT DEFAULT 0;
    DECLARE current_stock INT DEFAULT 0;
    DECLARE transaction_id INT DEFAULT 0;

    DECLARE curr_time TIMESTAMP;

    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        SELECT 0 as result, "SQL Exception" as error;
        COMMIT;
    END;

    START TRANSACTION;

        SELECT price_credits
        INTO unit_price
        FROM products
        WHERE products.id = product_id;

        SELECT credits
        INTO current_credits
        FROM credits
        WHERE credits.uid = uid
        FOR UPDATE;

        SELECT quantity_available
        INTO current_stock
        FROM product_stocks
        WHERE product_stocks.product_id = product_id
        FOR UPDATE;



        IF (current_stock < qty) THEN
            /* Insufficient stock level */
            ROLLBACK;
            SELECT 0 as result, "Insufficient Stock" as error;
            COMMIT;
        ELSEIF (current_credits < (qty * unit_price)) THEN
            /* Not enough credits */
            ROLLBACK;
            SELECT 0 as result, "Not enough credits" as error;
            COMMIT;
        ELSE
            /* Proceed payment */
            UPDATE product_stocks
            SET quantity_available = current_stock - qty
            WHERE product_stocks.product_id = product_id;

            SET curr_time = NOW();

            INSERT INTO transactions (
                uid,
                credits_expended,
                date
            )
            VALUES (
                uid,
                unit_price * qty,
                curr_time
            );

            SELECT id
            INTO transaction_id
            FROM transactions
            WHERE transactions.uid = uid AND transactions.date = curr_time;


            INSERT INTO product_purchases (
                uid,
                product_id,
                quantity,
                unit_price,
                transaction_id,
                authorized,
                stock_after_transaction,
                date
            )
            VALUES (
                uid,
                product_id,
                qty,
                unit_price,
                transaction_id,
                1,
                current_stock - qty,
                curr_time
            );

            UPDATE credits
            SET credits.credits = current_credits - (qty * unit_price)
            WHERE credits.uid = uid;

            SELECT 1 as result;
            COMMIT;

        END IF;
END$$



CREATE PROCEDURE GetPurchaseHistory(
    IN uid INT
)
BEGIN
    SELECT  records.*, ratings.value, ratings.comment,
            ratings.date as rating_timestamp, products.title, transactions.credits_expended
    FROM product_purchases AS records
    LEFT JOIN product_ratings AS ratings
    ON records.id = ratings.purchase_id
    INNER JOIN products
    ON products.id = records.product_id
    INNER JOIN transactions
    ON transactions.id = records.transaction_id
    WHERE records.uid = uid
    ORDER BY records.date;
END$$


/* --------------------- Guardianship Management Queries -------------------- */

CREATE PROCEDURE GetGuardians (
    IN username VARCHAR(60)
)
BEGIN

SELECT users.id,
       users.username,
       users.image_url,
       users.cell,
       users.email
FROM   users
WHERE  users.id IN (
    /* Get guardians' UIDs from their usernames */
    SELECT uid_guardian
    FROM   guardianship
    WHERE  uid_protected = (SELECT id
                            FROM   users
                            WHERE  users.username = username)
            AND guardianship.signed_protected = 'ACCEPTED'
            AND guardianship.signed_guardian = 'ACCEPTED');
COMMIT;
END $$




CREATE PROCEDURE GetProtecteds (
    IN username VARCHAR(60)
)
BEGIN

SELECT users.id,
       users.username,
       users.image_url,
       users.cell,
       users.email,
       users.status,
       stream_webtokens.token
FROM   users
LEFT JOIN stream_webtokens ON users.id = stream_webtokens.uid_protected
WHERE  users.id IN (
    /* Get guardians' UIDs from their usernames */
    SELECT uid_protected
    FROM   guardianship
    WHERE  uid_guardian = (SELECT id
                            FROM   users
                            WHERE  users.username = username)
            AND guardianship.signed_protected = 'ACCEPTED'
            AND guardianship.signed_guardian = 'ACCEPTED');

COMMIT;
END $$




CREATE PROCEDURE GetPendingRequests(
    IN uid INT
)
BEGIN
    SELECT  guardianship.*,
            A.username AS username_guardian,
            B.username AS username_protected
    FROM   guardianship
    INNER JOIN users as A
    ON guardianship.uid_guardian=A.id
    INNER JOIN users as B
    ON guardianship.uid_protected=B.id
    WHERE signed_guardian='WAITING'
        OR signed_protected='WAITING';
    COMMIT;

END $$




CREATE PROCEDURE GetEndangeredProtecteds (
    IN username_i VARCHAR(60)
)
BEGIN
    SELECT  users.id,
            users.username
    FROM    users
            INNER JOIN  guardianship
                    ON  guardianship.uid_protected = users.id
    WHERE   guardianship.uid_guardian IN (  SELECT users.id
                                            FROM   users
                                            WHERE  username = username_i)
            AND users.status = "DANGER_URGENT";
END $$




/* Todo*/
CREATE PROCEDURE GetEndangeredPeers (
    IN username VARCHAR(60)
)
BEGIN

    SELECT  users.id, users.username
    FROM    users
    WHERE   users.id IN (
                            SELECT      parent.uid_protected
                            FROM        guardianship AS parent
                            INNER JOIN  guardianship AS child
                            ON          child.uid_guardian = parent.uid_guardian
                            WHERE       child.uid_protected = username
                        );
END $$




/* ----------------------- Emergency Streaming Queries ---------------------- */

CREATE PROCEDURE GetStreamKey(
    IN username VARCHAR(30)
)
BEGIN
    SELECT  stream_key
    FROM    users
    WHERE   users.username=username;
END $$




CREATE PROCEDURE GetStreamWebToken(
    IN uid_guardian INT,
    IN uid_protected VARCHAR(60)
)
BEGIN
    START TRANSACTION;
        SELECT token
        FROM   stream_webtokens
        WHERE  stream_webtokens.uid_guardian = uid_guardian
            AND stream_id = (SELECT id
                            FROM   streams
                            WHERE  streams.uid = ( SELECT id
                                            FROM   users
                                            WHERE  username = username_protected));
    COMMIT;
END $$




CREATE PROCEDURE StartEmergencyProtocol(
    IN uid VARCHAR(60)
)
BEGIN
    /* Transaction Exception Handler */
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
    END;

    START TRANSACTION;
        SELECT Group_concat(uid_guardian)
        INTO   @guardians
        FROM   guardianship
        WHERE  uid_protected = uid;

        UPDATE streams
        SET    status = 'DANGER_URGENT'
        WHERE  streams.uid = uid;
    COMMIT;
END $$




CREATE PROCEDURE StopEmergencyProtocol(
    IN uid VARCHAR(60)
)
BEGIN
    /* Transaction Exception Handler */
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
    END;

    START TRANSACTION;

        UPDATE streams
        SET    status = 'FINE'
        WHERE  streams.uid = uid;
    COMMIT;
END $$




CREATE PROCEDURE StartStream (
    IN uid INT
)
BEGIN

    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
    END;

    START TRANSACTION;

        INSERT INTO streams(uid, responders, stream_key)
        VALUES (
            uid,
            (
                SELECT Group_concat(uid_guardian)
                FROM   guardianship
                WHERE  uid_protected = uid
            ),
            (
                SELECT stream_key
                FROM   users
                WHERE  users.id = uid
            )
        );
    COMMIT;
END $$




CREATE PROCEDURE CloseStream(
    IN username VARCHAR(60)
)
BEGIN

    /* Transaction Exception Handler */
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
    END;

    START TRANSACTION;
        CALL GetIdByUsername(username, @uid);
        DELETE FROM streams WHERE streams.uid = @uid;
    COMMIT;
END $$




/* ------------------------------- Forum CRUD ------------------------------- */

CREATE PROCEDURE GetTrendingPosts(
)
BEGIN
    /* Get most viewed posts of latest 7 days */
    SELECT DISTINCT id,
           title,
           forum,
           DATE(date) AS date,
           COUNT(*) AS likes
    FROM   (SELECT * FROM posts ORDER BY id DESC LIMIT 2000) AS posts
    LEFT JOIN post_likes ON posts.id=post_likes.post_id
    GROUP BY id
    ORDER BY likes DESC, id DESC
    LIMIT 10;
    /*WHERE  date BETWEEN ( Now() - INTERVAL 7 day ) AND Now()*/
    COMMIT;
END$$




CREATE PROCEDURE GetTopPosts(
)
BEGIN

    /* Get most viewed posts of all time */
    SELECT DISTINCT id,
           title,
           forum,
           DATE(date) AS date
    FROM   posts
    ORDER  BY view_count DESC
    LIMIT  10;

    COMMIT;
END$$


DELIMITER ;

/* -------------------------------------------------------------------------- */
/*                             /Stored Procedures                             */
/* -------------------------------------------------------------------------- */

COMMIT;
