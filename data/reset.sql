CREATE TABLE zufp_reset (
    id INT UNSIGNED AUTO_INCREMENT NOT NULL,
    user INT UNSIGNED NOT NULL,
    nonce VARCHAR(255) NOT NULL,
    created_on DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX IDX_295E70D88D93D649B22492FD (user, nonce),
    INDEX IDX_295E70D8E86F50DC (created_on),
    PRIMARY KEY(id)
) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
