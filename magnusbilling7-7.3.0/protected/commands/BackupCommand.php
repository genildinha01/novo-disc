<?php
/**
 * =======================================
 * ###################################
 * MagnusBilling
 *
 * @package MagnusBilling
 * @author Adilson Leffa Magnus.
 * @copyright Copyright (C) 2005 - 2018 MagnusSolution. All rights reserved.
 * ###################################
 *
 * This software is released under the terms of the GNU Lesser General Public License v2.1
 * A copy of which is available from http://www.gnu.org/copyleft/lesser.html
 *
 * Please submit bug reports, patches, etc to https://github.com/magnusbilling/mbilling/issues
 * =======================================
 * Magnusbilling.com <info@magnusbilling.com>
 *
 */
class BackupCommand extends ConsoleCommand
{
    public function run($args)
    {
        $dbString = explode('dbname=', Yii::app()->db->connectionString);
        $dataBase = end($dbString);

        $username = Yii::app()->db->username;
        $password = Yii::app()->db->password;
        $data     = date("d-m-Y");
        $comando  = "mysqldump -u" . $username . " -p" . $password . " " . $dataBase . " --ignore-table=" . $dataBase . ".pkg_portabilidade --ignore-table=" . $dataBase . ".pkg_cdr_archive --ignore-table=" . $dataBase . ".pkg_cdr_failed > /tmp/base.sql";
        LinuxAccess::exec($comando);
        LinuxAccess::exec("tar czvf /usr/local/src/magnus/backup/backup_voip_Magnus.$data.tgz /tmp/base.sql /etc/asterisk");
        LinuxAccess::exec("rm -f /tmp/base.sql");
    }
}
