<?php
    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */
    require_once "src/Student.php";
    require_once "src/Course.php";
    $DB = new PDO('pgsql:host=localhost;dbname=registrar');
    class StudentTest extends PHPUnit_Framework_TestCase
    {

            protected function tearDown()
        {
            Student::deleteAll();
            Course::deleteAll();
        }
        //Initialize a Student with a name and be able to get it back out of the object using getName().
        function testGetName()
        {
            //Arrange
            $id = 1;
            $name = "Do dishes.";
            $date='1909-09-08 12:00:00';
            $test_student = new Student($name,$id,$date);
            //No need to save here because we are communicating with the object only and not the database.
            //Act
            $result = $test_student->getName();
            //Assert
            $this->assertEquals($name, $result);
        }
        function testSetName()
        { //can I change the name in the object with setName() after initializing it?
            //Arrange
            $id = 1;
            $name = "Do dishes.";
            $date='1909-09-08 12:00:00';
            $test_student = new Student($name,$id,$date);
            //No need to save here because we are communicating with the object only and not the database.
            //Act
            $test_student->setName("Drink coffee.");
            $result = $test_student->getName();
            //Assert
            $this->assertEquals("Drink coffee.", $result);
        }
        //Next, let's add the Id. property to our Student class. Like any other property it needs a getter and setter.
        //Create a Student with the id in the constructor and be able to get the id back out.
        function testGetId()
        {
            //Arrange
            $id = 1;
            $name = "Wash the dog";
            $date='1909-09-08 12:00:00';
            $test_student = new Student($name, $id,$date);
            //Act
            $result = $test_student->getId();
            //Assert
            $this->assertEquals(1, $result);
        }
        //Create a Student with the id in the constructor and be able to change its value, and then get the new id out.
        function testSetId()
        {
            //Arrange
            $id = 1;
            $name = "Wash the dog";
            $date='1909-09-08 12:00:00';
            $test_student = new Student($name, $id,$date);
            //Act
            $test_student->setId(2);
            //Assert
            $result = $test_student->getId();
            $this->assertEquals(2, $result);
        }
        //CREATE - save method stores all object data in students table.
        function testSave()
        {
            //Arrange
            //create a new student
            $name = "Wash the dog";
            $date='1909-09-08 12:00:00';
            $id = 1;
            $test_student = new Student($name, $id,$date);
            //Act
            //save the student to the database
            //Id should be assigned in database, then stored in object.
            $test_student->save();
            //Assert
            //get all existing students back out of the database.
            //The first and only one should hold the same properties as the test student.
            $result = Student::getAll();
            $this->assertEquals($test_student, $result[0]);
        }
        //This test makes sure that after saving not only are the id's equal, they are not null.
        function testSaveSetsId()
        {
            //Arrange
            //create new student
            $name = "Wash the dog";
            $id = 1;
            $date='1909-09-08 12:00:00';
            $test_student = new Student($name, $id,$date);
            //Act
            //save it. Id should be assigned in database, then stored in object.
            $test_student->save();
            //Assert
            //That id in the object should be numeric (not null)
            $this->assertEquals(true, is_numeric($test_student->getId()));
        }
        // //READ - All students
        // //Can't run the previous two tests without getAll().
        // //This method should return an array of all Student objects from the students table.
        // //Since it isn't specifically for only one Student, it is for all, it should be a static method.
        function testGetAll()
        {
            //Arrange
            //Create and save more than one Student object.
            $name = "Wash the dog";
            $id = 1;
            $date='1909-09-08 12:00:00';
            $test_student = new Student($name, $id,$date);
            $test_student->save();
            $name2 = "Water the lawn";
            $id2 = 2;
            $test_student2 = new Student($name2, $id2,$date);
            $test_student2->save();
            //Act
            //Query the database to get all existing saved students as objects.
            $result = Student::getAll();
            //Assert
            //We should get our two test students back out in $result.
            //Remember the [$thing1, $thing2] notation is used for an array.
            $this->assertEquals([$test_student, $test_student2], $result);
        }
        // //Now that we are saving, we need a method to delete everything out of our database too.
        // //For our tests to run we need to clear our test database with a deleteAll function after each test.
        // //Since this also deals with more than one Student it should be a static method.
        function testDeleteAll()
        {
            //Arrange
            //We need some students saved into the database so that we can make sure our deleteAll method removes them all.
            $name = "Wash the dog";
            $id = 1;
            $date='1909-09-08 12:00:00';
            $test_student = new Student($name, $id,$date);
            $test_student->save();
            $name2 = "Water the lawn";
            $id2 = 2;
            $test_student2 = new Student($name2, $id2,$date);
            $test_student2->save();
            //Act
            //Set all students on fire. Delete them.
            Student::deleteAll();
            //Assert
            //Now when we call getAll, we should get an empty array because we deleted all students.
            $result = Student::getAll();
            $this->assertEquals([], $result);
        }
        // //We have Create, Read (all), Delete (all). What's left in CRUD?
        // //Read sigular (view a single student),
        // //Update (edit an existing singular student),
        // //Delete (singular - remove student the method is called on.)
        // //All of these require us to be able to select a Student by its unique id. So the FIND method is next.
        // //find() method should take an id as input and return the corresponding student.
        // //since it must search through all students it should be a static method.
        function testFind()
        {
            //Arrange
            //To test a search function we must have some students to search through.
            //Create and save 2 students.
            $name = "Wash the dog";
            $id = 1;
            $date='1909-09-08 12:00:00';
            $test_student = new Student($name, $id,$date);
            $test_student->save();
            $name2 = "Water the lawn";
            $id2 = 2;
            $test_student2 = new Student($name2, $id2,$date);
            $test_student2->save();
            //Act
            //call the method we intend to write.
            //look through all students for the student matching the first student's assigned id number.
            //store the output in $result.
            $result = Student::find($test_student->getId());
            //Assert
            //we should get the same object back out of the search as the one we were looking for if our search works correctly.
            $this->assertEquals($test_student, $result);
        }
        // function testUpdate()
        // {
        //     //Arrange
        //     $name = "Wash the dog";
        //     $id = 1;
        //     $test_student = new Student($name, $id);
        //     $test_student->save();
        //     $new_name = "Clean the dog";
        //     //Act
        //     $test_student->update($new_name);
        //     //Assert
        //     $this->assertEquals("Clean the dog", $test_student->getName());
        // }
        // function testDeleteTask()
        // {
        //     //Arrange
        //     $name = "Wash the dog";
        //     $id = 1;
        //     $test_student = new Student($name, $id);
        //     $test_student->save();
        //     $name2 = "Water the lawn";
        //     $id2 = 2;
        //     $test_student2 = new Student($name2, $id2);
        //     $test_student2->save();
        //     //Act
        //     $test_student->delete();
        //     //Assert
        //     $this->assertEquals([$test_student2], Student::getAll());
        // }
        // //Now add methods to add a course to a student, and get all the categories associated with the current student.
        // function testAddCourses()
        // {
        //     //Arrange
        //     $name = "Work stuff";
        //     $id = 1;
        //     $test_course = new Course($name, $id);
        //     $test_course->save();
        //     $name = "File reports";
        //     $id2 = 2;
        //     $test_student = new Student($name, $id2);
        //     $test_student->save();
        //     //Act
        //     $test_student->addCourses($test_course);
        //     //Assert
        //     $this->assertEquals($test_student->getCategories(), [$test_course]);
        // }
        // function testGetCategories()
        // {
        //     //Arrange
        //     $name = "Work stuff";
        //     $id = 1;
        //     $test_course = new Course($name, $id);
        //     $test_course->save();
        //     $name2 = "Volunteer stuff";
        //     $id2 = 2;
        //     $test_course2 = new Course($name2, $id2);
        //     $test_course2->save();
        //     $name = "File reports";
        //     $id3 = 3;
        //     $test_student = new Student($name, $id3);
        //     $test_student->save();
        //     //Act
        //     $test_student->addCourses($test_course);
        //     $test_student->addCourses($test_course2);
        //     //Assert
        //     $this->assertEquals($test_student->getCategories(), [$test_course, $test_course2]);
        // }
        // //When we call delete on a student it should delete all mention of that student from both the students table and the join table.
        // function testDelete()
        // {
        //     //Arrange
        //     $name = "Work stuff";
        //     $id = 1;
        //     $test_course = new Course($name, $id);
        //     $test_course->save();
        //     $name = "File reports";
        //     $id2 = 2;
        //     $test_student = new Student($name, $id2);
        //     $test_student->save();
        //     //Act
        //     $test_student->addCourses($test_course);
        //     $test_student->delete();
        //     //Assert
        //     $this->assertEquals([], $test_course->getTasks());
        // }

    }
?>
