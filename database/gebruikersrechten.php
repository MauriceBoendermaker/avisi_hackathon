<?php


namespace database;

    // ID INT
    // Recht VARCHAR(45)
    // Rechten INT (bitmask) ( 1 = read | 2 = write | 4 = update | 8 = delete | 2147483647 = admin )

    class Gebruikersrechten
    {
        private $id;
        private $recht;
        private $rechten;

        public function __construct($id, $recht, $rechten)
        {
            $this->id = $id;
            $this->recht = $recht;
            $this->rechten = $rechten;
        }

        public function getID()
        {
            return $this->id;
        }

        public function setID($id)
        {
            $this->id = $id;
        }

        public function getRecht()
        {
            return $this->recht;
        }

        public function setRecht($recht)
        {
            $this->recht = $recht;
        }

        public function getRechten()
        {
            return $this->rechten;
        }

        public function setRechten($rechten)
        {
            $this->rechten = $rechten;
        }
        
        public function setRechtenFromBool($read, $write, $update, $delete, $admin)
        {
            $this->rechten = 0;
            if ($read) $this->rechten += 1;
            if ($write) $this->rechten += 2;
            if ($update) $this->rechten += 4;
            if ($delete) $this->rechten += 8;
            if ($admin) $this->rechten = 2147483647;
        }

        // returns an array of boolean values for each permission
        public function getPermissions()
        {
            $permissions = array();
            $permissions['read'] = $this->rechten & 1;
            $permissions['write'] = $this->rechten & 2;
            $permissions['update'] = $this->rechten & 4;
            $permissions['delete'] = $this->rechten & 8;
            $permissions['admin'] = $this->rechten == 2147483647;
            return $permissions;
        }
    }