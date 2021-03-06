<!DOCTYPE html>
<?php
            session_start();
            if($_SESSION['authentication']!=1)
            {
                    header("location: index.php");
            }
            $flag1=0;
            $flag2=0;
            $flag3=0;
            $flag4=0;
?>
 
<html>
  <head>
    <meta charset="UTF-8">
    <title>Welcome Admin</title>
  
    <!-- Bootstrap -->
    <link rel="stylesheet" type="text/css"  href="css/bootstrap.css">
    <link rel="stylesheet" type="text/css"  href="css/custom.css">
    <link rel="stylesheet" type="text/css" href="fonts/font-awesome/css/font-awesome.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    
    <!-- Slider
    ================================================== -->
    <link href="css/owl.carousel.css" rel="stylesheet" media="screen">
    <link href="css/owl.theme.css" rel="stylesheet" media="screen">

    <!-- Stylesheet
    ================================================== -->
    <link rel="stylesheet" type="text/css"  href="css/style.css">
    <link rel="stylesheet" type="text/css" href="css/responsive.css">
    <link rel="stylesheet" type="text/css" href="css/admin-page.css">

    <script type="text/javascript" src="js/modernizr.custom.js"></script>
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
  
   </head>
   <body>
 
    <!-- Navigation
    ==========================================-->
        <nav id="tf-menu" class="navbar navbar-default"  style="background-color: #000">
            <div class="container-fluid overlay">
                <!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header">
                
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                        <span class="sr-only">Toggle navigation</span>               
                    </button>
                <b>
                    <a class="navbar-brand" href="index.php">Student Management System</a>
                </b>
                </div>

                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <b>
                    <ul class="nav navbar-nav navbar-right navbar-fixed">
                        
                        <li><a href="admin-page.php" class=" btn btn-success">Home</a></li>
                        
                        <li><a href="perinfo.php">Registration</a></li>
                        
                        <li><a href="manage.php" class="page-scroll">Manage</a></li>
                        <li class="dropdown">
                            <a class="dropdown-toggle" data-toggle="dropdown" href="#">Search
                                <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a href="searchStudents.php">Students</a></li>
                                <li><a href="searchAlumini.php">Alumini</a></li>          
                            </ul>
                        </li>
                        <li class="dropdown">
                            <a class="dropdown-toggle" data-toggle="dropdown" href="#">Statistics
                                <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a href="DeptAnalysis.php">Department Strength</a></li>
                                <li><a href="ClassAnalysis.php">Class Strength</a></li>          
                                <li><a href="GenderAnalysis.php">Gender Ratio</a></li> 
                                <li><a href="AdmissionAnalysis.php">Admission Type</a></li> 
                                <li><a href="ScholarsipAnalysis.php">Scholarship</a></li> 
                            </ul>
                        </li>
                        
                        <li class="dropdown">
                            <a class="dropdown-toggle" data-toggle="dropdown" href="#">Placement
                                <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a href="aluByDept.php">Add Student</a></li>
                                <li><a href="aluByDeptFind.php">View Database</a></li>          
                            </ul>
                        </li>                    
                        <li><a href="index.php" class=" btn btn-danger">Log Out</a></li>
                    </ul>
                </b>
                </div><!-- /.navbar-collapse -->
            </div><!-- /.container-fluid -->
        </nav>
    <b>
        <div class="container" id="tf-search">  
            
            <form id="contact" action="" method="post">
                <h2>Department Strength</h2>
    
                <fieldset>
                    <br><br>
                    <div> 
                        <Label style="color: red; font-size: medium" >Select a Year</label>
                        &nbsp;&nbsp;&nbsp;
                        <select name="year" required="true">
                            <option value=2011>2011</option>
                            <option value=2012>2012</option>
                            <option value=2013>2013</option>
                            <option value=2014>2014</option>
                            <option value=2015 selected="true">2015</option>
                            <option value=2016>2016</option>
                            <option value=2017>2017</option>
                            <option value=2018>2018</option>
                            <option value=2019>2019</option>
                        </select>                                                
                    </div>
                </fieldset>
                <br><br>
                <fieldset>
                    <button name="submit" type="submit" id="contact-submit" data-submit="...Sending">Submit</button>
                </fieldset>
    
            </form>
        </div>
    </b>   
        <div class="container-fluid">
            <hr style="border: 1px solid #000">
        </div>
    
        <b>
            <div class="container">              
                                                          
        <?php
            if($_SERVER["REQUEST_METHOD"]=="POST")  
            {
                $year =(int) $_POST['year'];                         
                            
                try 
                {
                    // open connection to MongoDB server
                    $conn = new MongoClient();
                    $db = $conn->selectDB("studentSystem");
                    // access collection
                    $collection = $db->analysis;
                    $cursor = $collection->find(array('yearAd' => $year)); 
                    
                    // iterate cursor to display title of documents                   
                    if (is_array($cursor) || is_object($cursor))
                    { 
                        $map = new MongoCode("function() { emit(this.dept,1); }");
                        $reduce = new MongoCode("function(k, vals) { ".
			"var i=0, sum = 0;".
			"for (i in vals) {".
			"sum += vals[i];}".
			"return sum ;".
			"}");
                        
                        $result = $db->command(array(
			"mapreduce" => "analysis", 
			"map" => $map,
			"reduce" => $reduce,
                         "query" => array("yearAd" => $year),
			"out" => 'test_mapreduce'));
			
                        $users = $db->selectCollection('test_mapreduce')->find();
                        $collection2 = $db->test_mapreduce;
                        $cursor2 = $collection2->find(); 
                     ?>
			 <!-- load api -->
                        <script type="text/javascript" src="http://www.google.com/jsapi"></script>

                        <script type="text/javascript">
                            //load package
                            google.load('visualization', '1', {packages: ['corechart']});
                        </script>

                        <script type="text/javascript">
                            function drawVisualization() 
                            {
                                // Create and populate the data table.
                                var data = google.visualization.arrayToDataTable
                                ([
                                    ['Dept', 'Students'],
                                    <?php
                                            foreach ($users as $document)
                                            {
                                                extract($document);
                                                echo "['{$_id}', {$value}],";
                                            }
                                     ?>
                                ]);

                                // Create and draw the visualization.
                                new google.visualization.PieChart(document.getElementById('visualization')).
                                        draw(data, {title:"Total number of students in Departments for the year <?PHP echo $year;?>"});
                            }
                            google.setOnLoadCallback(drawVisualization);		
                      </script>
                        <b>
                            <div class="container" id="analysis">
                                <h2>Analysis</h2>
                                
                                <div class="row container-fluid">
                                    <div class="col-lg-1 col-lg-offset-3">

                                                <section>
                                                                <div id="visualization" style="width: 600px; height: 400px;"></div>
                                                </section>
                                    </div>

                                </div>

                            </div>
                        </b>
                  
                      <div class="container">
                                <hr style="border: 1px solid #000">
                      </div> 
                      <h2>Grid View</h2>
                      
                        <Label style="color: red;font-size: medium" ><br><?PHP if($_SERVER["REQUEST_METHOD"]=="POST"){echo "Filters set :: Year: ".$year;} ?></label>
                        <table class="table table-hover">
                            <thead>
                                    <tr>
                                        <th>Department</th>
                                        <th>Strength </th>                                        
                                    </tr>
                            </thead>
                            <tbody>
         <?PHP                  
                                foreach ($cursor2 as $document2) 
                                {                            
        ?>
                                    <tr>                                        
                                        <td><?php echo $document2["_id"] . "\n"; ?></td>
                                        <td><?php echo $document2["value"] . "\n"; ?></td>
                                        <br>
                                    </tr>                                                                                                                          
        <?php 
                                }
        ?>
                                
                                </tbody>
                        </table>
                        <div class="container-fluid">
                                <hr style="border: 1px solid #000">
                        </div>                                               
        <?PHP                
                    }
                    else
                    {                       
                        echo'<script type=text/javascript> alter("No Data Found");</script>';
                    }
                    // disconnect from server
                   $conn->close();
                }
                    catch (MongoConnectionException $e) 
                    {
                        die('Error connecting to MongoDB server');
                    } 
                    catch (MongoException $e) 
                    {
                        die('Error: ' . $e->getMessage());
                    }
            }
            
        ?>                     
                      
                        
            </div>
        </b>                            
   </body>         
</html>