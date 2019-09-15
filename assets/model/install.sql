/* Developed by WEBmods
 * Zagorski oglasnik j.d.o.o. za usluge | www.zagorski-oglasnik.com
 *
 * License: GPL-3.0-or-later
 * More info in license.txt
*/

CREATE TABLE IF NOT EXISTS /*TABLE_PREFIX*/t_user_profilepic (
    fk_i_user_id INT(10) UNSIGNED NOT NULL,
    s_name VARCHAR(255) NOT NULL,
    dt_date DATETIME NOT NULL,

        PRIMARY KEY(fk_i_user_id),
        FOREIGN KEY (fk_i_user_id) REFERENCES /*TABLE_PREFIX*/t_user (pk_i_id)
) ENGINE=InnoDB DEFAULT CHARACTER SET 'UTF8' COLLATE 'UTF8_GENERAL_CI';
