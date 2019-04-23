<?php

namespace Wingman\Repositories\Backup;

use Wingman\Interfaces\BackupInterface;
use Spatie\DbDumper\Databases\MySql;

class MysqlBackup implements BackupInterface {

	/**
	 * Backup database
	 */
	public function backup($data)
	{
        $tmpfile = temp_path().'mysql.tmp';

        $db = MySql::create();
        
        $db->setHost($data['host']);
        $db->setHost($data['host']);
        $db->setPort($data['port']);
        $db->setDbName($data['name']);
        $db->setUserName($data['user']);
        $db->setPassword($data['pass']);

        /**
         * Exclude files if needed
         */
        if(isset($data['exclude'])) {
            $db->excludeTables($data['exclude']);
        }

        $db->dumpToFile($tmpfile);

        return $tmpfile;
	}

}