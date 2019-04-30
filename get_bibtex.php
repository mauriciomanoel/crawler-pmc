<?php
    set_time_limit(0);

    spl_autoload_register(function ($class_name) {
        include $class_name . '.php';
    });
    
    $break_line         = "<br>";
    
    //var_dump($query_string); exit;
    define('BREAK_LINE', $break_line);

    $values = file_get_contents("pmc_result.txt"); 
    
    var_dump(    $values);exit;


    try {
        if (empty($query_string)) {
            throw new Exception("Query String not found");
        }         
        if ( ( isset($_GET['page']) && isset($_GET['results']) ) || !isset($_GET['page']) && !isset($_GET['results']) ) {
            throw new Exception("Only one parameter: Page or Results");
        }

        $total              = (int) ($results / 100) + 1;
        
        $file           = "bibtex/" . Util::slug(trim($file_name)) . ".bib";
        $url            = ElsevierScienceDirect::getURL(0, $query_string);
        $cookie         = Util::getCookie($url);
        $user_agent     = (!empty($_SERVER["HTTP_USER_AGENT"])) ? $_SERVER["HTTP_USER_AGENT"] : "Mozilla/5.0 (Macintosh; Intel Mac OS X 10.13; rv:58.0) Gecko/20100101 Firefox/58.0";
        define('USER_AGENT', $user_agent);   
        define('COOKIE', @$cookie);
        define('FILE', $file);
        define('FILE_LOG', Util::slug(trim($file_name)) . "_log.txt");
        
        if (isset($_GET['page'])) {
            ElsevierScienceDirect::start(($page*100), $query_string, $url);
        }  else if (isset($_GET['results'])) {            
            for($offset=0; $offset<=$total; $offset++) {
                ElsevierScienceDirect::start(($offset*100), $query_string, $url);
                $sleep = rand(5,9);
                if ($offset != $total) {
                    Util::showMessage("Wait for " . $sleep . " seconds before executing next page");
                    Util::showMessage("");
                    sleep($sleep);
                }
            }
        }

    } catch(Exception $e) {
        Util::showMessage($e->getMessage());
    }
?>
