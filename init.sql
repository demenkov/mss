CREATE TABLE `user` (
    `email` varchar(255) NOT NULL,
    `username` varchar(255) NOT NULL,
    `validts` int NOT NULL DEFAULT '0',
    `confirmed` int NOT NULL DEFAULT '0',
    `checked` int NOT NULL DEFAULT '0',
    `valid` int NOT NULL DEFAULT '0',
    PRIMARY KEY (`email`),
    KEY `user_validts_index` (`validts`),
    KEY `user_checked_index` (`checked`,`valid`),
    KEY `user_valid_confirmed_index` (`valid`,`confirmed`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci

CREATE TABLE `job` (
   `id` int NOT NULL AUTO_INCREMENT,
   `type` int NOT NULL COMMENT '0 - check, 1 - send',
   `email` varchar(255) NOT NULL,
   `ts` int NOT NULL,
   `run` int DEFAULT NULL,
   PRIMARY KEY (`id`),
   UNIQUE KEY `job_email_type_uindex` (`email`,`type`),
   KEY `job_type_ts_index` (`type` DESC,`ts`),
   KEY `job_run_index` (`run`),
   CONSTRAINT `job_user_email_fk` FOREIGN KEY (`email`) REFERENCES `user` (`email`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci