DROP PROCEDURE if exists graphtest ;
DROP PROCEDURE if exists seedDB ;
DROP PROCEDURE if exists addHops ;
drop table if exists edge ;
drop table if exists vertex ;

DELIMITER $$



create table vertex (
    id INT AUTO_INCREMENT PRIMARY KEY
) ENGINE=INNODB;


create table edge (
    from_vertex    INT,
    to_vertex      INT,

    PRIMARY KEY (from_vertex, to_vertex),
    FOREIGN KEY (from_vertex) REFERENCES vertex(id),
    FOREIGN KEY (to_vertex) REFERENCES vertex(id)
) ENGINE=INNODB;




CREATE PROCEDURE CreateGraph(
)
BEGIN
    DECLARE iter INT;
    SET iter = 1;

    /* Create 10^6 vertexes */
    WHILE iter <= POWER(10, 6) DO
        INSERT INTO vertex(id)
        VALUES(iter);
        SET iter=iter+1;
    END WHILE;

    CALL addHops(1);
    CALL addHops(2);
    CALL addHops(3);
    CALL addHops(4);
    /*CALL addHops(5);*/
END $$




CREATE PROCEDURE AddHops(
    IN depth INT
)
BEGIN
    DECLARE iter INT;
    DECLARE iter2 INT;

    DECLARE loopCnt INT;
    SET loopCnt = 0;
    SET Iter = 0;

    WHILE loopCnt < depth DO
        SET iter = iter + POWER(10, loopCnt);
        SET loopCnt = loopCnt + 1;
    END WHILE;

    SET loopCnt = 0;
    SET iter2 = 0;

    WHILE loopCnt < POWER(10, depth) DO
        WHILE iter2 < 10 DO
            INSERT INTO edge
            (
                from_vertex,
                to_vertex
            )
            VALUES(iter, iter * 10 + iter2);
            SET iter2 = iter2 + 1;
        END WHILE;
        SET iter2 = 0;
        SET loopCnt = loopCnt + 1;
        SET iter = iter + 1;
    END WHILE;

END $$




CREATE PROCEDURE GraphTest(
    IN uid      INT,
    IN max_hops INT
)
begin
    START TRANSACTION;
        WITH recursive orig(id, hops) AS
        (
            SELECT id, 0
            FROM   vertex
            WHERE  vertex.id= uid
            UNION ALL
            SELECT     to_vertex, hops+1
            FROM       orig
            INNER JOIN edge AS edge_innerjoin
            ON         edge_innerjoin.from_vertex = orig.id
            WHERE      hops < max_hops
        )
        SELECT id, MIN(hops) as hops
        FROM   orig
        GROUP BY id;
    COMMIT;

END $$
delimiter ;
