SELECT
    expiry,
    FROM_UNIXTIME(expiry) AS readable_date
FROM
    sessions;