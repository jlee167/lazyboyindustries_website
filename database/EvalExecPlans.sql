
/* ----------------------------- GetGuardians ----------------------------- */
SELECT 'GetGuardians';

EXPLAIN
SELECT users.id,
       users.username,
       users.status
FROM   users
WHERE  users.id IN (
                   /* Get guardians' UIDs from their usernames */
                   SELECT uid_protected
                    FROM   guardianship
                    WHERE  uid_guardian = (SELECT id
                                           FROM   users
                                           WHERE  users.username = 'user1')
                           AND guardianship.signed_protected IS TRUE
                           AND guardianship.signed_guardian IS TRUE);




/* ----------------------------- GetProtecteds ---------------------------- */
SELECT 'GetProtecteds';

EXPLAIN
SELECT users.id,
       users.username
FROM   users
WHERE  users.id IN (
                    SELECT uid_guardian
                    FROM   guardianship USE INDEX(uid_protected)
                    WHERE  uid_protected = (SELECT id
                                            FROM   users
                                            WHERE  users.username = 'user1')
                           AND guardianship.signed_protected IS TRUE
                           AND guardianship.signed_guardian IS TRUE);




/* --------------------------- GetRequestsSent --------------------------- */
SELECT 'GetRequestsSent';

EXPLAIN
SELECT *
    FROM   guardianship
    WHERE   ( guardianship.uid_protected = 1
                AND guardianship.signed_protected IS FALSE )
            OR ( guardianship.uid_guardian = 1
                AND guardianship.signed_guardian IS FALSE );




/* ------------------------- GetEndangeredProtectees ------------------------ */
SELECT 'GetEndangeredProtectees';

EXPLAIN
SELECT  users.id,
            users.username
    FROM    users
            INNER JOIN  guardianship
                    ON  guardianship.uid_protected = users.id
    WHERE   guardianship.uid_guardian IN (  SELECT users.id
                                            FROM   users
                                            WHERE  username = 'user1')
            AND users.status = "DANGER_URGENT";


/* --------------------------- GetEndangeredPeers --------------------------- */
SELECT 'GetEndangeredPeers';

EXPLAIN
SELECT  users.id, users.username
    FROM    users
    WHERE   users.id IN (
                            SELECT      parent.uid_protected
                            FROM        guardianship AS parent
                            INNER JOIN  guardianship AS child
                            ON          child.uid_guardian = parent.uid_guardian
                            WHERE       child.uid_protected = 1
                        );


/* ------------------------- StartEmergencyProtocol ------------------------- */
SELECT 'StartEmergencyProtocol';

EXPLAIN
UPDATE streams
SET    status = 'DANGER_URGENT',
        response = 'RESPONSE_REQUIRED'
WHERE  streams.uid = 1;



/* ------------------------------- StartStream ------------------------------ */
SELECT 'StartStream';

EXPLAIN
INSERT INTO streams(uid, responders, stream_key)
    VALUES (
        (
            SELECT id
            FROM   users
            WHERE  users.username = "user2"
        ),
        (
            SELECT Group_concat(uid_guardian)
            FROM   guardianship
            WHERE  uid_protected = 3
        ),
        (
            SELECT stream_key
            FROM   users
            WHERE  users.username = "user2"
        )
    );

    SELECT  id
    FROM    streams
    WHERE   streams.uid=1;


/* ------------------------------- Close Stream ------------------------------ */
SELECT 'CloseStream';

EXPLAIN
DELETE FROM streams WHERE streams.uid = 1;


/* ---------------------------- RegisterWebTokens --------------------------- */
SELECT 'RegisterWebTokens';
SELECT 'PASS';



/* ---------------------------- GetTrendingPosts ---------------------------- */
SELECT 'GetTrendingPosts';

EXPLAIN
SELECT  id,
        title,
        forum,
        DATE(date) AS date
FROM    posts
WHERE   date BETWEEN ( Now() - INTERVAL 7 day ) AND Now()
ORDER   BY view_count DESC
LIMIT   10;


/* ------------------------------- GetTopPosts ------------------------------ */
SELECT 'GetTopPosts';

EXPLAIN
SELECT  id,
        title,
        forum,
        DATE(date) AS date
FROM    posts
ORDER   BY view_count DESC
LIMIT   10;
