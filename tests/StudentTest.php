<?php
    class Student
    {
        private $name;
        private $id;
        private $date;

        function __construct($initial_name, $initial_id = null, $initial_date)
        {
            $this->name = $initial_name;
            $this->id = $initial_id;
            $this->date = $initial_date;
        }
        function getName()
        {
            return $this->name;
        }
        function setName($new_name)
        {
            $this->name = (string) $new_name;
        }
        function getId()
        {
            return $this->id;
        }
        function setId($new_id)
        {
            $this->id = (int) $new_id;
        }

        function getDate()
        {
            return $this->id;
        }
        function setDate($new_date)
        {
            $this->date = (int) $new_date;
        }

        
