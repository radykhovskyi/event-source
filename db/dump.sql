CREATE TABLE IF NOT EXISTS event_log (
    event_id VARCHAR(36) NOT NULL,
    event_type VARCHAR(100) NOT NULL,
    aggregate_root_id VARCHAR(36) NOT NULL,
    aggregate_root_version MEDIUMINT(36) UNSIGNED NOT NULL,
    time_of_recording VARCHAR(40) NOT NULL,
    payload JSON NOT NULL,
    INDEX aggregate_root_id (aggregate_root_id),
    UNIQUE KEY unique_id_and_version (aggregate_root_id, aggregate_root_version ASC)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE=INNODB